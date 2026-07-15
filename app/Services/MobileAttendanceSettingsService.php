<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\User;

class MobileAttendanceSettingsService
{
    public const MODE_SELFIE = 'selfie';
    public const MODE_FACE_SCAN = 'face_scan';

    public function currentMode(): string
    {
        $mode = AppSetting::getSettings()->mobile_attendance_verification_mode ?? self::MODE_SELFIE;

        return in_array($mode, $this->availableModes(), true)
            ? $mode
            : self::MODE_SELFIE;
    }

    public function availableModes(): array
    {
        return [
            self::MODE_SELFIE,
            self::MODE_FACE_SCAN,
        ];
    }

    public function modeOptions(): array
    {
        return [
            self::MODE_SELFIE => [
                'label' => 'Selfie',
                'description' => 'Presensi mobile menggunakan foto selfie dari kamera depan.',
            ],
            self::MODE_FACE_SCAN => [
                'label' => 'Scan wajah',
                'description' => 'Presensi mobile mewajibkan verifikasi wajah dengan liveness check.',
            ],
        ];
    }

    public function modeLabel(?string $mode = null): string
    {
        $mode = $mode ?: $this->currentMode();

        return $this->modeOptions()[$mode]['label'] ?? 'Selfie';
    }

    public function modeDescription(?string $mode = null): string
    {
        $mode = $mode ?: $this->currentMode();

        return $this->modeOptions()[$mode]['description'] ?? $this->modeOptions()[self::MODE_SELFIE]['description'];
    }

    public function updateMode(string $mode): void
    {
        $normalizedMode = in_array($mode, $this->availableModes(), true)
            ? $mode
            : self::MODE_SELFIE;

        AppSetting::getSettings()->update([
            'mobile_attendance_verification_mode' => $normalizedMode,
        ]);
    }

    public function runtimeStateForUser(User $user): array
    {
        $mode = $this->currentMode();

        return [
            'mode' => $mode,
            'label' => $this->modeLabel($mode),
            'description' => $this->modeDescription($mode),
            'requires_face_scan' => $mode === self::MODE_FACE_SCAN,
            'face_enrollment_required' => $mode === self::MODE_FACE_SCAN,
            'face_enrolled' => $user->hasFaceEnrollment(),
        ];
    }
}
