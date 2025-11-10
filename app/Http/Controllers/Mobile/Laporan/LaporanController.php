<?php

namespace App\Http\Controllers\Mobile\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
}
