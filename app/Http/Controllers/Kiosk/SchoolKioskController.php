<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use App\Services\AttendanceKioskAccessService;
use App\Services\MobileAttendanceSettingsService;
use App\Services\SchoolKioskAttendanceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SchoolKioskController extends Controller
{
    public function __construct(
        private AttendanceKioskAccessService $attendanceKioskAccessService,
        private MobileAttendanceSettingsService $mobileAttendanceSettingsService,
        private SchoolKioskAttendanceService $schoolKioskAttendanceService,
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
        $fingerprint = $request->cookie('nuist_kiosk_fingerprint');
        $accessGranted = false;
        $accessMessage = 'Komputer presensi belum tervalidasi.';
        $teacherCount = 0;
        $teachers = collect();

        try {
            $device = $this->attendanceKioskAccessService->authorizeSchoolKioskAccess(
                $request,
                $operator,
                $device,
                $fingerprint
            );

            $accessGranted = true;
            $accessMessage = 'Komputer presensi sekolah tervalidasi dan siap dipakai.';
            $teacherCount = User::query()
                ->where('role', 'tenaga_pendidik')
                ->where(function ($query) use ($device) {
                    $query->where('madrasah_id', $device->madrasah_id)
                        ->orWhere('madrasah_id_tambahan', $device->madrasah_id);
                })
                ->count();
            $teachers = User::query()
                ->where('role', 'tenaga_pendidik')
                ->where(function ($query) use ($device) {
                    $query->where('madrasah_id', $device->madrasah_id)
                        ->orWhere('madrasah_id_tambahan', $device->madrasah_id);
                })
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
            'teacherCount' => $teacherCount,
            'teachers' => $teachers,
            'verificationMode' => $this->mobileAttendanceSettingsService->currentMode(),
            'verificationLabel' => $this->mobileAttendanceSettingsService->modeLabel(),
        ]);
    }

    public function submit(Request $request)
    {
        $operator = Auth::user();
        $device = $this->attendanceKioskAccessService->resolveDeviceByToken($request->cookie('nuist_kiosk_token'));

        try {
            $device = $this->attendanceKioskAccessService->authorizeSchoolKioskAccess(
                $request,
                $operator,
                $device,
                $request->cookie('nuist_kiosk_fingerprint')
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

            $teacher = User::query()
                ->where('role', 'tenaga_pendidik')
                ->where(function ($query) use ($device) {
                    $query->where('madrasah_id', $device->madrasah_id)
                        ->orWhere('madrasah_id_tambahan', $device->madrasah_id);
                })
                ->findOrFail($validated['teacher_id']);

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
}
