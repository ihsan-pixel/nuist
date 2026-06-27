<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicketScheduleSubmission extends Model
{
    use HasFactory;

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'picket_schedule_period_id',
        'user_id',
        'selected_dates',
        'approval_status',
        'submitted_at',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'selected_dates' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function approvalOptions(): array
    {
        return [
            self::APPROVAL_PENDING => 'Menunggu Persetujuan Kepala Sekolah',
            self::APPROVAL_APPROVED => 'Disetujui Kepala Sekolah',
            self::APPROVAL_REJECTED => 'Ditolak Kepala Sekolah',
        ];
    }

    public function period()
    {
        return $this->belongsTo(PicketSchedulePeriod::class, 'picket_schedule_period_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        return self::approvalOptions()[$this->approval_status] ?? 'Menunggu Persetujuan Kepala Sekolah';
    }

    public function getSelectedDatesCountAttribute(): int
    {
        return count($this->selected_dates ?? []);
    }

    public function getSelectedDateLabelsAttribute(): array
    {
        return collect($this->selected_dates ?? [])
            ->filter()
            ->sort()
            ->map(function (string $date) {
                return Carbon::parse($date, 'Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');
            })
            ->values()
            ->all();
    }
}
