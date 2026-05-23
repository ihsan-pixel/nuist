<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingAttendance extends Model
{
    use HasFactory;

    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_ACADEMIC_CALENDAR = 'academic_calendar';

    protected $fillable = [
        'teaching_schedule_id',
        'user_id',
        'tanggal',
        'waktu',
        'status',
        'attendance_source',
        'status_label',
        'academic_calendar_event_id',
        'is_auto_generated',
        'latitude',
        'longitude',
        'lokasi',
        'materi',
        'class_total_students',
        'present_students',
        'student_attendance_percentage',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_auto_generated' => 'boolean',
    ];

    public function teachingSchedule()
    {
        return $this->belongsTo(TeachingSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicCalendarEvent()
    {
        return $this->belongsTo(AcademicCalendarEvent::class, 'academic_calendar_event_id');
    }

    public function getDisplayStatusLabelAttribute(): string
    {
        if (trim((string) $this->status_label) !== '') {
            return trim((string) $this->status_label);
        }

        return ($this->status ?? 'hadir') === 'izin' ? 'Izin' : 'Hadir';
    }

    public function getIsAcademicCalendarAutoAttribute(): bool
    {
        return $this->attendance_source === self::SOURCE_ACADEMIC_CALENDAR && $this->is_auto_generated;
    }

    public function getIsAcademicCalendarEventAttribute(): bool
    {
        return $this->attendance_source === self::SOURCE_ACADEMIC_CALENDAR;
    }
}
