<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\FaceEnrollmentCapture;
use App\Models\Madrasah;
use App\Models\FaceEnrollmentSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FaceEnrollmentKioskController extends Controller
{
    private array $phases = [
        ['key' => 'front', 'label' => 'Lihat Depan'],
        ['key' => 'left', 'label' => 'Lihat Kiri'],
        ['key' => 'right', 'label' => 'Lihat Kanan'],
        ['key' => 'up', 'label' => 'Lihat Atas'],
    ];

    public function __construct()
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
            'phase_key' => ['required', 'string', 'in:front,left,right,up'],
            'phase_label' => ['required', 'string', 'max:64'],
            'capture_index' => ['required', 'integer', 'min:1', 'max:4'],
            'captured_image' => ['required', 'string', 'min:100'],
            'face_descriptor' => ['nullable', 'array'],
            'face_descriptor.*' => ['numeric'],
            'quality_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $capture = FaceEnrollmentCapture::updateOrCreate(
            [
                'session_id' => $session->id,
                'phase_key' => $validated['phase_key'],
            ],
            [
                'phase_label' => $validated['phase_label'],
                'capture_index' => $validated['capture_index'],
                'captured_image' => $validated['captured_image'],
                'face_descriptor' => $validated['face_descriptor'] ?? null,
                'quality_score' => $validated['quality_score'] ?? null,
                'liveness_score' => $validated['liveness_score'] ?? null,
                'metadata' => $validated['metadata'] ?? null,
            ]
        );

        $session->update([
            'active_phase' => $validated['phase_key'],
            'face_descriptor' => $validated['face_descriptor'] ?? $session->face_descriptor,
            'quality_score' => $validated['quality_score'] ?? $session->quality_score,
            'liveness_score' => $validated['liveness_score'] ?? $session->liveness_score,
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
            'face_descriptor' => ['required', 'array', 'min:32'],
            'face_descriptor.*' => ['numeric'],
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

        $teacher = $session->user;
        $faceData = [
            'face_descriptor' => $validated['face_descriptor'],
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
            'liveness_score' => $validated['liveness_score'] ?? null,
            'quality_score' => $validated['quality_score'] ?? null,
            'enrolled_at' => now()->toIso8601String(),
            'enrolled_by' => Auth::id(),
            'enrollment_channel' => 'kiosk_2_admin_only',
            'registered_device_id' => null,
            'source_session_uuid' => $session->session_uuid,
            'metadata' => $validated['metadata'] ?? [],
        ];

        $teacher->face_data = json_encode($faceData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $teacher->face_id = (string) Str::uuid();
        $teacher->face_registered_at = now();
        $teacher->face_verification_required = true;
        $teacher->save();

        $session->update([
            'status' => 'completed',
            'face_descriptor' => $validated['face_descriptor'],
            'face_data' => $faceData,
            'quality_score' => $validated['quality_score'] ?? $session->quality_score,
            'liveness_score' => $validated['liveness_score'] ?? $session->liveness_score,
            'completed_at' => now(),
            'metadata' => array_merge($session->metadata ?? [], $validated['metadata'] ?? []),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data wajah {$teacher->name} berhasil disimpan untuk presensi di device masing-masing.",
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'face_registered_at' => optional($teacher->face_registered_at)?->toIso8601String(),
                'face_id' => $teacher->face_id,
            ],
            'session' => $session->fresh(['captures']),
        ]);
    }

    private function authorizeSession(FaceEnrollmentSession $session): void
    {
        if ($session->operator_user_id !== Auth::id() && Auth::user()?->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }
    }
}
