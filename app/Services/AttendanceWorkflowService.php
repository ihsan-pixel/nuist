<?php

namespace App\Services;

use App\Models\Izin;
use App\Models\Madrasah;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Holiday;

class AttendanceWorkflowService
{
    public function __construct(
        private PicketScheduleApprovalService $picketScheduleApprovalService,
    ) {
    }

    public function blockedAttendanceDayReason(User $user, Carbon|string $date): ?string
    {
        $date = $this->normalizeDate($date);
        $isHoliday = Holiday::isHoliday($date->toDateString());
        $isSunday = $date->dayOfWeek === Carbon::SUNDAY;
        $approvedPicketSubmission = $this->picketScheduleApprovalService->approvedSubmissionForDate($user, $date);

        if ((!$isHoliday && !$isSunday) || $approvedPicketSubmission) {
            return null;
        }

        if ($isHoliday) {
            $holiday = Holiday::getHoliday($date->toDateString());

            return 'hari libur (' . $holiday->name . ')';
        }

        return 'hari Minggu';
    }

    public function findPendingLatePermit(User $user, Carbon|string $date): ?Presensi
    {
        $date = $this->normalizeDate($date);

        return Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->where('keterangan', 'like', '%terlambat%')
            ->first();
    }

    public function findApprovedBlockingIzin(User $user, Carbon|string $date): ?Izin
    {
        return ApprovedIzinSyncService::approvedRequestForDate($user, $date);
    }

    public function normalizeRequestedMode(?string $requestedMode, ?Presensi $existingPresensi): string
    {
        if (in_array($requestedMode, ['masuk', 'keluar'], true)) {
            return $requestedMode;
        }

        if ($existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar) {
            return 'keluar';
        }

        return 'masuk';
    }

    public function determineMasukKeterangan(User $user, Carbon|string $dateTime): string
    {
        $dateTime = $this->normalizeDateTime($dateTime);
        $approvedLatePermit = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $dateTime->toDateString())
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->where('keterangan', 'like', '%terlambat%')
            ->first();

        if ($approvedLatePermit) {
            return 'terlambat sudah izin';
        }

        if ($dateTime->format('H:i:s') > '07:00:00') {
            $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
            $terlambatMenit = abs(round($dateTime->floatDiffInMinutes($batas)));

            return "Terlambat {$terlambatMenit} menit";
        }

        return 'tidak terlambat';
    }

    public function isEarlyCheckout(User $user, ?Madrasah $school, Carbon|string $dateTime): bool
    {
        if (!$school || $user->ketugasan === 'penjaga sekolah' || $user->pemenuhan_beban_kerja_lain) {
            return false;
        }

        $dateTime = $this->normalizeDateTime($dateTime);
        $pulangStart = $this->resolvePulangStart($school, $dateTime);

        return $dateTime->format('H:i:s') < $pulangStart;
    }

    public function appendCheckoutNote(?string $keterangan, bool $isEarlyCheckout): string
    {
        $keterangan = trim((string) $keterangan);

        if (!$isEarlyCheckout) {
            return $keterangan;
        }

        $suffix = 'pulang awal';

        if ($keterangan === '') {
            return $suffix;
        }

        if (str_contains(strtolower($keterangan), $suffix)) {
            return $keterangan;
        }

        return $keterangan . ' / ' . $suffix;
    }

    public function resolveMasukStart(?Madrasah $school, string $fallback = '00:01:00'): string
    {
        return $this->normalizeTime($school?->presensi_masuk_start, $fallback);
    }

    public function resolveEndOfDayCutoff(?Madrasah $school, string $fallback = '23:59:59'): string
    {
        return $this->normalizeTime($school?->presensi_pulang_end, $fallback);
    }

    public function resolvePulangStart(?Madrasah $school, Carbon|string $date, string $fallback = '15:00:00'): string
    {
        if (!$school) {
            return $fallback;
        }

        $date = $this->normalizeDate($date);
        $dayOfWeek = $date->dayOfWeek;

        if ($dayOfWeek === Carbon::FRIDAY && $school->presensi_pulang_jumat) {
            return $this->normalizeTime($school->presensi_pulang_jumat, $fallback);
        }

        if ($dayOfWeek === Carbon::SATURDAY && $school->presensi_pulang_sabtu) {
            return $this->normalizeTime($school->presensi_pulang_sabtu, $fallback);
        }

        if ($school->presensi_pulang_start) {
            return $this->normalizeTime($school->presensi_pulang_start, $fallback);
        }

        $hariKbm = (string) ($school->hari_kbm ?? '');
        if ($hariKbm === '5') {
            return $dayOfWeek === Carbon::FRIDAY ? '11:15:00' : '13:35:00';
        }

        if ($hariKbm === '6') {
            return $dayOfWeek === Carbon::FRIDAY
                ? '13:00:00'
                : ($dayOfWeek === Carbon::SATURDAY ? '12:00:00' : '14:00:00');
        }

        return $fallback;
    }

    public function normalizeTime(?string $value, string $fallback = '00:00:00'): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return $fallback;
        }

        return strlen($value) === 5 ? $value . ':00' : $value;
    }

    private function normalizeDate(Carbon|string $date): Carbon
    {
        return $date instanceof Carbon
            ? $date->copy()->timezone('Asia/Jakarta')
            : Carbon::parse($date, 'Asia/Jakarta');
    }

    private function normalizeDateTime(Carbon|string $dateTime): Carbon
    {
        return $this->normalizeDate($dateTime);
    }
}
