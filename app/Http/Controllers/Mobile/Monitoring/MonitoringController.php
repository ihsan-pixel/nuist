<?php

namespace App\Http\Controllers\Mobile\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

        $selectedMonth = $request->input('month', Carbon::now('Asia/Jakarta')->format('Y-m'));
        $selectedClass = trim((string) $request->input('class_name', ''));
        $selectedMonthCarbon = Carbon::createFromFormat('Y-m', $selectedMonth, 'Asia/Jakarta');
        $startOfMonth = $selectedMonthCarbon->copy()->startOfMonth()->startOfDay();
        $endOfMonth = $selectedMonthCarbon->copy()->endOfMonth()->endOfDay();
        $today = Carbon::today('Asia/Jakarta')->endOfDay();
        $effectiveEnd = $endOfMonth->copy()->min($today);

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

        $recordsQuery = TeachingAttendance::with(['teachingSchedule.teacher', 'teachingSchedule.school'])
            ->whereHas('teachingSchedule', function ($q) use ($user) {
                $q->where('school_id', $user->madrasah_id);
            })
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $effectiveEnd->toDateString()])
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
            $dayOfWeek = match ($dayName) {
                'senin' => Carbon::MONDAY,
                'selasa' => Carbon::TUESDAY,
                'rabu' => Carbon::WEDNESDAY,
                'kamis' => Carbon::THURSDAY,
                'jumat' => Carbon::FRIDAY,
                'sabtu' => Carbon::SATURDAY,
                default => null,
            };

            if ($dayOfWeek === null) {
                continue;
            }

            $cursor = $startOfMonth->copy();
            while ($cursor <= $effectiveEnd) {
                if ($cursor->dayOfWeek !== $dayOfWeek) {
                    $cursor->addDay();
                    continue;
                }

                $attendance = TeachingAttendance::query()
                    ->where('teaching_schedule_id', $schedule->id)
                    ->whereDate('tanggal', $cursor->toDateString())
                    ->first();

                $expectedSessions->push([
                    'date' => $cursor->toDateString(),
                    'teacher' => $schedule->teacher?->name ?? '-',
                    'class_name' => $schedule->classNameLabel() ?: ($schedule->class_name ?? '-'),
                    'subject' => $schedule->subject ?? '-',
                    'time' => trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')),
                    'attendance' => $attendance,
                    'schedule' => $schedule,
                ]);

                $cursor->addDay();
            }
        }

        $completedJournals = $expectedSessions->filter(fn ($item) => !is_null($item['attendance']))->values();
        $missingJournals = $expectedSessions->filter(fn ($item) => is_null($item['attendance']))->values();

        $summary = [
            'total_jurnal' => $records->total(),
            'total_jadwal' => $expectedSessions->count(),
            'total_belum_jurnal' => $missingJournals->count(),
            'total_guru' => User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->count(),
            'bulan' => $selectedMonthCarbon->locale('id')->isoFormat('MMMM YYYY'),
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
            'selectedMonth',
            'selectedClass',
            'summary',
            'completedJournals',
            'missingJournals',
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
