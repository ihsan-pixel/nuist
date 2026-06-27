<?php

namespace App\Services;

use App\Models\PicketScheduleSubmission;
use App\Models\User;
use Carbon\Carbon;

class PicketScheduleApprovalService
{
    public function approvedSubmissionForDate(User $user, Carbon|string $date): ?PicketScheduleSubmission
    {
        $date = $date instanceof Carbon ? $date->copy()->startOfDay() : Carbon::parse($date, 'Asia/Jakarta')->startOfDay();

        return PicketScheduleSubmission::query()
            ->with('period')
            ->where('user_id', $user->id)
            ->where('approval_status', PicketScheduleSubmission::APPROVAL_APPROVED)
            ->whereJsonContains('selected_dates', $date->toDateString())
            ->whereHas('period', function ($query) use ($user, $date) {
                $query->where('is_active', true)
                    ->where('school_id', $user->madrasah_id)
                    ->whereDate('start_date', '<=', $date->toDateString())
                    ->whereDate('end_date', '>=', $date->toDateString());
            })
            ->latest('approved_at')
            ->latest('id')
            ->first();
    }
}
