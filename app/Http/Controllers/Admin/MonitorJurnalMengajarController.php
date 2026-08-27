<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AcademicCalendarEventService;

class MonitorJurnalMengajarController extends Controller
{
    public function __construct(
        private AcademicCalendarEventService $academicCalendarEventService,
    ) {
    }

    private function ensureAdmin(): User
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->ensureAdmin();

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'), 'Asia/Jakarta')
            : Carbon::today('Asia/Jakarta');
        $selectedClass = trim((string) $request->input('class_name', ''));
        $weekStart = $selectedDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(5)->endOfDay();
        $today = Carbon::today('Asia/Jakarta')->endOfDay();
        $effectiveEnd = $weekEnd->copy()->min($today);
        $selectedDay = strtolower($request->input('day', $selectedDate->locale('id')->dayName));
        $dayMap = [
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY,
        ];
        $selectedDayOfWeek = $dayMap[$selectedDay] ?? Carbon::MONDAY;
        $selectedDayDate = null;

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

        $records = $recordsQuery->paginate(15)->withQueryString();

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

        $weekDays = collect($dayMap)->map(function ($dayOfWeek, $label) use ($weekStart, $weekEnd, $effectiveEnd) {
            $date = $weekStart->copy()->addDays($dayOfWeek - Carbon::MONDAY);
            return [
                'key' => $label,
                'label' => ucfirst($label),
                'date' => $date->toDateString(),
                'active' => $date->betweenIncluded($weekStart, $effectiveEnd),
            ];
        })->values();

        $selectedDayDate = $weekStart->copy()->addDays($selectedDayOfWeek - Carbon::MONDAY);
        $selectedRecap = $dailyRecaps->firstWhere('date', $selectedDayDate->toDateString());
        $selectedRecap = $selectedRecap ?: [
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
            ->sortBy(function ($item) {
                return ($item['class_name'] ?? '') . '|' . ($item['time'] ?? '');
            })
            ->groupBy(fn ($item) => $item['class_name'] ?? '-')
            ->map(function ($items, $className) {
                return [
                    'class_name' => $className,
                    'items' => $items->sortBy(fn ($item) => $item['time'] ?? '')->values(),
                ];
            })
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

        return view('admin.monitor-jurnal-mengajar', compact(
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
}
