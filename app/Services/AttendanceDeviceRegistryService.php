<?php

namespace App\Services;

use App\Models\Madrasah;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AttendanceDeviceRegistryService
{
    public function issuePlainToken(): string
    {
        return Str::random(64);
    }

    public function hashToken(string $plainToken): string
    {
        return hash('sha256', trim($plainToken));
    }

    public function hashFingerprint(?string $fingerprint): ?string
    {
        $normalized = $this->normalizeFingerprint($fingerprint);

        return $normalized === null ? null : hash('sha256', $normalized);
    }

    public function registerSchoolKiosk(
        Madrasah $madrasah,
        User $registeredBy,
        string $name,
        ?string $plainToken,
        ?string $browserFingerprint,
        array $allowedIpAddresses = [],
        array $meta = [],
    ): RegisteredAttendanceDevice {
        $token = trim((string) $plainToken);

        if ($token === '') {
            $token = $this->issuePlainToken();
        }

        return RegisteredAttendanceDevice::create([
            'madrasah_id' => $madrasah->id,
            'name' => trim($name),
            'device_type' => 'school_kiosk',
            'device_token_hash' => $this->hashToken($token),
            'browser_fingerprint_hash' => $this->hashFingerprint($browserFingerprint),
            'allowed_ip_addresses' => $this->normalizeIpAddresses($allowedIpAddresses),
            'is_active' => true,
            'last_seen_at' => $meta['last_seen_at'] ?? null,
            'last_ip_address' => $meta['last_ip_address'] ?? null,
            'last_user_agent' => $meta['last_user_agent'] ?? null,
            'registered_by' => $registeredBy->id,
        ]);
    }

    public function updateAllowedIpAddresses(RegisteredAttendanceDevice $device, array $allowedIpAddresses): RegisteredAttendanceDevice
    {
        $device->allowed_ip_addresses = $this->normalizeIpAddresses($allowedIpAddresses);
        $device->save();

        return $device;
    }

    public function touchSeen(RegisteredAttendanceDevice $device, ?string $ipAddress, ?string $userAgent): void
    {
        $device->forceFill([
            'last_seen_at' => Carbon::now(),
            'last_ip_address' => $ipAddress,
            'last_user_agent' => $userAgent,
        ])->save();
    }

    public function normalizeIpAddresses(array $allowedIpAddresses): array
    {
        return collect($allowedIpAddresses)
            ->map(fn ($ip) => trim((string) $ip))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function normalizeFingerprint(?string $fingerprint): ?string
    {
        $normalized = trim((string) $fingerprint);

        return $normalized === '' ? null : $normalized;
    }
}
