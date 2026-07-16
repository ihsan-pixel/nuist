<?php

namespace App\Services;

use App\Models\AttendanceKioskLog;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class AttendanceKioskAccessService
{
    public function __construct(
        private AttendanceDeviceRegistryService $attendanceDeviceRegistryService,
    ) {
    }

    public function resolveDeviceByToken(?string $plainToken): ?RegisteredAttendanceDevice
    {
        $token = trim((string) $plainToken);

        if ($token === '') {
            return null;
        }

        return RegisteredAttendanceDevice::query()
            ->where('device_token_hash', $this->attendanceDeviceRegistryService->hashToken($token))
            ->first();
    }

    public function authorizeSchoolKioskAccess(
        Request $request,
        User $operator,
        ?RegisteredAttendanceDevice $device,
    ): RegisteredAttendanceDevice {
        if (!$device || !$device->is_active) {
            throw new AuthorizationException('Komputer presensi belum terdaftar atau sudah dinonaktifkan.');
        }

        if ((int) $operator->madrasah_id !== (int) $device->madrasah_id) {
            throw new AuthorizationException('Operator tidak memiliki akses ke komputer presensi sekolah ini.');
        }

        if (!$this->matchesAllowedIpAddress($device, $request->ip())) {
            throw new AuthorizationException('IP komputer ini belum diizinkan untuk presensi sekolah.');
        }

        $this->attendanceDeviceRegistryService->touchSeen($device, $request->ip(), $request->userAgent());

        return $device;
    }

    public function matchesAllowedIpAddress(RegisteredAttendanceDevice $device, ?string $requestIp): bool
    {
        $allowedIps = collect($device->allowed_ip_addresses ?? [])
            ->filter()
            ->values();

        if ($allowedIps->isEmpty()) {
            return false;
        }

        return $allowedIps->contains((string) $requestIp);
    }
    public function logAccess(
        string $action,
        string $status,
        ?RegisteredAttendanceDevice $device = null,
        ?User $operator = null,
        ?User $targetUser = null,
        array $payloadSnapshot = [],
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): AttendanceKioskLog {
        return AttendanceKioskLog::create([
            'registered_device_id' => $device?->id,
            'madrasah_id' => $device?->madrasah_id ?? $operator?->madrasah_id,
            'operator_user_id' => $operator?->id,
            'target_user_id' => $targetUser?->id,
            'action' => $action,
            'status' => $status,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'payload_snapshot' => $payloadSnapshot === [] ? null : $payloadSnapshot,
        ]);
    }
}
