<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\PicketScheduleSubmission;
use App\Models\User;
use Carbon\Carbon;

class AttendanceObligationService
{
    public const STATUS_OFF = 'off';
    public const STATUS_REQUIRED = 'required';
    public const STATUS_REQUIRED_PICKET = 'required_picket';
    public const STATUS_NOT_REQUIRED_PICKET_PERIOD = 'not_required_picket_period';

    public const NOTE_NOT_REQUIRED_PICKET_PERIOD = 'Di luar jadwal piket (tidak bertugas)';

    private array $statusCache = [];
    private array $approvedPeriodCache = [];

    public function statusForDate(User $user, Carbon|string $date): string
    {
        $date = $this->normalizeDate($date);
        $cacheKey = $user->id . '|' . $date->toDateString();

        if (isset($this->statusCache[$cacheKey])) {
            return $this->statusCache[$cacheKey];
        }

        $approvedSubmission = $this->approvedSubmissionForPeriod($user, $date);
        if ($approvedSubmission) {
            $selectedDates = collect($approvedSubmission->selected_dates ?? []);
            if ($selectedDates->contains($date->toDateString())) {
                return $this->statusCache[$cacheKey] = self::STATUS_REQUIRED_PICKET;
            }
        }

        if (!$this->isBaseWorkingDay($user, $date)) {
            return $this->statusCache[$cacheKey] = self::STATUS_OFF;
        }

        if ($approvedSubmission) {
            return $this->statusCache[$cacheKey] = self::STATUS_NOT_REQUIRED_PICKET_PERIOD;
        }

        return $this->statusCache[$cacheKey] = self::STATUS_REQUIRED;
    }

    public function hasAttendanceObligation(User $user, Carbon|string $date): bool
    {
        return in_array($this->statusForDate($user, $date), [
            self::STATUS_REQUIRED,
            self::STATUS_REQUIRED_PICKET,
        ], true);
    }

    public function isExcludedByApprovedPicketPeriod(User $user, Carbon|string $date): bool
    {
        return $this->statusForDate($user, $date) === self::STATUS_NOT_REQUIRED_PICKET_PERIOD;
    }

    public function isBaseWorkingDay(User $user, Carbon|string $date): bool
    {
        $date = $this->normalizeDate($date);

        if ($date->isSunday() || Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) ($user->madrasah?->hari_kbm ?? '5') === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }

    private function approvedSubmissionForPeriod(User $user, Carbon|string $date): ?PicketScheduleSubmission
    {
        $date = $this->normalizeDate($date);
        $cacheKey = $user->id . '|' . $date->toDateString();

        if (array_key_exists($cacheKey, $this->approvedPeriodCache)) {
            return $this->approvedPeriodCache[$cacheKey];
        }

        if ($user->role !== 'tenaga_pendidik' || !$user->madrasah_id) {
            return $this->approvedPeriodCache[$cacheKey] = null;
        }

        return $this->approvedPeriodCache[$cacheKey] = PicketScheduleSubmission::query()
            ->with('period')
            ->where('user_id', $user->id)
            ->where('approval_status', PicketScheduleSubmission::APPROVAL_APPROVED)
            ->whereHas('period', function ($query) use ($user, $date) {
                $query->where('school_id', $user->madrasah_id)
                    ->whereDate('start_date', '<=', $date->toDateString())
                    ->whereDate('end_date', '>=', $date->toDateString());
            })
            ->latest('approved_at')
            ->latest('id')
            ->first();
    }

    private function normalizeDate(Carbon|string $date): Carbon
    {
        return $date instanceof Carbon
            ? $date->copy()->startOfDay()
            : Carbon::parse($date, 'Asia/Jakarta')->startOfDay();
    }
}
