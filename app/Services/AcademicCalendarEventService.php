<?php

namespace App\Services;

use App\Models\AcademicCalendarEvent;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AcademicCalendarEventService
{
    public function syncEvent(AcademicCalendarEvent $event): void
    {
        $event->loadMissing('school');

        $this->removeGeneratedAttendancesForEvent($event);

        if (!$event->is_active) {
            return;
        }

        TeachingSchedule::query()
            ->with('teacher')
            ->where('school_id', $event->school_id)
            ->orderBy('teacher_id')
            ->chunk(100, function ($schedules) use ($event) {
                foreach ($schedules as $schedule) {
                    $this->syncScheduleAgainstEvent($schedule, $event);
                }
            });
    }

    public function syncSchedule(TeachingSchedule $schedule): void
    {
        $schedule->loadMissing('teacher', 'school');

        AcademicCalendarEvent::query()
            ->where('school_id', $schedule->school_id)
            ->where('is_active', true)
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get()
            ->each(function (AcademicCalendarEvent $event) use ($schedule) {
                $this->syncScheduleAgainstEvent($schedule, $event);
            });
    }

    public function syncTeacherDate(User $teacher, Carbon|string $date): void
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');
        $dayName = $date->locale('id')->dayName;

        TeachingSchedule::query()
            ->with('teacher')
            ->where('teacher_id', $teacher->id)
            ->whereRaw('LOWER(day) = ?', [strtolower((string) $dayName)])
            ->get()
            ->each(function (TeachingSchedule $schedule) use ($date) {
                $event = $this->eventForScheduleDate($schedule, $date);
                if ($event) {
                    $this->syncScheduleDate($schedule, $date, $event);
                }
            });
    }

    public function syncTeacherRange(User $teacher, Carbon|string $startDate, Carbon|string $endDate): void
    {
        $startDate = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
        $endDate = $endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate, 'Asia/Jakarta')->startOfDay();

        if ($endDate->lt($startDate)) {
            return;
        }

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $this->syncTeacherDate($teacher, $date);
        }
    }

    public function syncSchoolDate(int $schoolId, Carbon|string $date): void
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');
        $dayName = $date->locale('id')->dayName;

        TeachingSchedule::query()
            ->with('teacher')
            ->where('school_id', $schoolId)
            ->whereRaw('LOWER(day) = ?', [strtolower((string) $dayName)])
            ->get()
            ->each(function (TeachingSchedule $schedule) use ($date) {
                $event = $this->eventForScheduleDate($schedule, $date);
                if ($event) {
                    $this->syncScheduleDate($schedule, $date, $event);
                }
            });
    }

    public function eventForScheduleDate(TeachingSchedule $schedule, Carbon|string $date): ?AcademicCalendarEvent
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        return AcademicCalendarEvent::query()
            ->where('school_id', $schedule->school_id)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->orderByRaw("
                CASE event_type
                    WHEN '" . AcademicCalendarEvent::TYPE_ACADEMIC_HOLIDAY . "' THEN 1
                    WHEN '" . AcademicCalendarEvent::TYPE_SCHOOL_ACTIVITY . "' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('start_time')
            ->get()
            ->first(fn (AcademicCalendarEvent $event) => $event->coversScheduleStartOnDate($schedule, $date));
    }

    public function ensureAutomaticAttendanceForSchedule(TeachingSchedule $schedule, Carbon|string $date): ?AcademicCalendarEvent
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');
        $event = $this->eventForScheduleDate($schedule, $date);

        if (!$event) {
            return null;
        }

        $this->syncScheduleDate($schedule, $date, $event);

        return $event;
    }

    public function removeGeneratedAttendancesForEvent(AcademicCalendarEvent $event): void
    {
        TeachingAttendance::query()
            ->where('academic_calendar_event_id', $event->id)
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
    }

    private function syncScheduleAgainstEvent(TeachingSchedule $schedule, AcademicCalendarEvent $event): void
    {
        foreach (CarbonPeriod::create($event->start_date, $event->end_date) as $date) {
            if (!$event->coversScheduleStartOnDate($schedule, $date)) {
                continue;
            }

            $this->syncScheduleDate($schedule, $date, $event);
        }
    }

    private function syncScheduleDate(TeachingSchedule $schedule, Carbon $date, AcademicCalendarEvent $event): TeachingAttendance
    {
        $existingAttendance = TeachingAttendance::query()
            ->where('teaching_schedule_id', $schedule->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $payload = [
            'teaching_schedule_id' => $schedule->id,
            'user_id' => $schedule->teacher_id,
            'tanggal' => $date->toDateString(),
            'waktu' => $event->effectiveAttendanceTimeForSchedule($schedule),
            'status' => 'izin',
            'attendance_source' => TeachingAttendance::SOURCE_ACADEMIC_CALENDAR,
            'status_label' => $event->resolved_type_label,
            'academic_calendar_event_id' => $event->id,
            'is_auto_generated' => true,
            'lokasi' => $event->name,
            'materi' => null,
        ];

        if (!$existingAttendance) {
            return TeachingAttendance::create($payload);
        }

        if (
            $existingAttendance->attendance_source === TeachingAttendance::SOURCE_ACADEMIC_CALENDAR &&
            $existingAttendance->is_auto_generated
        ) {
            $existingAttendance->update($payload);
            return $existingAttendance->fresh();
        }

        return $existingAttendance;
    }
}
