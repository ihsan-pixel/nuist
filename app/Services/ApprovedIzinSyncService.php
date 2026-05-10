<?php

namespace App\Services;

use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ApprovedIzinSyncService
{
    private const AUTO_PRESENT_TYPES = ['tugas_luar'];

    public static function approvedRequestForDate(User $user, Carbon|string $date): ?Izin
    {
        $izin = self::findApprovedRequestForDate($user, $date);

        return $izin && !self::isAutoPresentType($izin->type)
            ? $izin
            : null;
    }

    public static function approvedTeachingJournalRequestForDate(User $user, Carbon|string $date): ?Izin
    {
        return self::findApprovedRequestForDate($user, $date);
    }

    public static function approvedPresenceSyncRequestForDate(User $user, Carbon|string $date): ?Izin
    {
        return self::findApprovedRequestForDate($user, $date);
    }

    public static function isAutoPresentType(?string $type): bool
    {
        return in_array((string) $type, self::AUTO_PRESENT_TYPES, true);
    }

    private static function findApprovedRequestForDate(User $user, Carbon|string $date): ?Izin
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        return Izin::query()
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('type', '!=', 'terlambat')
            ->where('type', '!=', ExternalTeachingPermissionService::TYPE)
            ->where(function ($query) use ($date) {
                $query->where(function ($singleDayQuery) use ($date) {
                    $singleDayQuery->whereNull('tanggal_selesai')
                        ->whereDate('tanggal', $date->toDateString());
                })->orWhere(function ($rangeQuery) use ($date) {
                    $rangeQuery->whereNotNull('tanggal_selesai')
                        ->whereDate('tanggal', '<=', $date->toDateString())
                        ->whereDate('tanggal_selesai', '>=', $date->toDateString());
                });
            })
            ->orderByDesc('approved_at')
            ->first();
    }

    public static function syncApprovedIzinPresensi(Izin $izin, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null): int
    {
        if ($izin->status !== 'approved' || $izin->type === 'terlambat' || $izin->type === ExternalTeachingPermissionService::TYPE) {
            return 0;
        }

        $izin->loadMissing('user');

        if (!$izin->user) {
            return 0;
        }

        $startDate = Carbon::parse($izin->tanggal)->startOfDay();
        $endDate = $izin->type === 'cuti' && $izin->tanggal_selesai
            ? Carbon::parse($izin->tanggal_selesai)->startOfDay()
            : $startDate->copy();

        if ($rangeStart) {
            $startDate = $startDate->max($rangeStart->copy()->startOfDay());
        }

        if ($rangeEnd) {
            $endDate = $endDate->min($rangeEnd->copy()->startOfDay());
        }

        if ($endDate->lt($startDate)) {
            return 0;
        }

        $synced = 0;
        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            if (self::syncApprovedIzinPresensiForDate($izin, $date)) {
                $synced++;
            }
        }

        return $synced;
    }

    public static function syncApprovedIzinPresensiForUserDate(User $user, Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');
        $izin = self::approvedPresenceSyncRequestForDate($user, $date);

        if (!$izin) {
            return false;
        }

        return self::syncApprovedIzinPresensiForDate($izin, $date);
    }

    public static function syncApprovedIzinPresensiInRange(User $user, Carbon|string $startDate, Carbon|string $endDate): int
    {
        $startDate = $startDate instanceof Carbon ? $startDate->copy() : Carbon::parse($startDate, 'Asia/Jakarta');
        $endDate = $endDate instanceof Carbon ? $endDate->copy() : Carbon::parse($endDate, 'Asia/Jakarta');

        if ($endDate->lt($startDate)) {
            return 0;
        }

        $approvedIzins = Izin::query()
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('type', '!=', 'terlambat')
            ->where('type', '!=', ExternalTeachingPermissionService::TYPE)
            ->whereDate('tanggal', '<=', $endDate->toDateString())
            ->where(function ($query) use ($startDate) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $startDate->toDateString());
            })
            ->get();

        $synced = 0;
        foreach ($approvedIzins as $izin) {
            $synced += self::syncApprovedIzinPresensi($izin, $startDate, $endDate);
        }

        return $synced;
    }

    public static function approvedTeachingJournalKeys(
        Collection|array $userIds,
        Carbon|string $startDate,
        Carbon|string $endDate
    ): Collection {
        $userIds = collect($userIds)->filter()->unique()->values();
        $startDate = $startDate instanceof Carbon ? $startDate->copy() : Carbon::parse($startDate, 'Asia/Jakarta');
        $endDate = $endDate instanceof Carbon ? $endDate->copy() : Carbon::parse($endDate, 'Asia/Jakarta');

        if ($userIds->isEmpty() || $endDate->lt($startDate)) {
            return collect();
        }

        $approvedIzins = Izin::query()
            ->whereIn('user_id', $userIds)
            ->where('status', 'approved')
            ->where('type', '!=', 'terlambat')
            ->where('type', '!=', ExternalTeachingPermissionService::TYPE)
            ->whereDate('tanggal', '<=', $endDate->toDateString())
            ->where(function ($query) use ($startDate) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $startDate->toDateString());
            })
            ->get(['user_id', 'tanggal', 'tanggal_selesai', 'type']);

        $keys = collect();

        foreach ($approvedIzins as $izin) {
            $periodStart = Carbon::parse($izin->tanggal)->startOfDay()->max($startDate->copy()->startOfDay());
            $periodEnd = $izin->tanggal_selesai
                ? Carbon::parse($izin->tanggal_selesai)->startOfDay()->min($endDate->copy()->startOfDay())
                : $periodStart->copy();

            if ($periodEnd->lt($periodStart)) {
                continue;
            }

            foreach (CarbonPeriod::create($periodStart, $periodEnd) as $date) {
                $keys->put($izin->user_id . '|' . $date->toDateString(), true);
            }
        }

        return $keys;
    }

    private static function syncApprovedIzinPresensiForDate(Izin $izin, Carbon $date): bool
    {
        $existingPresensi = Presensi::query()
            ->where('user_id', $izin->user_id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $status = self::isAutoPresentType($izin->type) ? 'hadir' : 'izin';
        $izinPresensiData = [
            'madrasah_id' => $izin->user->madrasah_id,
            'waktu_masuk' => $izin->waktu_masuk,
            'waktu_keluar' => $izin->waktu_keluar,
            'status' => $status,
            'keterangan' => $izin->alasan ?: $izin->deskripsi_tugas,
            'status_izin' => 'approved',
            'status_kepegawaian_id' => $izin->user->status_kepegawaian_id,
            'approved_by' => $izin->approved_by,
            'surat_izin_path' => $izin->file_path,
        ];

        if (!$existingPresensi) {
            Presensi::create(array_merge($izinPresensiData, [
                'user_id' => $izin->user_id,
                'tanggal' => $date->toDateString(),
            ]));

            return true;
        }

        if ($existingPresensi->status === 'hadir') {
            $updates = [
                'status_izin' => 'approved',
                'approved_by' => $izin->approved_by,
                'surat_izin_path' => $izin->file_path,
            ];

            if (self::isAutoPresentType($izin->type)) {
                $updates['keterangan'] = $izin->alasan ?: $izin->deskripsi_tugas;
                $updates['waktu_masuk'] = $existingPresensi->waktu_masuk ?: $izin->waktu_masuk;
                $updates['waktu_keluar'] = $existingPresensi->waktu_keluar ?: $izin->waktu_keluar;
            } elseif (!$existingPresensi->waktu_keluar && $izin->waktu_keluar) {
                $updates['waktu_keluar'] = $izin->waktu_keluar;
            }

            if (count($updates) > 3 || self::isAutoPresentType($izin->type)) {
                $existingPresensi->update($updates);

                return true;
            }

            return false;
        }

        $hasNoAttendanceTime = !$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        if (
            $existingPresensi->status === 'izin'
            || ($existingPresensi->status === 'alpha' && $hasNoAttendanceTime)
            || $hasNoAttendanceTime
        ) {
            $existingPresensi->update($izinPresensiData);

            return true;
        }

        return false;
    }
}
