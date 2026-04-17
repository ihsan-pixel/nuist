<?php

namespace App\Http\Controllers\Mobile\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Holiday;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\User;

class LaporanController extends \App\Http\Controllers\Controller
{
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

        $history = TeachingAttendance::with(['teachingSchedule.school'])
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.laporan-mengajar', compact('history'));
    }

    public function laporanPersentaseKehadiran(Request $request)
    {
        $user = Auth::user();

        if (!$this->canAccessAttendancePercentageReport($user)) {
            abort(403, 'Unauthorized.');
        }

        $teacherOptions = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->orderBy('name')
            ->get(['id', 'name', 'ketugasan']);

        $selectedTeacherId = (int) ($request->input('teacher_id') ?: $user->id);
        $selectedTeacher = $teacherOptions->firstWhere('id', $selectedTeacherId) ?? $teacherOptions->firstWhere('id', $user->id);

        if (!$selectedTeacher) {
            abort(404, 'Data tenaga pendidik tidak ditemukan.');
        }

        $today = Carbon::today('Asia/Jakarta');

        $selectedWeek = $request->filled('week') && preg_match('/^\d{4}-W\d{2}$/', $request->week)
            ? Carbon::now('Asia/Jakarta')->setISODate(
                (int) substr($request->week, 0, 4),
                (int) substr($request->week, 6, 2)
            )->startOfWeek(Carbon::MONDAY)
            : $today->copy()->startOfWeek(Carbon::MONDAY);

        $selectedMonth = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->month, 'Asia/Jakarta')->startOfMonth()
            : $today->copy()->startOfMonth();

        $weeklySummary = $this->buildAttendanceSummary(
            $selectedTeacher->id,
            $user->madrasah?->hari_kbm,
            $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
            $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY),
            $today
        );

        $monthlySummary = $this->buildAttendanceSummary(
            $selectedTeacher->id,
            $user->madrasah?->hari_kbm,
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth(),
            $today
        );

        return view('mobile.laporan-persentase-kehadiran', [
            'selectedWeekValue' => $selectedWeek->format('o-\WW'),
            'selectedMonthValue' => $selectedMonth->format('Y-m'),
            'teacherOptions' => $teacherOptions,
            'selectedTeacher' => $selectedTeacher,
            'weeklySummary' => $weeklySummary,
            'monthlySummary' => $monthlySummary,
        ]);
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

        // Build schedule query with today's teaching attendances, filtered by current day
        $query = \App\Models\TeachingSchedule::with(['teacher', 'school', 'teachingAttendances' => function ($q) use ($selectedDate) {
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
            $schedule->attendance = $schedule->teachingAttendances->first() ?? null;
        });

        $today = $selectedDate->toDateString();

        return view('mobile.teaching-attendances', compact('today', 'schedules'));
    }

    private function buildAttendanceSummary(int $userId, ?string $hariKbm, Carbon $startDate, Carbon $endDate, Carbon $today): array
    {
        $effectiveEndDate = $endDate->copy()->min($today);

        // If the requested period's end is after today, mark this summary as realtime
        // i.e. calculations are only up to today (month-to-date / week-to-date)
        $isRealtime = $endDate->gt($today);

        if ($effectiveEndDate->lt($startDate)) {
            $label = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
            if ($isRealtime) {
                $label .= ' (s.d. hari ini)';
            }

            return [
                'periode_label' => $label,
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
                'is_realtime' => $isRealtime,
            ];
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
            $statusLabel = $isHadir
                ? 'Hadir'
                : ($isIzinApproved
                    ? 'Izin Disetujui'
                    : ($izinRecords->isNotEmpty() ? 'Izin Belum Disetujui' : ($alphaRecords->isNotEmpty() ? 'Alpha' : 'Belum Presensi')));

            $details->push([
                'tanggal' => $date->copy(),
                'hari' => $date->locale('id')->dayName,
                'status' => $statusLabel,
                'is_hadir' => $isHadir,
                'is_izin' => $isIzinApproved,
                'keterangan' => $records->pluck('keterangan')->filter()->implode(' | '),
            ]);

            $breakdownItem = [
                'tanggal' => $date->translatedFormat('d M Y'),
                'hari' => ucfirst($date->locale('id')->dayName),
                'status' => $statusLabel,
                'keterangan' => $records->pluck('keterangan')->filter()->implode(' | '),
            ];

            $totalHariKerja++;
            $breakdown['hari_kerja'][] = $breakdownItem;
            if ($isHadir) {
                $totalHadir++;
                $breakdown['hadir'][] = $breakdownItem;
            } elseif ($isIzinApproved) {
                $totalIzinApproved++;
                $breakdown['izin'][] = $breakdownItem;
            } else {
                $breakdown['belum_hadir'][] = $breakdownItem;
            }
        }

        $totalDasarPersentase = max($totalHariKerja - $totalIzinApproved, 0);

        $periodeLabel = $startDate->translatedFormat('d M Y') . ' - ' . $effectiveEndDate->translatedFormat('d M Y');
        if ($isRealtime) {
            $periodeLabel .= ' (s.d. hari ini)';
        }

        return [
            'periode_label' => $periodeLabel,
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzinApproved,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir - $totalIzinApproved, 0),
            'persentase_kehadiran' => $totalDasarPersentase > 0 ? round(($totalHadir / $totalDasarPersentase) * 100, 1) : 0,
            'details' => $details,
            'breakdown' => $breakdown,
            'is_realtime' => $isRealtime,
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
