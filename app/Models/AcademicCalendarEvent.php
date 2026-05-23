<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendarEvent extends Model
{
    use HasFactory;

    public const TYPE_SCHOOL_ACTIVITY = 'school_activity';
    public const TYPE_ACADEMIC_HOLIDAY = 'academic_holiday';
    public const TYPE_CUSTOM = 'custom';
    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'school_id',
        'name',
        'event_type',
        'custom_type_label',
        'start_date',
        'end_date',
        'is_all_day',
        'start_time',
        'end_time',
        'description',
        'is_active',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_all_day' => 'boolean',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public static function typeOptions(): array
    {
        return [
            self::TYPE_SCHOOL_ACTIVITY => 'Kegiatan Sekolah',
            self::TYPE_ACADEMIC_HOLIDAY => 'Libur Akademik',
            self::TYPE_CUSTOM => 'Jenis Lainnya',
        ];
    }

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function teachingAttendances()
    {
        return $this->hasMany(TeachingAttendance::class, 'academic_calendar_event_id');
    }

    public function getResolvedTypeLabelAttribute(): string
    {
        if ($this->event_type === self::TYPE_CUSTOM) {
            return trim((string) $this->custom_type_label) !== ''
                ? trim((string) $this->custom_type_label)
                : self::typeOptions()[self::TYPE_CUSTOM];
        }

        return self::typeOptions()[$this->event_type] ?? 'Kegiatan Akademik';
    }

    public static function approvalOptions(): array
    {
        return [
            self::APPROVAL_PENDING => 'Menunggu Persetujuan Kepala Sekolah',
            self::APPROVAL_APPROVED => 'Disetujui Kepala Sekolah',
            self::APPROVAL_REJECTED => 'Ditolak Kepala Sekolah',
        ];
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        return self::approvalOptions()[$this->approval_status] ?? 'Menunggu Persetujuan Kepala Sekolah';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function getDateRangeLabelAttribute(): string
    {
        $start = Carbon::parse($this->start_date)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $end = Carbon::parse($this->end_date)->locale('id')->isoFormat('dddd, D MMMM YYYY');

        return $start === $end ? $start : $start . ' - ' . $end;
    }

    public function getTimeRangeLabelAttribute(): string
    {
        if ($this->is_all_day) {
            return 'Sehari penuh';
        }

        return trim((string) $this->start_time) . ' - ' . trim((string) $this->end_time);
    }

    public function occursOnDate(Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date->copy()->startOfDay() : Carbon::parse($date, 'Asia/Jakarta')->startOfDay();

        return $date->betweenIncluded(
            Carbon::parse($this->start_date, 'Asia/Jakarta')->startOfDay(),
            Carbon::parse($this->end_date, 'Asia/Jakarta')->startOfDay()
        );
    }

    public function overlapsScheduleOnDate(TeachingSchedule $schedule, Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        if (!$this->is_active || !$this->occursOnDate($date)) {
            return false;
        }

        if (strcasecmp((string) $schedule->day, (string) $date->locale('id')->dayName) !== 0) {
            return false;
        }

        if ($this->is_all_day) {
            return true;
        }

        return $this->timeRangesOverlap(
            substr((string) $schedule->start_time, 0, 8),
            substr((string) $schedule->end_time, 0, 8),
            substr((string) $this->start_time, 0, 8),
            substr((string) $this->end_time, 0, 8),
        );
    }

    public function affectsScheduleOnDate(TeachingSchedule $schedule, Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        if (!$this->is_active || !$this->occursOnDate($date)) {
            return false;
        }

        if (strcasecmp((string) $schedule->day, (string) $date->locale('id')->dayName) !== 0) {
            return false;
        }

        return true;
    }

    public function conflictsWithWindow(
        Carbon $startDate,
        Carbon $endDate,
        bool $isAllDay,
        ?string $startTime,
        ?string $endTime
    ): bool {
        if (
            Carbon::parse($this->start_date, 'Asia/Jakarta')->startOfDay()->gt($endDate->copy()->startOfDay()) ||
            Carbon::parse($this->end_date, 'Asia/Jakarta')->startOfDay()->lt($startDate->copy()->startOfDay())
        ) {
            return false;
        }

        if ($this->is_all_day || $isAllDay) {
            return true;
        }

        return $this->timeRangesOverlap(
            substr((string) $this->start_time, 0, 8),
            substr((string) $this->end_time, 0, 8),
            substr((string) $startTime, 0, 8),
            substr((string) $endTime, 0, 8),
        );
    }

    public function effectiveAttendanceTimeForSchedule(TeachingSchedule $schedule): string
    {
        if ($this->is_all_day || !$this->start_time) {
            return substr((string) $schedule->start_time, 0, 8);
        }

        return substr((string) $this->start_time, 0, 8);
    }

    private function timeRangesOverlap(string $firstStart, string $firstEnd, string $secondStart, string $secondEnd): bool
    {
        return $firstStart < $secondEnd && $firstEnd > $secondStart;
    }
}
