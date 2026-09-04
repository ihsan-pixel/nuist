<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\FaceEnrollmentCapture;
use App\Models\Madrasah;
use App\Models\FaceEnrollmentSession;
use App\Models\User;
use App\Services\BiometricProfileService;
use App\Services\KioskFaceEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FaceEnrollmentKioskController extends Controller
{
    private array $phases = [
        ['key' => 'front', 'label' => 'Lihat Depan'],
        ['key' => 'front_2', 'label' => 'Lihat Depan (Template 2)'],
        ['key' => 'left', 'label' => 'Lihat Kiri'],
        ['key' => 'right', 'label' => 'Lihat Kanan'],
        ['key' => 'up', 'label' => 'Lihat Atas'],
        ['key' => 'down', 'label' => 'Lihat Bawah'],
    ];

    public function __construct(
        private KioskFaceEngineService $faceEngine,
        private BiometricProfileService $profileService,
    )
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index(Request $request)
    {
        $schools = Madrasah::query()
            ->orderBy('name')
            ->get(['id', 'name', 'kabupaten']);

        $selectedMadrasahId = $request->filled('madrasah_id')
            ? (int) $request->input('madrasah_id')
            : null;

        if ($selectedMadrasahId !== null && !$schools->pluck('id')->contains($selectedMadrasahId)) {
            $selectedMadrasahId = null;
        }

        $teachersQuery = User::query()
            ->where('role', 'tenaga_pendidik')
            ->with('madrasah')
            ->orderBy('name');

        if ($selectedMadrasahId !== null) {
            $teachersQuery->where('madrasah_id', $selectedMadrasahId);
        }

        $teachers = $teachersQuery->get(['id', 'name', 'nip', 'nuptk', 'madrasah_id', 'face_registered_at']);

        return view('kiosk.face-enrollment-kiosk', [
            'schools' => $schools,
            'selectedMadrasahId' => $selectedMadrasahId,
            'teachers' => $teachers,
            'teachersPayload' => $teachers->map(fn (User $teacher) => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'nip' => $teacher->nip,
                'nuptk' => $teacher->nuptk,
                'school' => $teacher->madrasah?->nama ?? $teacher->madrasah?->name ?? null,
                'has_face' => (bool) $teacher->face_registered_at,
                'face_registered_at' => optional($teacher->face_registered_at)?->toIso8601String(),
            ])->values()->all(),
            'phases' => $this->phases,
        ]);
    }

    public function startSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'device_info' => ['nullable', 'string', 'max:1000'],
            'metadata' => ['nullable', 'array'],
        ]);

        $teacher = User::whereKey($validated['user_id'])->where('role', 'tenaga_pendidik')->firstOrFail();

        $session = FaceEnrollmentSession::create([
            'session_uuid' => (string) Str::uuid(),
            'user_id' => $teacher->id,
            'school_id' => $teacher->madrasah_id,
            'operator_user_id' => Auth::id(),
            'status' => 'draft',
            'active_phase' => $this->phases[0]['key'],
            'metadata' => array_merge($validated['metadata'] ?? [], [
                'device_info' => $validated['device_info'] ?? null,
                'phase_count' => count($this->phases),
            ]),
        ]);

        return response()->json([
            'success' => true,
            'session' => $session->load('captures'),
            'phases' => $this->phases,
        ]);
    }

    public function storeCapture(Request $request, FaceEnrollmentSession $session): JsonResponse
    {
        $this->authorizeSession($session);

        $validated = $request->validate([
            'phase_key' => ['required', 'string', 'in:front,front_2,left,right,up,down'],
            'phase_label' => ['required', 'string', 'max:64'],
            'capture_index' => ['required', 'integer', 'min:1', 'max:6'],
            'captured_image' => ['required', 'string', 'min:100'],
            'frames' => ['required', 'array', 'min:1', 'max:8'],
            'frames.*' => ['required', 'string', 'starts_with:data:image/'],
            'face_descriptor' => ['nullable', 'array'],
            'face_descriptor.*' => ['numeric'],
            'quality_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $engineResult = $this->faceEngine->enroll(
            $session->user,
            ['selfie_frames' => $validated['frames']],
            ['expected_pose' => str_starts_with($validated['phase_key'], 'front') ? 'front' : $validated['phase_key'], 'enrollment_phase' => $validated['phase_key']],
        );

        if (!($engineResult['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'code' => $this->mapEngineReason($engineResult),
                'message' => $engineResult['message'] ?? 'Engine wajah menolak capture ini.',
                'reason' => $engineResult['reason'] ?? null,
                'quality_score' => $engineResult['quality_score'] ?? null,
                'liveness_score' => $engineResult['liveness_score'] ?? null,
                'pose' => $engineResult['pose'] ?? null,
                'engine' => $engineResult,
            ], (int) ($engineResult['status'] ?? 422));
        }

        $embedding = $engineResult['face_embedding'] ?? [];
        if (!is_array($embedding) || count($embedding) !== (int) config('biometric_v2.dimension', 128)) {
            return response()->json([
                'success' => false,
                'code' => 'INVALID_EMBEDDING',
                'message' => 'Embedding SFace dari engine tidak valid.',
            ], 422);
        }

        $previousCapture = FaceEnrollmentCapture::query()
            ->where('session_id', $session->id)
            ->where('phase_key', $validated['phase_key'])
            ->first();
        $capturedImage = $this->storeCapturedImage(
            $session->user,
            $validated['phase_key'],
            $engineResult['captured_image'] ?? $validated['captured_image'],
        );

        if ($previousCapture) {
            $this->deleteStoredCapture($previousCapture->captured_image);
        }

        $capture = FaceEnrollmentCapture::updateOrCreate(
            [
                'session_id' => $session->id,
                'phase_key' => $validated['phase_key'],
            ],
            [
                'phase_label' => $validated['phase_label'],
                'capture_index' => $validated['capture_index'],
                'captured_image' => $capturedImage,
                'face_descriptor' => $embedding,
                'quality_score' => $engineResult['quality_score'] ?? null,
                'liveness_score' => $engineResult['liveness_score'] ?? null,
                'metadata' => array_merge($validated['metadata'] ?? [], [
                    'provider' => $engineResult['provider'] ?? config('kiosk_face_v2.provider'),
                    'model' => $engineResult['model'] ?? config('kiosk_face_v2.model'),
                    'model_version' => $engineResult['model_version'] ?? config('kiosk_face_v2.model_version'),
                    'detection_score' => $engineResult['detection_score'] ?? null,
                    'engine_metadata' => $engineResult['metadata'] ?? [],
                ]),
            ]
        );

        $session->update([
            'active_phase' => $validated['phase_key'],
            'face_descriptor' => $embedding,
            'quality_score' => $engineResult['quality_score'] ?? null,
            'liveness_score' => $engineResult['liveness_score'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'capture' => $capture,
            'completed_phases' => $session->captures()->count(),
            'remaining_phases' => max(0, count($this->phases) - $session->captures()->count()),
        ]);
    }

    public function complete(Request $request, FaceEnrollmentSession $session): JsonResponse
    {
        $this->authorizeSession($session);

        $validated = $request->validate([
            'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'quality_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $captures = $session->captures()->orderBy('capture_index')->get();
        $requiredPhaseCount = count($this->phases);
        if ($captures->count() < $requiredPhaseCount) {
            throw ValidationException::withMessages([
                'captures' => "Semua {$requiredPhaseCount} fase belum tersimpan. Selesaikan seluruh capture terlebih dahulu.",
            ]);
        }

        $requiredKeys = collect($this->phases)->pluck('key')->sort()->values()->all();
        $actualKeys = $captures->pluck('phase_key')->sort()->values()->all();
        if ($requiredKeys !== $actualKeys) {
            throw ValidationException::withMessages([
                'captures' => 'Fase enrollment tidak lengkap atau tidak valid.',
            ]);
        }

        $embeddings = $captures->map(fn (FaceEnrollmentCapture $capture) => $capture->face_descriptor)
            ->filter(fn ($embedding) => is_array($embedding) && count($embedding) === (int) config('biometric_v2.dimension', 128))
            ->values();
        if ($embeddings->count() !== $requiredPhaseCount) {
            throw ValidationException::withMessages([
                'captures' => 'Tidak semua fase memiliki embedding SFace yang valid.',
            ]);
        }

        $teacher = $session->user;
        $faceData = [
            'face_embedding' => $embeddings->first(),
            'face_embedding_dimension' => (int) config('biometric_v2.dimension', 128),
            'face_provider' => config('kiosk_face_v2.provider', 'opencv_sface'),
            'face_model' => config('kiosk_face_v2.model', 'sface'),
            'face_model_version' => config('kiosk_face_v2.model_version', 'v1'),
            'descriptors' => $embeddings->all(),
            'face_descriptor' => null,
            'captured_faces' => $captures->map(fn (FaceEnrollmentCapture $capture) => [
                'phase_key' => $capture->phase_key,
                'phase_label' => $capture->phase_label,
                'capture_index' => $capture->capture_index,
                'captured_image' => $capture->captured_image,
                'face_descriptor' => $capture->face_descriptor,
                'quality_score' => $capture->quality_score,
                'liveness_score' => $capture->liveness_score,
                'created_at' => optional($capture->created_at)?->toIso8601String(),
            ])->values()->all(),
            'enrollment_phase_count' => $captures->count(),
            'liveness_score' => $captures->min('liveness_score'),
            'quality_score' => $captures->min('quality_score'),
            'enrolled_at' => now()->toIso8601String(),
            'enrolled_by' => Auth::id(),
            'enrollment_channel' => 'kiosk_2_admin_only',
            'registered_device_id' => null,
            'source_session_uuid' => $session->session_uuid,
            'metadata' => $validated['metadata'] ?? [],
        ];

        $this->profileService->deactivateCompatibleProfiles(
            $teacher,
            'opencv',
            config('kiosk_face_v2.model', 'sface'),
            (int) config('biometric_v2.dimension', 128),
            config('kiosk_face_v2.model_version', 'v1'),
        );
        foreach ($captures as $capture) {
            $this->profileService->createProfile([
                'user_id' => $teacher->id,
                'engine' => 'opencv',
                'model' => config('kiosk_face_v2.model', 'sface'),
                'model_version' => config('kiosk_face_v2.model_version', 'v1'),
                'pose' => $capture->phase_key,
                'dimension' => (int) config('biometric_v2.dimension', 128),
                'embedding' => $capture->face_descriptor,
                'quality_score' => $capture->quality_score,
                'liveness_score' => $capture->liveness_score,
                'source' => 'kiosk_enrollment',
                'status' => 'active',
                'metadata' => $capture->metadata ?? [],
                'enrolled_at' => now(),
            ]);
        }

        $session->update([
            'status' => 'completed',
            'face_descriptor' => $embeddings->first(),
            'face_data' => $faceData,
            'quality_score' => $captures->min('quality_score'),
            'liveness_score' => $captures->min('liveness_score'),
            'completed_at' => now(),
            'metadata' => array_merge($session->metadata ?? [], $validated['metadata'] ?? []),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data wajah {$teacher->name} berhasil disimpan untuk presensi di device masing-masing.",
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'biometric_v2_registered' => true,
            ],
            'session' => $session->fresh(['captures']),
        ]);
    }

    public function reset(FaceEnrollmentSession $session): JsonResponse
    {
        $this->authorizeSession($session);

        if ($session->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi yang sudah selesai tidak dapat diulang dari awal.',
            ], 422);
        }

        $session->captures
            ->each(fn (FaceEnrollmentCapture $capture) => $this->deleteStoredCapture($capture->captured_image));

        // Capture draft ikut terhapus melalui foreign key cascade.
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesi enrollment berhasil direset dari awal.',
        ]);
    }

    private function authorizeSession(FaceEnrollmentSession $session): void
    {
        if ($session->operator_user_id !== Auth::id() && Auth::user()?->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }
    }

    private function storeCapturedImage(User $teacher, string $phaseKey, string $capturedImage): string
    {
        if (!preg_match('#^data:image/(jpeg|jpg|png);base64,(.+)$#is', $capturedImage, $matches)) {
            throw ValidationException::withMessages([
                'captured_image' => 'Hasil capture wajah bukan gambar yang valid.',
            ]);
        }

        $image = base64_decode($matches[2], true);
        if ($image === false) {
            throw ValidationException::withMessages([
                'captured_image' => 'Hasil capture wajah tidak dapat diproses.',
            ]);
        }

        $poseLabels = [
            'front' => 'Menghadap Depan',
            'front_2' => 'Menghadap Depan 2',
            'left' => 'Menghadap Kiri',
            'right' => 'Menghadap Kanan',
            'up' => 'Menghadap Atas',
            'down' => 'Menghadap Bawah',
        ];
        $schoolName = $teacher->madrasah?->name ?: 'Tanpa Sekolah';
        $userName = $this->displayNameWithDegree($teacher);
        $extension = strtolower($matches[1]) === 'png' ? 'png' : 'jpg';
        $fileName = implode('-', [
            Str::slug($userName ?: 'Tanpa Nama'),
            Str::slug($poseLabels[$phaseKey] ?? $phaseKey),
            Str::lower((string) Str::uuid()),
        ]) . '.' . $extension;
        $relativePath = implode('/', [
            'face-enrollment-captures',
            Str::slug($schoolName),
            Str::slug($userName ?: 'Tanpa Nama'),
            $fileName,
        ]);

        if (!Storage::disk('public')->put($relativePath, $image)) {
            throw new \RuntimeException('Foto hasil capture wajah gagal disimpan.');
        }

        return Storage::disk('public')->url($relativePath);
    }

    private function displayNameWithDegree(User $teacher): string
    {
        $name = trim((string) $teacher->name);
        $degree = trim((string) $teacher->gelar);

        if ($name === '' || $degree === '') {
            return trim($name . ' ' . $degree);
        }

        $normalizedName = strtolower((string) preg_replace('/[^a-z0-9]/i', '', $name));
        $normalizedDegree = strtolower((string) preg_replace('/[^a-z0-9]/i', '', $degree));

        return str_ends_with($normalizedName, $normalizedDegree)
            ? $name
            : $name . ' ' . $degree;
    }

    private function deleteStoredCapture(?string $capturedImage): void
    {
        if (!is_string($capturedImage) || str_starts_with($capturedImage, 'data:image/')) {
            return;
        }

        $path = parse_url($capturedImage, PHP_URL_PATH);
        if (!is_string($path)) {
            return;
        }

        $relativePath = ltrim(Str::after($path, '/storage/'), '/');
        if (Str::startsWith($relativePath, 'face-enrollment-captures/')) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function mapEngineReason(array $result): string
    {
        $reason = strtoupper((string) ($result['reason'] ?? $result['notes'] ?? data_get($result, 'metadata.reason', 'ENGINE_ERROR')));

        return match ($reason) {
            'NO_FACE' => 'NO_FACE',
            'MULTIPLE_FACES' => 'MULTIPLE_FACES',
            'FACE_TOO_SMALL' => 'FACE_TOO_SMALL',
            'LOW_QUALITY' => 'LOW_QUALITY',
            'LOW_LIVENESS', 'LIVENESS_BELOW_THRESHOLD' => 'LOW_LIVENESS',
            'INVALID_POSE' => 'INVALID_POSE',
            default => 'ENGINE_ERROR',
        };
    }
}
