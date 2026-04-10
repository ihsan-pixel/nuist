<?php

namespace App\Http\Controllers\Mobile\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Holiday;
use App\Models\Presensi;
use App\Models\TeachingAttendance;

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

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
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
            $user->id,
            $user->madrasah?->hari_kbm,
            $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
            $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY),
            $today
        );

        $monthlySummary = $this->buildAttendanceSummary(
            $user->id,
            $user->madrasah?->hari_kbm,
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth(),
            $today
        );

        return view('mobile.laporan-persentase-kehadiran', [
            'selectedWeekValue' => $selectedWeek->format('o-\WW'),
            'selectedMonthValue' => $selectedMonth->format('Y-m'),
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

        if ($effectiveEndDate->lt($startDate)) {
            return [
                'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y'),
                'total_hari_kerja' => 0,
                'total_hadir' => 0,
                'total_belum_hadir' => 0,
                'persentase_kehadiran' => 0,
                'details' => collect(),
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

        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            if (!$this->isWorkingDay($date, $hariKbm)) {
                continue;
            }

            $records = $presensiByDate->get($date->toDateString(), collect());
            $hadirRecords = $records->where('status', 'hadir');
            $izinRecords = $records->where('status', 'izin');
            $alphaRecords = $records->where('status', 'alpha');

            $isHadir = $hadirRecords->isNotEmpty();
            $statusLabel = $isHadir
                ? 'Hadir'
                : ($izinRecords->isNotEmpty() ? 'Izin' : ($alphaRecords->isNotEmpty() ? 'Alpha' : 'Belum Presensi'));

            $details->push([
                'tanggal' => $date->copy(),
                'hari' => $date->locale('id')->dayName,
                'status' => $statusLabel,
                'is_hadir' => $isHadir,
                'keterangan' => $records->pluck('keterangan')->filter()->implode(' | '),
            ]);

            $totalHariKerja++;
            if ($isHadir) {
                $totalHadir++;
            }
        }

        return [
            'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $effectiveEndDate->translatedFormat('d M Y'),
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir, 0),
            'persentase_kehadiran' => $totalHariKerja > 0 ? round(($totalHadir / $totalHariKerja) * 100, 1) : 0,
            'details' => $details,
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
}
