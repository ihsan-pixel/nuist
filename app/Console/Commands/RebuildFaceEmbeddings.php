<?php

namespace App\Console\Commands;

use App\Models\FaceEnrollmentSession;
use App\Models\User;
use App\Services\BiometricProfileService;
use App\Services\KioskFaceEngineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RebuildFaceEmbeddings extends Command
{
    protected $signature = 'face:rebuild-embeddings {--dry-run : Validate captures without writing profiles} {--user= : Process only this user ID}';

    protected $description = 'Build ArcFace profiles from completed enrollment photos, preserving existing profiles.';

    public function __construct(
        private KioskFaceEngineService $engine,
        private BiometricProfileService $profiles,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        if (config('kiosk_face_v2.model') !== 'arcface'
            || config('biometric_v2.engine') !== 'onnxruntime'
            || (int) config('biometric_v2.dimension') !== 512) {
            $this->error('Konfigurasi harus menggunakan onnxruntime / arcface / 512 dimensi.');

            return self::FAILURE;
        }
        if ($this->option('user') !== null && ! ctype_digit((string) $this->option('user'))) {
            $this->error('--user harus berupa ID numerik.');

            return self::FAILURE;
        }
        $sessions = FaceEnrollmentSession::query()->with(['user', 'captures'])
            ->where('status', 'completed')
            ->when($this->option('user') !== null, fn ($query) => $query->where('user_id', $this->option('user')))
            ->orderByDesc('id')->get()->unique('user_id');
        $written = 0;
        $failed = 0;

        foreach ($sessions as $session) {
            $user = $session->user;
            $pending = [];
            if (! $user instanceof User || $session->captures->count() < 6) {
                $this->warn("Session {$session->id} dilewati: minimal 6 capture diperlukan.");
                $failed++;

                continue;
            }
            foreach ($session->captures as $capture) {
                try {
                    $captureImage = $this->resolveCaptureImage($capture->captured_image);
                    if (! $user instanceof User || $captureImage === null) {
                        throw new \RuntimeException('File foto tidak tersedia.');
                    }

                    $result = $this->engine->enroll(
                        $user,
                        ['selfie_frames' => [$captureImage]],
                        [
                            'expected_pose' => str_starts_with($capture->phase_key, 'front') ? 'front' : $capture->phase_key,
                            'rebuild' => true,
                        ],
                    );

                    if (! ($result['success'] ?? false)) {
                        throw new \RuntimeException($result['message'] ?? 'Engine gagal memproses foto.');
                    }

                    $embedding = $result['face_embedding'] ?? [];
                    if (($result['provider'] ?? null) !== 'insightface_arcface'
                        || ($result['model'] ?? null) !== 'arcface'
                        || ($result['model_version'] ?? null) !== config('kiosk_face_v2.model_version')
                        || ! is_array($embedding) || count($embedding) !== 512) {
                        throw new \RuntimeException('Metadata atau dimensi hasil engine tidak kompatibel.');
                    }
                    $norm = 0.0;
                    foreach ($embedding as $value) {
                        if (! is_numeric($value) || ! is_finite((float) $value)) {
                            throw new \RuntimeException('Embedding mengandung nilai tidak valid.');
                        }
                        $norm += (float) $value * (float) $value;
                    }
                    if (abs(sqrt($norm) - 1.0) > 0.01) {
                        throw new \RuntimeException('Embedding belum dinormalisasi L2.');
                    }
                    $pending[] = [
                        'user_id' => $user->id,
                        'engine' => 'onnxruntime',
                        'model' => 'arcface',
                        'model_version' => $result['model_version'],
                        'pose' => str_starts_with($capture->phase_key, 'front') ? 'front' : $capture->phase_key,
                        'dimension' => 512,
                        'embedding' => $result['face_embedding'],
                        'quality_score' => $result['quality_score'] ?? null,
                        'liveness_score' => $result['liveness_score'] ?? null,
                        'source' => 'legacy_rebuild',
                        'status' => 'active',
                        'metadata' => ['legacy_capture_id' => $capture->id, 'legacy_session_id' => $session->id],
                        'enrolled_at' => now(),
                    ];
                } catch (\Throwable $exception) {
                    $this->warn("Capture {$capture->id} gagal: {$exception->getMessage()}");
                    $pending = [];
                    $failed++;
                    break;
                }
            }
            if ($pending === []) {
                continue;
            }
            if ($dryRun) {
                $this->line("[dry-run] User {$user->id}: ".count($pending).' capture valid; tidak ada perubahan database.');

                continue;
            }
            try {
                $written += DB::transaction(function () use ($user, $pending) {
                    User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
                    $created = 0;
                    foreach ($pending as $attributes) {
                        $exists = $user->biometricProfiles()->where('engine', 'onnxruntime')
                            ->where('model', 'arcface')->where('model_version', $attributes['model_version'])
                            ->where('status', 'active')
                            ->where('metadata->legacy_capture_id', $attributes['metadata']['legacy_capture_id'])->exists();
                        if (! $exists) {
                            $this->profiles->createProfile($attributes);
                            $created++;
                        }
                    }

                    return $created;
                });
            } catch (\Throwable $exception) {
                $this->warn("User {$user->id} gagal disimpan; transaksi dibatalkan: {$exception->getMessage()}");
                $failed++;
            }
        }

        $this->info($dryRun ? 'Dry-run selesai; database tidak berubah.' : "{$written} profil ArcFace dibuat; profil lama dipertahankan.");
        if ($sessions->isEmpty()) {
            $this->warn('Tidak ditemukan session pendaftaran completed.');

            return self::FAILURE;
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolveCaptureImage(mixed $capturedImage): ?string
    {
        if (! is_string($capturedImage) || $capturedImage === '') {
            return null;
        }

        if (str_starts_with($capturedImage, 'data:image/')) {
            return $capturedImage;
        }

        $urlPath = parse_url($capturedImage, PHP_URL_PATH);
        $relativePath = is_string($urlPath) && str_contains($urlPath, '/storage/')
            ? ltrim((string) substr($urlPath, strpos($urlPath, '/storage/') + 9), '/')
            : ltrim($capturedImage, '/');

        if (! Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        $contents = Storage::disk('public')->get($relativePath);
        $mimeType = Storage::disk('public')->mimeType($relativePath) ?: 'image/jpeg';

        return 'data:'.$mimeType.';base64,'.base64_encode($contents);
    }
}
