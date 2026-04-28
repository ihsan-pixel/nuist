<?php

namespace App\Services;

use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ApprovedIzinSyncService
{
    public static function approvedRequestForDate(User $user, Carbon|string $date): ?Izin
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
        $izin = self::approvedRequestForDate($user, $date);

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

    private static function syncApprovedIzinPresensiForDate(Izin $izin, Carbon $date): bool
    {
        $existingPresensi = Presensi::query()
            ->where('user_id', $izin->user_id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $izinPresensiData = [
            'madrasah_id' => $izin->user->madrasah_id,
            'waktu_masuk' => $izin->waktu_masuk,
            'waktu_keluar' => $izin->waktu_keluar,
            'status' => 'izin',
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
            if (!$existingPresensi->waktu_keluar && $izin->waktu_keluar) {
                $existingPresensi->update([
                    'waktu_keluar' => $izin->waktu_keluar,
                    'status_izin' => 'approved',
                    'approved_by' => $izin->approved_by,
                    'surat_izin_path' => $izin->file_path,
                ]);

                return true;
            }

            return false;
        }

        $hasNoAttendanceTime = !$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        if ($existingPresensi->status === 'izin' || ($existingPresensi->status === 'alpha' && $hasNoAttendanceTime) || $hasNoAttendanceTime) {
            $existingPresensi->update($izinPresensiData);

            return true;
        }

        return false;
    }
}
