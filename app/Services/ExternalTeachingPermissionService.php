<?php

namespace App\Services;

use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ExternalTeachingPermissionService
{
    public const TYPE = 'mengajar_sekolah_lain';
    public const KETERANGAN_TIDAK_PRESENSI = 'guru mengajar di sekolah lain/tidak presensi';

    public static function isEligibleUser(User $user): bool
    {
        return $user->role === 'tenaga_pendidik' && (bool) $user->pemenuhan_beban_kerja_lain;
    }

    public static function approvedRequestForDate(User $user, Carbon|string $date): ?Izin
    {
        if (!self::isEligibleUser($user)) {
            return null;
        }

        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);

        return Izin::query()
            ->where('user_id', $user->id)
            ->where('type', self::TYPE)
            ->where('status', 'approved')
            ->whereDate('tanggal', '<=', $date->toDateString())
            ->where(function ($query) use ($date) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date->toDateString());
            })
            ->orderByDesc('approved_at')
            ->get()
            ->first(fn (Izin $izin) => self::isNoPresenceDay($izin, $date));
    }

    public static function isNoPresenceDay(Izin $izin, Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        $dayOfWeek = (int) $date->dayOfWeek;
        $days = self::normalizeDays($izin->hari_tidak_presensi ?? []);

        return in_array($dayOfWeek, $days, true);
    }

    public static function hasApprovedNoPresenceDay(User $user, Carbon|string $date): bool
    {
        return self::approvedRequestForDate($user, $date) !== null;
    }

    public static function syncApprovedNoPresencePresensi(Izin $izin, ?Carbon $until = null): int
    {
        if ($izin->type !== self::TYPE || $izin->status !== 'approved') {
            return 0;
        }

        $izin->loadMissing('user.madrasah');

        if (!$izin->user || !self::isEligibleUser($izin->user)) {
            return 0;
        }

        $startDate = Carbon::parse($izin->tanggal)->startOfDay();
        $endDate = $izin->tanggal_selesai
            ? Carbon::parse($izin->tanggal_selesai)->startOfDay()
            : $startDate->copy();
        $effectiveEndDate = $until
            ? $endDate->copy()->min($until->copy()->startOfDay())
            : $endDate;

        if ($effectiveEndDate->lt($startDate)) {
            return 0;
        }

        $synced = 0;
        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            if (!self::isWorkingDayForUser($izin->user, $date) || !self::isNoPresenceDay($izin, $date)) {
                continue;
            }

            if (self::createOrUpdateNoPresenceRecord($izin->user, $date, $izin)) {
                $synced++;
            }
        }

        return $synced;
    }

    public static function createOrUpdateNoPresenceRecord(User $user, Carbon|string $date, ?Izin $izin = null): bool
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);
        $izin = $izin ?: self::approvedRequestForDate($user, $date);

        if (!$izin) {
            return false;
        }

        $existing = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $data = [
            'madrasah_id' => $user->madrasah_id,
            'status' => 'izin',
            'keterangan' => self::KETERANGAN_TIDAK_PRESENSI,
            'status_izin' => 'approved',
            'approved_by' => $izin->approved_by,
            'status_kepegawaian_id' => $user->status_kepegawaian_id,
            'surat_izin_path' => $izin->file_path,
        ];

        if (!$existing) {
            Presensi::create(array_merge($data, [
                'user_id' => $user->id,
                'tanggal' => $date->toDateString(),
            ]));

            return true;
        }

        $hasNoAttendanceTime = !$existing->waktu_masuk && !$existing->waktu_keluar;
        if ($existing->status === 'alpha' && $hasNoAttendanceTime) {
            $existing->update($data);

            return true;
        }

        return false;
    }

    public static function isWorkingDayForUser(User $user, Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        if ($date->isSunday() || \App\Models\Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) ($user->madrasah->hari_kbm ?? '5') === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }

    private static function normalizeDays(array|string|null $days): array
    {
        if (is_string($days)) {
            $decoded = json_decode($days, true);
            $days = is_array($decoded) ? $decoded : [];
        }

        return collect($days ?? [])
            ->map(fn ($day) => (int) $day)
            ->filter(fn ($day) => $day >= 0 && $day <= 6)
            ->unique()
            ->values()
            ->all();
    }
}
