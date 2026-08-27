<?php

namespace App\Http\Controllers\Mobile\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Services\AcademicCalendarEventService;
use App\Services\AttendanceObligationService;
use App\Services\ApprovedIzinSyncService;
use App\Services\ExternalTeachingPermissionService;

class MonitoringController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private AcademicCalendarEventService $academicCalendarEventService,
        private AttendanceObligationService $attendanceObligationService,
    )
    {
    }

    /**
     * Monitoring presensi page for kepala madrasah
     */
    public function monitorPresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $this->syncMadrasahAttendancePermissionsForDate($user, $selectedDate);

        $presensis = Presensi::with(['user', 'statusKepegawaian'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            })
            ->whereDate('tanggal', $selectedDate)
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(15, ['*'], 'presensi_page')
            ->withQueryString();

        $belumPresensiIds = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })
            ->get()
            ->filter(fn (User $teacher) => $this->attendanceObligationService->hasAttendanceObligation($teacher, $selectedDate))
            ->pluck('id');

        $belumPresensi = User::whereIn('id', $belumPresensiIds)
            ->paginate(15, ['*'], 'belum_page')
            ->withQueryString();

        return view('mobile.monitor-presensi', compact('presensis', 'belumPresensi', 'selectedDate'));
    }

    /**
     * Monitoring jadwal mengajar page for kepala madrasah
     */
    public function monitorJadwalMengajar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $this->academicCalendarEventService->syncSchoolDate((int) $user->madrasah_id, $selectedDate);

        // Get day name in Indonesian for the selected date
        $dayName = $selectedDate->locale('id')->dayName;

        // Fetch teaching schedules for the madrasah on the selected day
        $schedules = TeachingSchedule::with(['teacher', 'teachingAttendances' => function ($q) use ($selectedDate) {
            $q->whereDate('tanggal', $selectedDate);
        }])
        ->where('school_id', $user->madrasah_id)
        ->whereRaw('LOWER(day) = ?', [strtolower($dayName)])
        ->orderBy('start_time')
        ->get();

        // Attach attendance status to each schedule
        $schedules->each(function ($schedule) use ($selectedDate) {
            $attendance = $schedule->teachingAttendances->first();
            $calendarEvent = $this->academicCalendarEventService->eventForScheduleDate($schedule, $selectedDate);

            if ($calendarEvent) {
                $schedule->attendance_status = 'izin';
                $schedule->attendance_time = $calendarEvent->effectiveAttendanceTimeForSchedule($schedule);
                $schedule->attendance_label = $calendarEvent->resolved_type_label;
                $schedule->attendance_event_name = $calendarEvent->name;
                return;
            }

            $schedule->attendance_status = $attendance ? 'hadir' : 'belum';
            $schedule->attendance_time = $attendance ? $attendance->waktu : null;
        });

        return view('mobile.monitor-jadwal-mengajar', compact('schedules', 'selectedDate'));
    }

    /**
     * Monitoring jurnal mengajar for kepala madrasah
     */
    public function monitorJurnalMengajar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'), 'Asia/Jakarta')
            : Carbon::today('Asia/Jakarta');
        $selectedClass = trim((string) $request->input('class_name', ''));
        $weekStart = $selectedDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(5)->endOfDay();
        $today = Carbon::today('Asia/Jakarta')->endOfDay();
        $effectiveEnd = $weekEnd->copy()->min($today);
        $dayMap = [
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY,
        ];
        $selectedDay = strtolower($request->input('day', $selectedDate->locale('id')->dayName));
        $selectedDayOfWeek = $dayMap[$selectedDay] ?? Carbon::MONDAY;

        $scheduleQuery = TeachingSchedule::with(['teacher'])
            ->where('school_id', $user->madrasah_id);

        if ($selectedClass !== '') {
            $scheduleQuery->where(function ($q) use ($selectedClass) {
                $q->whereRaw('LOWER(class_name) = ?', [mb_strtolower($selectedClass)])
                    ->orWhereJsonContains('class_names', $selectedClass);
            });
        }

        $schedules = $scheduleQuery
            ->orderByRaw('LOWER(COALESCE(class_name, "")) ASC')
            ->orderByRaw('LOWER(subject) ASC')
            ->orderBy('start_time')
            ->get();

        $approvedSchoolActivityEvents = $this->academicCalendarEventService->getApprovedEventMapForSchedules(
            $schedules,
            $weekStart,
            $effectiveEnd
        );
        $holidayMap = Holiday::query()
            ->where('is_active', true)
            ->whereBetween('date', [$weekStart->toDateString(), $effectiveEnd->toDateString()])
            ->get()
            ->keyBy(fn (Holiday $holiday) => $holiday->getRawOriginal('date'));

        $recordsQuery = TeachingAttendance::with(['teachingSchedule.teacher', 'teachingSchedule.school', 'academicCalendarEvent'])
            ->whereHas('teachingSchedule', function ($q) use ($user) {
                $q->where('school_id', $user->madrasah_id);
            })
            ->whereBetween('tanggal', [$weekStart->toDateString(), $effectiveEnd->toDateString()])
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc');

        if ($selectedClass !== '') {
            $recordsQuery->whereHas('teachingSchedule', function ($q) use ($selectedClass) {
                $q->whereRaw('LOWER(class_name) = ?', [mb_strtolower($selectedClass)])
                    ->orWhereJsonContains('class_names', $selectedClass);
            });
        }

        $records = $recordsQuery->paginate(12, ['*'], 'jurnal_page')->withQueryString();

        $expectedSessions = collect();
        foreach ($schedules as $schedule) {
            $dayName = strtolower(trim((string) $schedule->day));
            $dayOfWeek = $dayMap[$dayName] ?? null;

            if ($dayOfWeek === null) {
                continue;
            }

            $cursor = $weekStart->copy();
            while ($cursor <= $effectiveEnd) {
                if ($cursor->dayOfWeek !== $dayOfWeek) {
                    $cursor->addDay();
                    continue;
                }

                $attendance = TeachingAttendance::query()
                    ->where('teaching_schedule_id', $schedule->id)
                    ->whereDate('tanggal', $cursor->toDateString())
                    ->first();
                $event = $approvedSchoolActivityEvents->get($schedule->id . '|' . $cursor->toDateString());
                $holiday = $holidayMap->get($cursor->toDateString());
                $status = $event ? 'izin' : (($holiday && !$attendance) ? 'libur' : ($attendance ? 'hadir' : 'belum'));

                $expectedSessions->push([
                    'date' => $cursor->toDateString(),
                    'teacher' => $schedule->teacher?->name ?? '-',
                    'class_name' => $schedule->classNameLabel() ?: ($schedule->class_name ?? '-'),
                    'subject' => $schedule->subject ?? '-',
                    'time' => trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')),
                    'attendance' => $attendance,
                    'schedule' => $schedule,
                    'event' => $event,
                    'holiday' => $holiday,
                    'status' => $status,
                ]);

                $cursor->addDay();
            }
        }

        $completedJournals = $expectedSessions->filter(fn ($item) => !is_null($item['attendance']))->values();
        $approvedIzinSessions = $expectedSessions->filter(fn ($item) => ($item['status'] ?? null) === 'izin')->values();
        $holidaySessions = $expectedSessions->filter(fn ($item) => ($item['status'] ?? null) === 'libur')->values();
        $missingJournals = $expectedSessions->filter(fn ($item) => ($item['status'] ?? null) === 'belum')->values();
        $dailyRecaps = $expectedSessions
            ->groupBy('date')
            ->map(function ($items, $date) {
                $items = $items->values();

                return [
                    'date' => $date,
                    'label' => Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                    'total' => $items->count(),
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'libur' => $items->where('status', 'libur')->count(),
                    'belum' => $items->where('status', 'belum')->count(),
                    'items' => $items,
                ];
            })
            ->sortByDesc('date')
            ->values();

        $weekDays = collect($dayMap)->map(function ($dayOfWeek, $label) use ($weekStart, $effectiveEnd) {
            $date = $weekStart->copy()->addDays($dayOfWeek - Carbon::MONDAY);
            return [
                'key' => $label,
                'label' => ucfirst($label),
                'date' => $date->toDateString(),
                'active' => $date->betweenIncluded($weekStart, $effectiveEnd),
            ];
        })->values();

        $selectedDayDate = $weekStart->copy()->addDays($selectedDayOfWeek - Carbon::MONDAY);
        $selectedRecap = $dailyRecaps->firstWhere('date', $selectedDayDate->toDateString()) ?: [
            'date' => $selectedDayDate->toDateString(),
            'label' => $selectedDayDate->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'total' => 0,
            'hadir' => 0,
            'izin' => 0,
            'libur' => 0,
            'belum' => 0,
            'items' => collect(),
        ];
        $selectedRecap['items'] = collect($selectedRecap['items'])
            ->sortBy(fn ($item) => ($item['class_name'] ?? '') . '|' . ($item['time'] ?? ''))
            ->groupBy(fn ($item) => $item['class_name'] ?? '-')
            ->map(fn ($items, $className) => [
                'class_name' => $className,
                'items' => $items->sortBy(fn ($item) => $item['time'] ?? '')->values(),
            ])
            ->values();

        $topApprovedEvent = $approvedSchoolActivityEvents->first();
        $approvedEventLabel = $topApprovedEvent?->resolved_type_label;
        $approvedEventName = $topApprovedEvent?->name;
        $approvedEventNote = $topApprovedEvent?->description ?: $topApprovedEvent?->approval_notes;

        $summary = [
            'total_jurnal' => $records->total(),
            'total_jadwal' => $expectedSessions->count(),
            'total_izin' => $approvedIzinSessions->count(),
            'total_libur' => $holidaySessions->count(),
            'total_belum_jurnal' => $missingJournals->count(),
            'total_guru' => User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->count(),
            'week_label' => $weekStart->locale('id')->isoFormat('D MMMM YYYY') . ' - ' . $effectiveEnd->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        $availableClasses = $schedules
            ->flatMap(function ($schedule) {
                $names = $schedule->resolvedClassNames();
                return empty($names) ? [trim((string) $schedule->class_name)] : $names;
            })
            ->map(fn ($className) => trim((string) $className))
            ->filter()
            ->unique(fn ($className) => mb_strtolower($className))
            ->sort()
            ->values();

        return view('mobile.monitor-jurnal-mengajar', compact(
            'records',
            'selectedDate',
            'selectedClass',
            'selectedDay',
            'weekDays',
            'selectedRecap',
            'summary',
            'completedJournals',
            'approvedIzinSessions',
            'approvedEventLabel',
            'approvedEventName',
            'approvedEventNote',
            'missingJournals',
            'dailyRecaps',
            'availableClasses'
        ));
    }

    /**
     * Dedicated map monitoring page for kepala madrasah
     */
    public function monitorMap(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $this->syncMadrasahAttendancePermissionsForDate($user, $selectedDate);

        // Get presensi data for the madrasah
        $presensis = Presensi::with(['user', 'statusKepegawaian'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            })
            ->whereDate('tanggal', $selectedDate)
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Get users who haven't done presensi
        $belumPresensi = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })
            ->get()
            ->filter(fn (User $teacher) => $this->attendanceObligationService->hasAttendanceObligation($teacher, $selectedDate))
            ->values();

        // Prepare map data
        $madrasahLat = $user->madrasah->latitude ?? -6.2088; // Default Jakarta coordinates
        $madrasahLng = $user->madrasah->longitude ?? 106.8456;
        $mapData = [];

        // Add markers for users who have done presensi
        foreach ($presensis as $presensi) {
            $mapData[] = [
                'id' => $presensi->user->id,
                'name' => $presensi->user->name,
                'status' => $presensi->status,
                'latitude' => $presensi->latitude ?? $madrasahLat,
                'longitude' => $presensi->longitude ?? $madrasahLng,
                'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : null,
                'waktu_keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : null,
                'lokasi' => $presensi->lokasi ?? 'Lokasi tidak tersedia',
                'marker_type' => 'presensi',
                'status_kepegawaian' => $presensi->user->statusKepegawaian?->name ?? '-'
            ];
        }

        // Add markers for users who haven't done presensi (at madrasah location)
        foreach ($belumPresensi as $userBelum) {
            $mapData[] = [
                'id' => $userBelum->id,
                'name' => $userBelum->name,
                'status' => 'belum_presensi',
                'latitude' => $madrasahLat,
                'longitude' => $madrasahLng,
                'waktu_masuk' => null,
                'waktu_keluar' => null,
                'lokasi' => $user->madrasah->alamat ?? 'Alamat madrasah',
                'marker_type' => 'belum_presensi',
                'status_kepegawaian' => $userBelum->statusKepegawaian?->name ?? '-'
            ];
        }

        return view('mobile.monitor-map', compact('mapData', 'selectedDate', 'presensis', 'belumPresensi'));
    }

    private function syncMadrasahAttendancePermissionsForDate(User $kepalaMadrasah, Carbon $selectedDate): void
    {
        User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $kepalaMadrasah->madrasah_id)
            ->with('madrasah')
            ->get()
            ->each(function (User $teacher) use ($selectedDate) {
                ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($teacher, $selectedDate);

                if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate)) {
                    ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($teacher, $selectedDate);
                }
            });
    }
}
