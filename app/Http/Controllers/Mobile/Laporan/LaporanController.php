<?php

namespace App\Http\Controllers\Mobile\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Holiday;
use App\Models\Izin;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\User;
use App\Services\AcademicCalendarEventService;
use App\Services\ApprovedIzinSyncService;
use App\Services\ExternalTeachingPermissionService;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends \App\Http\Controllers\Controller
{
    public function __construct(private AcademicCalendarEventService $academicCalendarEventService)
    {
    }

    // Laporan (reports) stubs
    public function laporan()
    {
        return view('mobile.laporan');
    }

    public function laporanMengajar()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $selectedMonth = Carbon::now();

        $this->academicCalendarEventService->syncTeacherRange(
            $user,
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth()
        );

        $history = TeachingAttendance::with(['teachingSchedule.school', 'academicCalendarEvent'])
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->get();

        $scheduleMap = \App\Models\TeachingSchedule::with('school')
            ->where('teacher_id', $user->id)
            ->get()
            ->keyBy('id');
        $eventKeys = $this->academicCalendarEventService->getApprovedEventMapForSchedules(
            $scheduleMap->values(),
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth()
        );

        $filteredHistory = $history->filter(function (TeachingAttendance $attendance) use ($eventKeys) {
            return !$eventKeys->has($attendance->teaching_schedule_id . '|' . Carbon::parse($attendance->tanggal)->toDateString());
        });

        $virtualHistory = $eventKeys->map(function ($event, $key) use ($scheduleMap) {
            [$scheduleId, $date] = explode('|', $key, 2);
            $schedule = $scheduleMap->get((int) $scheduleId);

            return $schedule
                ? $this->academicCalendarEventService->buildVirtualAttendanceForSchedule($schedule, $date, $event)
                : null;
        })->filter();

        $history = $filteredHistory
            ->concat($virtualHistory)
            ->sortByDesc(function ($item) {
                return Carbon::parse($item->tanggal)->toDateString() . ' ' . ($item->waktu ?? '00:00:00');
            })
            ->values();

        return view('mobile.laporan-mengajar', compact('history'));
    }

    public function laporanPersentaseKehadiran(Request $request)
    {
        $user = Auth::user();

        if (!$this->canAccessAttendancePercentageReport($user)) {
            abort(403, 'Unauthorized.');
        }

        return view('mobile.laporan-persentase-kehadiran', $this->buildAttendancePercentageReportData($request, $user));
    }

    public function downloadPersentaseKehadiranPdf(Request $request)
    {
        $user = Auth::user();

        if (!$this->canAccessAttendancePercentageReport($user)) {
            abort(403, 'Unauthorized.');
        }

        if ($request->filled('export_month') && !$request->filled('month')) {
            $request->merge(['month' => $request->input('export_month')]);
        }

        $reportData = $this->buildAttendancePercentageReportData($request, $user);
        $schoolName = trim((string) ($user->madrasah->name ?? 'sekolah'));
        $filename = 'rekap-persentase-kehadiran-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower($schoolName)) . '-' . $reportData['selectedMonthValue'] . '.pdf';

        $pdf = Pdf::loadView('pdf.mobile-attendance-percentage-school-rekap', array_merge($reportData, [
            'school' => $user->madrasah,
            'generatedAt' => Carbon::now('Asia/Jakarta'),
        ]))->setPaper('A4', 'landscape');

        return $pdf->download($filename);
    }

    // Teaching attendances (mobile)
    public function teachingAttendances(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Always use today's date, ignore any date param to show only current day data
        $selectedDate = Carbon::today('Asia/Jakarta');
        $todayName = $selectedDate->locale('id')->dayName;

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($user, $selectedDate);

        $approvedIzinPresensi = ApprovedIzinSyncService::approvedTeachingJournalRequestForDate($user, $selectedDate)
            ?? ExternalTeachingPermissionService::approvedRequestForDate($user, $selectedDate);
        $approvedIzinNote = $approvedIzinPresensi
            ? ($approvedIzinPresensi->type === ExternalTeachingPermissionService::TYPE
                ? ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI
                : ($approvedIzinPresensi->alasan ?: $approvedIzinPresensi->deskripsi_tugas))
            : null;

        $this->academicCalendarEventService->syncTeacherDate($user, $selectedDate);

        // Build schedule query with today's teaching attendances, filtered by current day
        $query = \App\Models\TeachingSchedule::with(['teacher', 'school', 'teachingAttendances' => function ($q) use ($selectedDate) {
            $q->with('academicCalendarEvent');
            $q->whereDate('tanggal', $selectedDate);
        }]);

        // Always show only the user's own teaching schedules
        $query->where('teacher_id', $user->id);

        // Filter by current day's name (case insensitive)
        $query->whereRaw('LOWER(day) = ?', [strtolower($todayName)]);

        $schedules = $query->orderBy('start_time')->get();

        // Show all schedules for the day, including past ones (UI will handle disabling buttons)
        // Removed time filter to allow viewing all schedules for the day

        // Normalize: attach shortcut `attendance` to each schedule (first attendance of the day or null)
        $schedules->each(function ($schedule) {
            $attendance = $schedule->teachingAttendances->first() ?? null;
            $calendarEvent = $this->academicCalendarEventService->eventForScheduleDate($schedule, Carbon::today('Asia/Jakarta'));
            $schedule->attendance = $calendarEvent
                ? $this->academicCalendarEventService->buildVirtualAttendanceForSchedule($schedule, Carbon::today('Asia/Jakarta'), $calendarEvent)
                : $attendance;
        });

        $this->attachClassStudentCounts($schedules);

        $today = $selectedDate->toDateString();

        return view('mobile.teaching-attendances', compact('today', 'schedules', 'approvedIzinPresensi', 'approvedIzinNote'));
    }

    private function attachClassStudentCounts($schedules): void
    {
        $schoolIds = $schedules->pluck('school_id')->filter()->unique()->values();
        $classNames = $schedules->pluck('class_name')
            ->map(fn ($className) => trim((string) $className))
            ->filter()
            ->unique()
            ->values();

        if ($schoolIds->isEmpty() || $classNames->isEmpty()) {
            return;
        }

        $counts = TeachingClassStudentCount::whereIn('school_id', $schoolIds)
            ->whereIn('class_name', $classNames)
            ->get()
            ->keyBy(fn ($count) => $this->classStudentCountKey($count->school_id, $count->class_name));

        $schedules->each(function ($schedule) use ($counts) {
            $schedule->class_student_count = $counts->get(
                $this->classStudentCountKey($schedule->school_id, $schedule->class_name)
            );
        });
    }

    private function classStudentCountKey($schoolId, $className): string
    {
        return $schoolId . '|' . strtolower(trim((string) $className));
    }

    private function buildAttendancePercentageReportData(Request $request, User $user): array
    {
        $teacherOptions = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->orderBy('name')
            ->get(['id', 'name', 'ketugasan', 'madrasah_id']);

        $today = Carbon::today('Asia/Jakarta');
        $availableMonths = $this->getAvailableAttendanceMonths($teacherOptions, $today);

        $selectedWeek = $request->filled('week') && preg_match('/^\d{4}-W\d{2}$/', $request->week)
            ? Carbon::now('Asia/Jakarta')->setISODate(
                (int) substr($request->week, 0, 4),
                (int) substr($request->week, 6, 2)
            )->startOfWeek(Carbon::MONDAY)
            : $today->copy()->startOfWeek(Carbon::MONDAY);

        $selectedMonthValue = $request->filled('month') && preg_match('/^\d{4}-\d{2}$/', (string) $request->month)
            ? $request->month
            : ($availableMonths->first()['value'] ?? $today->format('Y-m'));

        if ($availableMonths->isNotEmpty() && !$availableMonths->contains(fn ($item) => $item['value'] === $selectedMonthValue)) {
            $selectedMonthValue = $availableMonths->first()['value'];
        }

        $selectedMonth = Carbon::createFromFormat('Y-m', $selectedMonthValue, 'Asia/Jakarta')->startOfMonth();

        $selectedTeacherId = (int) ($request->input('teacher_id') ?: 0);
        $selectedTeacher = $teacherOptions->firstWhere('id', $selectedTeacherId)
            ?? $teacherOptions->firstWhere('id', $user->id)
            ?? $teacherOptions->first();

        $schoolTeacherSummaries = $this->buildSchoolTeacherSummaries(
            $teacherOptions,
            $user->madrasah?->hari_kbm,
            $selectedWeek,
            $selectedMonth,
            $today
        );

        $schoolOverview = $this->buildSchoolAttendanceOverview($schoolTeacherSummaries);
        $selectedTeacherSummary = $selectedTeacher
            ? $schoolTeacherSummaries->first(fn ($item) => $item['teacher']->id === $selectedTeacher->id)
            : null;

        $weeklySummary = $selectedTeacher
            ? (($selectedTeacherSummary['weekly'] ?? null) ?: $this->emptyAttendanceSummary(
                $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
                $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY)
            ))
            : $this->emptyAttendanceSummary(
                $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
                $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY)
            );

        $monthlySummary = $selectedTeacher
            ? (($selectedTeacherSummary['monthly'] ?? null) ?: $this->emptyAttendanceSummary(
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth()
            ))
            : $this->emptyAttendanceSummary(
                $selectedMonth->copy()->startOfMonth(),
                $selectedMonth->copy()->endOfMonth()
            );

        return [
            'selectedWeekValue' => $selectedWeek->format('o-\WW'),
            'selectedMonthValue' => $selectedMonthValue,
            'selectedWeekLabel' => $selectedWeek->copy()->startOfWeek(Carbon::MONDAY)->translatedFormat('d M Y')
                . ' - ' . $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY)->translatedFormat('d M Y'),
            'selectedMonthLabel' => $selectedMonth->translatedFormat('F Y'),
            'availableMonths' => $availableMonths,
            'teacherOptions' => $teacherOptions,
            'selectedTeacher' => $selectedTeacher,
            'weeklySummary' => $weeklySummary,
            'monthlySummary' => $monthlySummary,
            'schoolTeacherSummaries' => $schoolTeacherSummaries,
            'schoolOverview' => $schoolOverview,
        ];
    }

    private function getAvailableAttendanceMonths($teacherOptions, Carbon $today)
    {
        $teacherIds = $teacherOptions->pluck('id')->filter()->values();

        if ($teacherIds->isEmpty()) {
            return collect([[
                'value' => $today->format('Y-m'),
                'label' => $today->translatedFormat('F Y'),
            ]]);
        }

        $presensiMonths = Presensi::query()
            ->whereIn('user_id', $teacherIds)
            ->selectRaw("DISTINCT DATE_FORMAT(tanggal, '%Y-%m') as month_value")
            ->pluck('month_value');

        $izinMonths = Izin::query()
            ->whereIn('user_id', $teacherIds)
            ->selectRaw("DISTINCT DATE_FORMAT(tanggal, '%Y-%m') as month_value")
            ->pluck('month_value');

        $months = $presensiMonths
            ->concat($izinMonths)
            ->filter(fn ($month) => is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month))
            ->unique()
            ->sortDesc()
            ->values()
            ->map(fn ($month) => [
                'value' => $month,
                'label' => Carbon::createFromFormat('Y-m', $month, 'Asia/Jakarta')->translatedFormat('F Y'),
            ]);

        if ($months->isEmpty()) {
            return collect([[
                'value' => $today->format('Y-m'),
                'label' => $today->translatedFormat('F Y'),
            ]]);
        }

        return $months;
    }

    private function buildAttendanceSummary(int $userId, ?string $hariKbm, Carbon $startDate, Carbon $endDate, Carbon $today): array
    {
        $effectiveEndDate = $endDate->copy()->min($today);

        if ($effectiveEndDate->lt($startDate)) {
            return $this->emptyAttendanceSummary($startDate, $endDate);
        }

        $summaryUser = User::with('madrasah')->find($userId);
        if ($summaryUser) {
            ApprovedIzinSyncService::syncApprovedIzinPresensiInRange($summaryUser, $startDate, $effectiveEndDate);
        }

        $presensiByDate = Presensi::query()
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal->toDateString());

        $details = collect();
        $totalHariKerja = 0;
        $totalHadir = 0;
        $totalIzinApproved = 0;
        $breakdown = [
            'hari_kerja' => [],
            'hadir' => [],
            'izin' => [],
            'belum_hadir' => [],
        ];

        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            if (!$this->isWorkingDay($date, $hariKbm)) {
                continue;
            }

            $records = $presensiByDate->get($date->toDateString(), collect());
            $hadirRecords = $records->where('status', 'hadir');
            $izinRecords = $records->where('status', 'izin');
            $izinApprovedRecords = $izinRecords->where('status_izin', 'approved');
            $alphaRecords = $records->where('status', 'alpha');

            $isHadir = $hadirRecords->isNotEmpty();
            $isIzinApproved = !$isHadir && $izinApprovedRecords->isNotEmpty();
            $externalTeachingIzin = (!$isHadir && !$isIzinApproved && $summaryUser)
                ? ExternalTeachingPermissionService::approvedRequestForDate($summaryUser, $date)
                : null;
            $statusLabel = $isHadir
                ? 'Hadir'
                : (($isIzinApproved || $externalTeachingIzin)
                    ? 'Izin Disetujui'
                    : ($izinRecords->isNotEmpty() ? 'Izin Belum Disetujui' : ($alphaRecords->isNotEmpty() ? 'Alpha' : 'Belum Presensi')));
            $keterangan = $externalTeachingIzin
                ? ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI
                : $records->pluck('keterangan')->filter()->implode(' | ');

            $details->push([
                'tanggal' => $date->copy(),
                'hari' => $date->locale('id')->dayName,
                'status' => $statusLabel,
                'is_hadir' => $isHadir,
                'is_izin' => $isIzinApproved || (bool) $externalTeachingIzin,
                'keterangan' => $keterangan,
            ]);

            $breakdownItem = [
                'tanggal' => $date->translatedFormat('d M Y'),
                'hari' => ucfirst($date->locale('id')->dayName),
                'status' => $statusLabel,
                'keterangan' => $keterangan,
            ];

            $totalHariKerja++;
            $breakdown['hari_kerja'][] = $breakdownItem;
            if ($isHadir) {
                $totalHadir++;
                $breakdown['hadir'][] = $breakdownItem;
            } elseif ($isIzinApproved || $externalTeachingIzin) {
                $totalIzinApproved++;
                $breakdown['izin'][] = $breakdownItem;
            } else {
                $breakdown['belum_hadir'][] = $breakdownItem;
            }
        }

        $totalDasarPersentase = max($totalHariKerja - $totalIzinApproved, 0);

        return [
            'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $effectiveEndDate->translatedFormat('d M Y'),
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzinApproved,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir - $totalIzinApproved, 0),
            'persentase_kehadiran' => $totalDasarPersentase > 0 ? round(($totalHadir / $totalDasarPersentase) * 100, 1) : 0,
            'details' => $details,
            'breakdown' => $breakdown,
        ];
    }

    private function buildSchoolTeacherSummaries($teacherOptions, ?string $hariKbm, Carbon $selectedWeek, Carbon $selectedMonth, Carbon $today)
    {
        return $teacherOptions
            ->map(function (User $teacher) use ($hariKbm, $selectedWeek, $selectedMonth, $today) {
                return [
                    'teacher' => $teacher,
                    'weekly' => $this->buildAttendanceSummary(
                        $teacher->id,
                        $hariKbm,
                        $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
                        $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY),
                        $today
                    ),
                    'monthly' => $this->buildAttendanceSummary(
                        $teacher->id,
                        $hariKbm,
                        $selectedMonth->copy()->startOfMonth(),
                        $selectedMonth->copy()->endOfMonth(),
                        $today
                    ),
                ];
            })
            ->values();
    }

    private function buildSchoolAttendanceOverview($schoolTeacherSummaries): array
    {
        $teacherCount = $schoolTeacherSummaries->count();

        return [
            'teacher_count' => $teacherCount,
            'weekly_average' => $teacherCount > 0
                ? round($schoolTeacherSummaries->avg(fn ($item) => $item['weekly']['persentase_kehadiran']), 1)
                : 0,
            'monthly_average' => $teacherCount > 0
                ? round($schoolTeacherSummaries->avg(fn ($item) => $item['monthly']['persentase_kehadiran']), 1)
                : 0,
            'weekly_hadir_total' => $schoolTeacherSummaries->sum(fn ($item) => $item['weekly']['total_hadir']),
            'monthly_hadir_total' => $schoolTeacherSummaries->sum(fn ($item) => $item['monthly']['total_hadir']),
            'weekly_belum_total' => $schoolTeacherSummaries->sum(fn ($item) => $item['weekly']['total_belum_hadir']),
            'monthly_belum_total' => $schoolTeacherSummaries->sum(fn ($item) => $item['monthly']['total_belum_hadir']),
        ];
    }

    private function emptyAttendanceSummary(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y'),
            'total_hari_kerja' => 0,
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_belum_hadir' => 0,
            'persentase_kehadiran' => 0,
            'details' => collect(),
            'breakdown' => [
                'hari_kerja' => [],
                'hadir' => [],
                'izin' => [],
                'belum_hadir' => [],
            ],
        ];
    }

    private function isWorkingDay(Carbon $date, ?string $hariKbm): bool
    {
        if ($date->isSunday() || Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) $hariKbm === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }

    private function canAccessAttendancePercentageReport($user): bool
    {
        return $user->role === 'tenaga_pendidik'
            && $user->ketugasan === 'kepala madrasah/sekolah';
    }
}
