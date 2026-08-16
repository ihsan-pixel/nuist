<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\User;

class MobileAttendanceSettingsService
{
    public const MODE_FACE_SCAN = 'face_scan';

    public function currentMode(): string
    {
        return self::MODE_FACE_SCAN;
    }

    public function availableModes(): array
    {
        return [
            self::MODE_FACE_SCAN,
        ];
    }

    public function modeOptions(): array
    {
        return [
            self::MODE_FACE_SCAN => [
                'label' => 'Scan wajah',
                'description' => 'Presensi mobile mewajibkan verifikasi wajah dengan liveness check.',
            ],
        ];
    }

    public function modeLabel(?string $mode = null): string
    {
        $mode = $mode ?: $this->currentMode();

        return $this->modeOptions()[$mode]['label'] ?? 'Scan wajah';
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
            : self::MODE_FACE_SCAN;

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
