<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingSchedulePeriod extends Model
{
    use HasFactory;

    public const SEMESTER_GANJIL = 'ganjil';
    public const SEMESTER_GENAP = 'genap';

    protected $fillable = [
        'school_id',
        'title',
        'school_year',
        'semester',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function schedules()
    {
        return $this->hasMany(TeachingSchedule::class, 'teaching_schedule_period_id');
    }

    public function classStudentCounts()
    {
        return $this->hasMany(TeachingClassStudentCount::class, 'teaching_schedule_period_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeForSchool($query, int|string|null $schoolId)
    {
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query;
    }

    public function scopeActiveOn($query, CarbonInterface|string|null $date = null)
    {
        $dateValue = $date instanceof CarbonInterface ? $date->toDateString() : ($date ?: now('Asia/Jakarta')->toDateString());

        return $query
            ->whereDate('start_date', '<=', $dateValue)
            ->whereDate('end_date', '>=', $dateValue);
    }

    public static function activeForSchool(int|string|null $schoolId, CarbonInterface|string|null $date = null): ?self
    {
        if (!$schoolId) {
            return null;
        }

        return static::query()
            ->forSchool($schoolId)
            ->activeOn($date)
            ->orderByDesc('start_date')
            ->first();
    }

    public static function latestForSchool(int|string|null $schoolId): ?self
    {
        if (!$schoolId) {
            return null;
        }

        return static::query()
            ->forSchool($schoolId)
            ->orderByDesc('end_date')
            ->orderByDesc('start_date')
            ->first();
    }

    public function getSemesterLabelAttribute(): string
    {
        return match ($this->semester) {
            self::SEMESTER_GANJIL => 'Semester Ganjil',
            self::SEMESTER_GENAP => 'Semester Genap',
            default => 'Semester',
        };
    }

    public function getDateRangeLabelAttribute(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return '-';
        }

        return $this->start_date->translatedFormat('d M Y') . ' - ' . $this->end_date->translatedFormat('d M Y');
    }

    public function getSummaryLabelAttribute(): string
    {
        $parts = array_filter([
            trim((string) $this->title),
            trim((string) $this->semester_label),
            trim((string) $this->school_year),
        ]);

        return implode(' | ', $parts);
    }
}
