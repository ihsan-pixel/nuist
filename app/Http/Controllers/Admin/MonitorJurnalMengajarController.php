<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $approvedSchoolActivityEvents = $this->academicCalendarEventService->getApprovedEventMapForSchedules(
            $schedules,
            $startOfMonth,
            $effectiveEnd
        );

        $recordsQuery = TeachingAttendance::with(['teachingSchedule.teacher', 'teachingSchedule.school', 'academicCalendarEvent'])
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

        $records = $recordsQuery->paginate(15)->withQueryString();

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
                $event = $approvedSchoolActivityEvents->get($schedule->id . '|' . $cursor->toDateString());

                $expectedSessions->push([
                    'date' => $cursor->toDateString(),
                    'teacher' => $schedule->teacher?->name ?? '-',
                    'class_name' => $schedule->classNameLabel() ?: ($schedule->class_name ?? '-'),
                    'subject' => $schedule->subject ?? '-',
                    'time' => trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')),
                    'attendance' => $attendance,
                    'event' => $event,
                    'status' => $event ? 'izin' : ($attendance ? 'hadir' : 'belum'),
                ]);

                $cursor->addDay();
            }
        }

        $completedJournals = $expectedSessions->filter(fn ($item) => !is_null($item['attendance']))->values();
        $approvedIzinSessions = $expectedSessions->filter(fn ($item) => ($item['status'] ?? null) === 'izin')->values();
        $missingJournals = $expectedSessions->filter(fn ($item) => ($item['status'] ?? null) === 'belum')->values();

        $topApprovedEvent = $approvedSchoolActivityEvents->first();
        $approvedEventLabel = $topApprovedEvent?->resolved_type_label;
        $approvedEventName = $topApprovedEvent?->name;
        $approvedEventNote = $topApprovedEvent?->description ?: $topApprovedEvent?->approval_notes;

        $summary = [
            'total_jurnal' => $records->total(),
            'total_jadwal' => $expectedSessions->count(),
            'total_izin' => $approvedIzinSessions->count(),
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

        return view('admin.monitor-jurnal-mengajar', compact(
            'records',
            'selectedMonth',
            'selectedClass',
            'summary',
            'completedJournals',
            'approvedIzinSessions',
            'approvedEventLabel',
            'approvedEventName',
            'approvedEventNote',
            'missingJournals',
            'availableClasses'
        ));
    }
}
