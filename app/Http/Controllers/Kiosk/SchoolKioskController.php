<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use App\Services\AttendanceKioskAccessService;
use App\Services\AttendanceValidationService;
use App\Services\FaceVerificationService;
use App\Services\KioskFaceEngineService;
use App\Services\MobileAttendanceSettingsService;
use App\Services\SchoolKioskAttendanceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SchoolKioskController extends Controller
{
    public function __construct(
        private AttendanceKioskAccessService $attendanceKioskAccessService,
        private MobileAttendanceSettingsService $mobileAttendanceSettingsService,
        private SchoolKioskAttendanceService $schoolKioskAttendanceService,
        private FaceVerificationService $faceVerificationService,
        private AttendanceValidationService $attendanceValidationService,
        private KioskFaceEngineService $kioskFaceEngineService,
    ) {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $allowed = in_array($user->role, ['super_admin', 'admin', 'pengurus'], true);

            if (!$allowed && $user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') {
                $allowed = true;
            }

            if (!$allowed) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $operator = Auth::user();
        $device = $this->attendanceKioskAccessService->resolveDeviceByToken($request->cookie('nuist_kiosk_token'));
        $accessGranted = false;
        $accessMessage = 'Komputer presensi belum tervalidasi.';
        $teachers = collect();

        try {
            $device = $this->resolveAuthorizedDevice($request, $operator);
            $accessGranted = true;
            $accessMessage = 'Perangkat kiosk siap digunakan.';
            $teachers = $this->teachersQuery($device)
                ->orderBy('name')
                ->get(['id', 'name', 'nip', 'nuptk', 'ketugasan', 'face_registered_at']);

            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_access',
                status: 'success',
                device: $device,
                operator: $operator,
                payloadSnapshot: ['message' => $accessMessage],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );
        } catch (AuthorizationException $exception) {
            $accessMessage = $exception->getMessage();

            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_access',
                status: 'denied',
                device: $device,
                operator: $operator,
                payloadSnapshot: ['message' => $accessMessage],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );
        }

        return view('kiosk.school-kiosk', [
            'device' => $device,
            'accessGranted' => $accessGranted,
            'accessMessage' => $accessMessage,
            'teacherCount' => $teachers->count(),
            'teachers' => $teachers,
            'verificationMode' => $this->mobileAttendanceSettingsService->currentMode(),
            'verificationLabel' => $this->mobileAttendanceSettingsService->modeLabel(),
            'teachersWithoutFaceCount' => $teachers->whereNull('face_registered_at')->count(),
            'faceEngineDriver' => $this->kioskFaceEngineService->driver(),
            'faceEngineLabel' => $this->kioskFaceEngineService->displayLabel(),
            'faceEngineUsesPython' => $this->kioskFaceEngineService->usesPython(),
        ]);
    }

    public function checkLocation(Request $request): JsonResponse
    {
        $operator = Auth::user();

        try {
            $device = $this->resolveAuthorizedDevice($request, $operator);
            $validated = $request->validate([
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
                'accuracy' => ['nullable', 'numeric'],
                'location_readings' => ['nullable', 'string'],
            ]);

            $school = $device->madrasah;
            if (!$school) {
                throw ValidationException::withMessages([
                    'location' => 'Perangkat kiosk belum terhubung ke madrasah.',
                ]);
            }

            if (!$this->attendanceValidationService->schoolContainsPoint($school, (float) $validated['latitude'], (float) $validated['longitude'])) {
                return response()->json([
                    'success' => false,
                    'allowed' => false,
                    'message' => 'Presensi tidak dapat dilakukan karena lokasi berada di luar area sekolah.',
                ], 422);
            }

            $consistency = $this->attendanceValidationService->validateLocationConsistency($validated['location_readings'] ?? null);
            if (!$consistency['valid']) {
                return response()->json([
                    'success' => false,
                    'allowed' => false,
                    'message' => $consistency['message'],
                ], 422);
            }

            return response()->json([
                'success' => true,
                'allowed' => true,
                'message' => 'Lokasi berada di dalam area sekolah.',
                'school_name' => $school->name,
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'allowed' => false,
                'message' => collect($exception->errors())->flatten()->first() ?: 'Validasi lokasi gagal.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (AuthorizationException $exception) {
            return response()->json([
                'success' => false,
                'allowed' => false,
                'message' => $exception->getMessage(),
            ], 403);
        }
    }

    public function enrollFace(Request $request): JsonResponse
    {
        $operator = Auth::user();

        try {
            $device = $this->resolveAuthorizedDevice($request, $operator);
            $validated = $request->validate([
                'teacher_id' => ['required', 'integer'],
                'selfie_data' => ['nullable', 'string', 'min:100'],
                'selfie_frames' => ['nullable', 'array', 'max:8'],
                'selfie_frames.*' => ['string', 'min:100'],
                'face_descriptor' => ['nullable', 'array'],
                'face_descriptor.*' => ['numeric'],
                'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
                'liveness_challenges' => ['nullable', 'array'],
                'device_info' => ['nullable', 'string'],
            ]);

            $teacher = $this->resolveTeacher($device, (int) $validated['teacher_id']);

            if ($this->kioskFaceEngineService->usesPython()) {
                $engineResult = $this->kioskFaceEngineService->enroll($teacher, $validated, [
                    'device_id' => $device->id,
                    'madrasah_id' => $device->madrasah_id,
                    'operator_user_id' => $operator->id,
                ]);

                if (!($engineResult['success'] ?? false)) {
                    return response()->json([
                        'success' => false,
                        'message' => $engineResult['message'] ?? 'Registrasi wajah melalui engine Python gagal.',
                        'status_code' => $engineResult['notes'] ?? 'face_engine_failed',
                    ], (int) ($engineResult['status'] ?? 422));
                }

                $faceEmbedding = $this->normalizeVector($engineResult['face_embedding'] ?? null);
                if ($faceEmbedding === []) {
                    throw ValidationException::withMessages([
                        'face_data' => 'Embedding wajah dari engine Python tidak valid. Silakan ulangi registrasi wajah.',
                    ]);
                }

                $teacher->face_data = json_encode([
                    'face_engine' => 'python',
                    'face_embedding' => $faceEmbedding,
                    'face_embedding_dimension' => count($faceEmbedding),
                    'liveness_score' => $engineResult['liveness_score'] ?? null,
                    'liveness_challenges' => $this->normalizeChallenges($engineResult['liveness_challenges'] ?? []),
                    'quality_score' => $engineResult['quality_score'] ?? null,
                    'captured_image' => $engineResult['captured_image'] ?? ($validated['selfie_data'] ?? null),
                    'enrolled_at' => now()->toIso8601String(),
                    'enrolled_by' => $operator->id,
                    'device_info' => is_string($validated['device_info'] ?? null)
                        ? Str::limit($validated['device_info'], 1000, '')
                        : null,
                    'engine_metadata' => is_array($engineResult['metadata'] ?? null) ? $engineResult['metadata'] : [],
                    'enrollment_channel' => 'school_kiosk_python',
                    'registered_device_id' => $device->id,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
            } else {
                $faceDescriptor = $this->normalizeDescriptor($validated['face_descriptor'] ?? null);
                if ($faceDescriptor === []) {
                    throw ValidationException::withMessages([
                        'face_descriptor' => 'Data wajah hasil scan tidak valid. Silakan ulangi scan wajah.',
                    ]);
                }

                $livenessScore = (float) ($validated['liveness_score'] ?? 0);
                if ($livenessScore < 0.78) {
                    throw ValidationException::withMessages([
                        'liveness_score' => 'Scan wajah belum cukup stabil untuk pendaftaran. Posisikan wajah dengan jelas lalu coba lagi.',
                    ]);
                }

                $teacher->face_data = json_encode([
                    'face_descriptor' => $faceDescriptor,
                    'liveness_score' => $livenessScore,
                    'liveness_challenges' => $this->normalizeChallenges($validated['liveness_challenges'] ?? []),
                    'enrolled_at' => now()->toIso8601String(),
                    'enrolled_by' => $operator->id,
                    'device_info' => is_string($validated['device_info'] ?? null)
                        ? Str::limit($validated['device_info'], 1000, '')
                        : null,
                    'enrollment_channel' => 'school_kiosk',
                    'registered_device_id' => $device->id,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
            }

            $teacher->face_id = (string) Str::uuid();
            $teacher->face_registered_at = now();
            $teacher->face_verification_required = true;
            $teacher->save();

            return response()->json([
                'success' => true,
                'message' => "Data wajah {$teacher->name} berhasil disimpan.",
                'teacher' => $this->teacherResource($teacher->fresh()),
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first() ?: 'Pendaftaran wajah gagal.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (AuthorizationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 403);
        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Data wajah gagal disimpan. Silakan ulangi scan wajah lalu coba lagi.',
            ], 500);
        }
    }

    public function autoSubmit(Request $request): JsonResponse
    {
        $operator = Auth::user();

        try {
            $device = $this->resolveAuthorizedDevice($request, $operator);
            $validated = $request->validate([
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
                'lokasi' => ['nullable', 'string'],
                'accuracy' => ['nullable', 'numeric'],
                'altitude' => ['nullable', 'numeric'],
                'speed' => ['nullable', 'numeric'],
                'device_info' => ['nullable', 'string'],
                'location_readings' => ['nullable', 'string'],
                'selfie_data' => ['required', 'string', 'min:100'],
                'selfie_frames' => ['nullable', 'array', 'max:8'],
                'selfie_frames.*' => ['string', 'min:100'],
                'face_descriptor' => ['nullable', 'array'],
                'face_descriptor.*' => ['numeric'],
                'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
                'liveness_challenges' => ['nullable', 'array'],
            ]);

            if (!$this->attendanceValidationService->schoolContainsPoint($device->madrasah, (float) $validated['latitude'], (float) $validated['longitude'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi tidak dapat dilakukan karena berada di luar area sekolah.',
                    'status_code' => 'outside_school_area',
                ], 422);
            }

            if ($this->kioskFaceEngineService->usesPython()) {
                $teacherMatch = $this->kioskFaceEngineService->identify(
                    $this->teachersQuery($device)->get(),
                    $validated,
                    [
                        'device_id' => $device->id,
                        'madrasah_id' => $device->madrasah_id,
                        'operator_user_id' => $operator->id,
                    ]
                );
            } else {
                $teacherMatch = $this->faceVerificationService->identifyBestMatchingUser(
                    $this->teachersQuery($device)->get(),
                    $validated['face_descriptor'] ?? null,
                    $validated['liveness_score'] ?? null,
                    $validated['liveness_challenges'] ?? [],
                    true,
                );
            }

            if (!($teacherMatch['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => $teacherMatch['message'] ?? 'Wajah tidak dikenali.',
                    'status_code' => $teacherMatch['notes'] ?? 'face_not_recognized',
                ], (int) ($teacherMatch['status'] ?? 422));
            }

            if ($this->kioskFaceEngineService->usesPython()) {
                $teacher = $this->resolveTeacher($device, (int) ($teacherMatch['user_id'] ?? 0));
            } else {
                /** @var User $teacher */
                $teacher = $teacherMatch['user'];
            }

            $attendancePayload = array_merge($validated, [
                'registered_device_id' => $device->id,
                'recorded_by_user_id' => $operator->id,
                'source_ip_address' => $request->ip(),
                'presensi_mode' => null,
            ]);

            if ($this->kioskFaceEngineService->usesPython()) {
                $attendancePayload['selfie_data'] = $teacherMatch['captured_image'] ?? $validated['selfie_data'];
                $attendancePayload['external_face_verification'] = [
                    'success' => true,
                    'user_id' => $teacher->id,
                    'message' => $teacherMatch['message'] ?? 'Identitas guru berhasil dikenali.',
                    'face_id_used' => $teacherMatch['face_id_used'] ?? $teacher->face_id,
                    'similarity' => $teacherMatch['similarity'] ?? null,
                    'face_distance' => $teacherMatch['face_distance'] ?? null,
                    'liveness_score' => $teacherMatch['liveness_score'] ?? null,
                    'challenges' => $teacherMatch['liveness_challenges'] ?? [],
                    'notes' => $teacherMatch['notes'] ?? 'face_identified_python',
                ];
            }

            $result = $this->schoolKioskAttendanceService->submit(
                $operator,
                $teacher,
                $device,
                $attendancePayload,
                $request->ip(),
            );

            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_submit',
                status: 'success',
                device: $device,
                operator: $operator,
                targetUser: $teacher,
                payloadSnapshot: [
                    'mode' => $result['mode'],
                    'teacher_id' => $teacher->id,
                    'teacher_name' => $teacher->name,
                ],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'status_code' => 'attendance_recorded',
                'mode' => $result['mode'],
                'teacher' => $this->teacherResource($teacher),
                'presensi' => [
                    'id' => $result['presensi']->id,
                    'tanggal' => optional($result['presensi']->tanggal)->format('Y-m-d'),
                    'waktu_masuk' => $result['presensi']->waktu_masuk?->format('H:i'),
                    'waktu_keluar' => $result['presensi']->waktu_keluar?->format('H:i'),
                ],
                'face_similarity' => $teacherMatch['similarity'] ?? null,
            ]);
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?: 'Presensi otomatis gagal.';
            $statusCode = str_contains(strtolower($message), 'sudah lengkap')
                ? 'attendance_completed'
                : (str_contains(strtolower($message), 'keluar')
                    ? 'attendance_checkout_pending'
                    : 'attendance_validation_failed');

            $device = $this->attendanceKioskAccessService->resolveDeviceByToken($request->cookie('nuist_kiosk_token'));
            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_submit',
                status: 'failed',
                device: $device,
                operator: $operator,
                payloadSnapshot: ['message' => $message],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'success' => false,
                'message' => $message,
                'status_code' => $statusCode,
                'errors' => $exception->errors(),
            ], 422);
        } catch (AuthorizationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'status_code' => 'unauthorized_device',
            ], 403);
        }
    }

    public function submit(Request $request)
    {
        $operator = Auth::user();
        $device = $this->attendanceKioskAccessService->resolveDeviceByToken($request->cookie('nuist_kiosk_token'));

        try {
            $device = $this->attendanceKioskAccessService->authorizeSchoolKioskAccess(
                $request,
                $operator,
                $device
            );

            $validated = $request->validate([
                'teacher_id' => ['required', 'integer'],
                'presensi_mode' => ['nullable', 'in:masuk,keluar'],
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
                'lokasi' => ['nullable', 'string'],
                'accuracy' => ['nullable', 'numeric'],
                'altitude' => ['nullable', 'numeric'],
                'speed' => ['nullable', 'numeric'],
                'device_info' => ['nullable', 'string'],
                'location_readings' => ['nullable', 'string'],
                'selfie_data' => ['required', 'string', 'min:100'],
                'face_descriptor' => ['nullable', 'array'],
                'face_descriptor.*' => ['numeric'],
                'liveness_score' => ['nullable', 'numeric', 'min:0', 'max:1'],
                'liveness_challenges' => ['nullable', 'array'],
            ]);

            $teacher = $this->resolveTeacher($device, (int) $validated['teacher_id']);

            $result = $this->schoolKioskAttendanceService->submit(
                $operator,
                $teacher,
                $device,
                array_merge($validated, [
                    'registered_device_id' => $device->id,
                    'recorded_by_user_id' => $operator->id,
                    'source_ip_address' => $request->ip(),
                ]),
                $request->ip(),
            );

            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_submit',
                status: 'success',
                device: $device,
                operator: $operator,
                targetUser: $teacher,
                payloadSnapshot: [
                    'mode' => $result['mode'],
                    'teacher_id' => $teacher->id,
                ],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'mode' => $result['mode'],
                'teacher_name' => $teacher->name,
                'presensi' => [
                    'id' => $result['presensi']->id,
                    'tanggal' => optional($result['presensi']->tanggal)->format('Y-m-d'),
                    'waktu_masuk' => $result['presensi']->waktu_masuk?->format('H:i'),
                    'waktu_keluar' => $result['presensi']->waktu_keluar?->format('H:i'),
                    'madrasah' => $result['presensi']->madrasah?->name,
                ],
            ]);
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?: 'Submit presensi gagal.';

            $this->attendanceKioskAccessService->logAccess(
                action: 'kiosk_submit',
                status: 'failed',
                device: $device,
                operator: $operator,
                payloadSnapshot: ['message' => $message],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $exception->errors(),
            ], 422);
        } catch (AuthorizationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 403);
        }
    }

    private function resolveAuthorizedDevice(Request $request, User $operator): RegisteredAttendanceDevice
    {
        $device = $this->attendanceKioskAccessService->resolveDeviceByToken($request->cookie('nuist_kiosk_token'));

        return $this->attendanceKioskAccessService->authorizeSchoolKioskAccess($request, $operator, $device);
    }

    private function teachersQuery(RegisteredAttendanceDevice $device): Builder
    {
        return User::query()
            ->where('role', 'tenaga_pendidik')
            ->where(function (Builder $query) use ($device) {
                $query->where('madrasah_id', $device->madrasah_id)
                    ->orWhere('madrasah_id_tambahan', $device->madrasah_id);
            });
    }

    private function resolveTeacher(RegisteredAttendanceDevice $device, int $teacherId): User
    {
        return $this->teachersQuery($device)->findOrFail($teacherId);
    }

    private function teacherResource(User $teacher): array
    {
        return [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'nip' => $teacher->nip,
            'nuptk' => $teacher->nuptk,
            'ketugasan' => $teacher->ketugasan,
            'face_registered_at' => optional($teacher->face_registered_at)?->toIso8601String(),
            'has_face_enrollment' => $teacher->hasFaceEnrollment(),
        ];
    }

    private function normalizeDescriptor(mixed $descriptor): array
    {
        $normalized = $this->normalizeVector($descriptor);

        return count($normalized) === 128 ? $normalized : [];
    }

    private function normalizeVector(mixed $vector): array
    {
        if (!is_array($vector)) {
            return [];
        }

        $normalized = [];
        foreach ($vector as $value) {
            if (!is_numeric($value)) {
                return [];
            }

            $normalized[] = (float) $value;
        }

        return $normalized;
    }

    private function normalizeChallenges(mixed $challenges): array
    {
        if (!is_array($challenges)) {
            return [];
        }

        return collect($challenges)
            ->filter(fn ($challenge) => is_array($challenge))
            ->map(function (array $challenge) {
                return [
                    'type' => (string) ($challenge['type'] ?? 'unknown'),
                    'passed' => (bool) ($challenge['passed'] ?? false),
                    'score' => is_numeric($challenge['score'] ?? null) ? round((float) $challenge['score'], 4) : null,
                    'detail' => isset($challenge['detail']) ? (string) $challenge['detail'] : null,
                    'timestamp' => $challenge['timestamp'] ?? now()->timestamp,
                ];
            })
            ->values()
            ->all();
    }
}
