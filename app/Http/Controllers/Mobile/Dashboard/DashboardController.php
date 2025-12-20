<?php

namespace App\Http\Controllers\Mobile\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Models\TeachingAttendance;
use App\Models\AppSetting;
use App\Models\Holiday;

class DashboardController extends \App\Http\Controllers\Controller
{
    // Mobile dashboard for tenaga_pendidik
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // simple role check (middleware already restricts but keep safe-guard)
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Get banner image from app settings
        $appSettings = AppSetting::getSettings();
        $bannerImage = $appSettings->banner_image_url;

        // Check if banner should be shown (only once per session)
        $showBanner = false;
        if ($bannerImage && !session('banner_shown')) {
            $showBanner = true;
            session(['banner_shown' => true]);
        }

        // Determine start date for attendance calculation (first presensi or account creation)
        $firstPresensiDate = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = Carbon::parse($firstPresensiDate);
        } else {
            $startDate = Carbon::parse($user->created_at);
        }

        $today = Carbon::now();

        // Aggregate presensi counts for the user between startDate and today
        $presensiCounts = Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $today])
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hadir = $presensiCounts['hadir'] ?? 0;
        $izin = $presensiCounts['izin'] ?? 0;
        $sakit = $presensiCounts['sakit'] ?? 0;
        $alpha = $presensiCounts['alpha'] ?? 0;

        $totalBasis = array_sum($presensiCounts);

        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 2) : 0;

        // Prepare user info array expected by the view
        $userInfo = [
            'nuist_id' => $user->nuist_id ?? '-',
            'status_kepegawaian' => $user->statusKepegawaian?->name ?? '-',
            'ketugasan' => $user->ketugasan ?? '-',
            'tempat_lahir' => $user->tempat_lahir ?? '-',
            'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-',
            'tmt' => $user->tmt ? Carbon::parse($user->tmt)->format('d-m-Y') : '-',
            'nuptk' => $user->nuptk ?? '-',
            'npk' => $user->npk ?? '-',
            'kartanu' => $user->kartanu ?? '-',
            'nip' => $user->nip ?? '-',
            'pendidikan_terakhir' => $user->pendidikan_terakhir ?? '-',
            'program_studi' => $user->program_studi ?? '-',
        ];

        // Today's schedules for the teacher
        $todayName = Carbon::parse($today)->locale('id')->dayName; // e.g., 'Senin'
        $todaySchedules = TeachingSchedule::where('teacher_id', $user->id)
            ->whereRaw('LOWER(day) = ?', [strtolower($todayName)])
            ->orderBy('start_time')
            ->get();

        // Add attendance status to each schedule
        $todaySchedulesWithAttendance = $todaySchedules->map(function ($schedule) use ($today) {
            $attendance = TeachingAttendance::where('teaching_schedule_id', $schedule->id)
                ->where('tanggal', $today->toDateString())
                ->first();
            $schedule->attendance_status = $attendance ? 'sudah' : 'belum';
            return $schedule;
        });

        // Get selected month and year for calendar (default to current)
        $currentMonth = $request->get('month', Carbon::now()->month);
        $currentYear = $request->get('year', Carbon::now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(2020, min(2030, (int)$currentYear));

        // Get presensi data for selected month for calendar
        $monthlyPresensi = Presensi::where('user_id', $user->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->pluck('status', 'tanggal')
            ->toArray();

        // Get hari KBM setting from user's madrasah
        $hariKbm = $user->madrasah->hari_kbm ?? 6;

        // Get holidays for selected month
        $monthlyHolidays = Holiday::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('is_active', true)
            ->pluck('name', 'date')
            ->toArray();

        // Calculate previous and next month
        $prevMonth = $currentMonth - 1;
        $prevYear = $currentYear;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $currentYear - 1;
        }

        $nextMonth = $currentMonth + 1;
        $nextYear = $currentYear;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $currentYear + 1;
        }

        return view('mobile.dashboard', compact(
            'kehadiranPercent', 'totalBasis', 'izin', 'alpha', 'userInfo', 'todaySchedulesWithAttendance',
            'bannerImage', 'showBanner', 'monthlyPresensi', 'currentMonth', 'currentYear',
            'hariKbm', 'monthlyHolidays', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    // AJAX endpoint for calendar navigation
    public function getCalendarData(Request $request)
    {
        $user = Auth::user();

        // Get selected month and year for calendar
        $currentMonth = $request->get('month', Carbon::now()->month);
        $currentYear = $request->get('year', Carbon::now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(2020, min(2030, (int)$currentYear));

        // Get presensi data for selected month for calendar
        $monthlyPresensi = Presensi::where('user_id', $user->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->pluck('status', 'tanggal')
            ->toArray();

        // Get hari KBM setting from user's madrasah
        $hariKbm = $user->madrasah->hari_kbm ?? 6;

        // Get holidays for selected month
        $monthlyHolidays = Holiday::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('is_active', true)
            ->pluck('name', 'date')
            ->toArray();

        // Calculate previous and next month
        $prevMonth = $currentMonth - 1;
        $prevYear = $currentYear;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $currentYear - 1;
        }

        $nextMonth = $currentMonth + 1;
        $nextYear = $currentYear;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $currentYear + 1;
        }

        return response()->json([
            'monthlyPresensi' => $monthlyPresensi,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'hariKbm' => $hariKbm,
            'monthlyHolidays' => $monthlyHolidays,
            'prevMonth' => $prevMonth,
            'prevYear' => $prevYear,
            'nextMonth' => $nextMonth,
            'nextYear' => $nextYear,
        ]);
    }
}
