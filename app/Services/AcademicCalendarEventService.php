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
        $this->removeGeneratedAttendancesForEvent($event);
    }

    public function syncSchedule(TeachingSchedule $schedule): void
    {
        $this->removeGeneratedAttendancesForSchedule($schedule);
    }

    public function syncTeacherDate(User $teacher, Carbon|string $date): void
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        TeachingAttendance::query()
            ->where('user_id', $teacher->id)
            ->whereDate('tanggal', $date->toDateString())
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
    }

    public function syncTeacherRange(User $teacher, Carbon|string $startDate, Carbon|string $endDate): void
    {
        $startDate = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
        $endDate = $endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate, 'Asia/Jakarta')->startOfDay();

        if ($endDate->lt($startDate)) {
            return;
        }

        TeachingAttendance::query()
            ->where('user_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
    }

    public function syncSchoolDate(int $schoolId, Carbon|string $date): void
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        TeachingAttendance::query()
            ->whereHas('teachingSchedule', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereDate('tanggal', $date->toDateString())
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
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
            ->first(fn (AcademicCalendarEvent $event) => $event->isApproved() && $event->affectsScheduleOnDate($schedule, $date));
    }

    public function ensureAutomaticAttendanceForSchedule(TeachingSchedule $schedule, Carbon|string $date): ?AcademicCalendarEvent
    {
        return $this->eventForScheduleDate($schedule, $date);
    }

    public function removeGeneratedAttendancesForEvent(AcademicCalendarEvent $event): void
    {
        TeachingAttendance::query()
            ->where('academic_calendar_event_id', $event->id)
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
    }

    public function buildVirtualAttendanceForSchedule(
        TeachingSchedule $schedule,
        Carbon|string $date,
        ?AcademicCalendarEvent $event = null
    ): ?TeachingAttendance
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');
        $event = $event ?: $this->eventForScheduleDate($schedule, $date);

        if (!$event) {
            return null;
        }

        $attendance = new TeachingAttendance([
            'teaching_schedule_id' => $schedule->id,
            'user_id' => $schedule->teacher_id,
            'tanggal' => $date->toDateString(),
            'waktu' => $event->effectiveAttendanceTimeForSchedule($schedule),
            'status' => 'izin',
            'attendance_source' => TeachingAttendance::SOURCE_ACADEMIC_CALENDAR,
            'status_label' => $event->resolved_type_label,
            'academic_calendar_event_id' => $event->id,
            'is_auto_generated' => false,
            'lokasi' => $event->name,
            'materi' => null,
        ]);

        $attendance->setRelation('teachingSchedule', $schedule);
        $attendance->setRelation('academicCalendarEvent', $event);
        $attendance->setAttribute('virtual_entry', true);

        return $attendance;
    }

    public function buildVirtualAttendancesForSchedules(
        Collection $schedules,
        Carbon|string $startDate,
        Carbon|string $endDate
    ): Collection
    {
        $startDate = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
        $endDate = $endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate, 'Asia/Jakarta')->startOfDay();

        if ($schedules->isEmpty() || $endDate->lt($startDate)) {
            return collect();
        }

        $scheduleIds = $schedules->pluck('id')->filter()->values();
        $existingKeys = TeachingAttendance::query()
            ->whereIn('teaching_schedule_id', $scheduleIds)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->get(['teaching_schedule_id', 'tanggal'])
            ->mapWithKeys(function ($attendance) {
                return [$attendance->teaching_schedule_id . '|' . Carbon::parse($attendance->tanggal)->toDateString() => true];
            });

        $eventKeys = $this->getApprovedEventMapForSchedules($schedules, $startDate, $endDate);

        return $eventKeys->map(function (AcademicCalendarEvent $event, string $key) use ($schedules, $existingKeys) {
            [$scheduleId, $date] = explode('|', $key, 2);

            if ($existingKeys->has($key)) {
                return null;
            }

            $schedule = $schedules->firstWhere('id', (int) $scheduleId);
            if (!$schedule) {
                return null;
            }

            return $this->buildVirtualAttendanceForSchedule($schedule, $date, $event);
        })->filter()->values();
    }

    public function getApprovedEventMapForSchedules(
        Collection $schedules,
        Carbon|string $startDate,
        Carbon|string $endDate
    ): Collection {
        $startDate = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
        $endDate = $endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate, 'Asia/Jakarta')->startOfDay();

        if ($schedules->isEmpty() || $endDate->lt($startDate)) {
            return collect();
        }

        $eventsBySchool = AcademicCalendarEvent::query()
            ->whereIn('school_id', $schedules->pluck('school_id')->filter()->unique()->values())
            ->where('is_active', true)
            ->where('approval_status', AcademicCalendarEvent::APPROVAL_APPROVED)
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->orderByRaw("
                CASE event_type
                    WHEN '" . AcademicCalendarEvent::TYPE_ACADEMIC_HOLIDAY . "' THEN 1
                    WHEN '" . AcademicCalendarEvent::TYPE_SCHOOL_ACTIVITY . "' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('start_time')
            ->get()
            ->groupBy('school_id');

        $eventKeys = collect();

        foreach ($schedules as $schedule) {
            $schoolEvents = $eventsBySchool->get($schedule->school_id, collect());
            if ($schoolEvents->isEmpty()) {
                continue;
            }

            foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
                $event = $schoolEvents->first(function (AcademicCalendarEvent $event) use ($schedule, $date) {
                    return $event->affectsScheduleOnDate($schedule, $date);
                });

                if ($event) {
                    $eventKeys->put($schedule->id . '|' . $date->toDateString(), $event);
                }
            }
        }

        return $eventKeys;
    }

    private function removeGeneratedAttendancesForSchedule(TeachingSchedule $schedule): void
    {
        TeachingAttendance::query()
            ->where('teaching_schedule_id', $schedule->id)
            ->where('attendance_source', TeachingAttendance::SOURCE_ACADEMIC_CALENDAR)
            ->where('is_auto_generated', true)
            ->delete();
    }
}
