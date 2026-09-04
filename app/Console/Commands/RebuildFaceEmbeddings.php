<?php

namespace App\Console\Commands;

use App\Models\FaceEnrollmentCapture;
use App\Models\User;
use App\Services\BiometricProfileService;
use App\Services\KioskFaceEngineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RebuildFaceEmbeddings extends Command
{
    protected $signature = 'face:rebuild-embeddings {--dry-run : Validate legacy captures without writing profiles}';
    protected $description = 'Rebuild SFace embeddings from legacy enrollment captures.';

    public function __construct(
        private KioskFaceEngineService $engine,
        private BiometricProfileService $profiles,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $captures = FaceEnrollmentCapture::query()->with('session.user')->orderBy('id')->get();
        $written = 0;

        foreach ($captures as $capture) {
            $user = $capture->session?->user;
            $captureImage = $this->resolveCaptureImage($capture->captured_image);
            if (!$user instanceof User || $captureImage === null) {
                $this->warn("Capture {$capture->id} dilewati: data tidak lengkap.");
                continue;
            }

            $result = $this->engine->enroll(
                $user,
                ['selfie_frames' => [$captureImage]],
                [
                    'expected_pose' => str_starts_with($capture->phase_key, 'front') ? 'front' : $capture->phase_key,
                    'rebuild' => true,
                ],
            );

            if (!($result['success'] ?? false)) {
                $this->warn("Capture {$capture->id} gagal: {$result['message']}");
                continue;
            }

            if ($dryRun) {
                $this->line("[dry-run] Capture {$capture->id} valid untuk {$user->name}.");
                continue;
            }

            $this->profiles->createProfile([
                'user_id' => $user->id,
                'engine' => 'opencv',
                'model' => config('kiosk_face_v2.model', 'sface'),
                'model_version' => config('kiosk_face_v2.model_version', 'v1'),
                'pose' => str_starts_with($capture->phase_key, 'front') ? 'front' : $capture->phase_key,
                'dimension' => config('biometric_v2.dimension', 128),
                'embedding' => $result['face_embedding'],
                'quality_score' => $result['quality_score'] ?? null,
                'liveness_score' => $result['liveness_score'] ?? null,
                'source' => 'legacy_rebuild',
                'status' => 'active',
                'metadata' => ['legacy_capture_id' => $capture->id],
                'enrolled_at' => now(),
            ]);
            $written++;
        }

        $this->info($dryRun ? 'Dry-run selesai.' : "{$written} embedding SFace berhasil dibuat.");
        return self::SUCCESS;
    }

    private function resolveCaptureImage(mixed $capturedImage): ?string
    {
        if (!is_string($capturedImage) || $capturedImage === '') {
            return null;
        }

        if (str_starts_with($capturedImage, 'data:image/')) {
            return $capturedImage;
        }

        $urlPath = parse_url($capturedImage, PHP_URL_PATH);
        $relativePath = is_string($urlPath) && str_contains($urlPath, '/storage/')
            ? ltrim((string) substr($urlPath, strpos($urlPath, '/storage/') + 9), '/')
            : ltrim($capturedImage, '/');

        if (!Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        $contents = Storage::disk('public')->get($relativePath);
        $mimeType = Storage::disk('public')->mimeType($relativePath) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
    }
}
