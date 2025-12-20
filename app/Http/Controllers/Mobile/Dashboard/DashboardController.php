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

        // Initialize variables
        $teachingSteps = [];

        // Get banner image from app settings
        $appSettings = AppSetting::getSettings();
        $bannerImage = $appSettings->banner_image_url;

        // Check if banner should be shown (only once per session)
        $showBanner = false;
        if ($bannerImage && !session('banner_shown')) {
            $showBanner = true;
            session(['banner_shown' => true]);
        }

        // Get selected month and year for stats and calendar (default to current)
        $currentMonth = $request->get('month', Carbon::now()->month);
        $currentYear = $request->get('year', Carbon::now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(2020, min(2030, (int)$currentYear));

        // Calculate stats based on calendar logic
        $hariKbm = $user->madrasah->hari_kbm ?? 6;
        $monthlyHolidays = Holiday::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('is_active', true)
            ->pluck('date')
            ->toArray();

        // Get all presensi for the month
        $monthlyPresensi = Presensi::where('user_id', $user->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->pluck('status', 'tanggal')
            ->toArray();

        $hadir = 0;
        $izin = 0;
        $alpha = 0;
        $workingDays = 0;
        $daysInMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;
        $today = Carbon::now();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            $dateKey = $date->toDateString();
            $dayOfWeek = $date->dayOfWeek; // 0=Sunday, 6=Saturday
            $isWorkingDay = true;
            if ($hariKbm == 5 && ($dayOfWeek == 6 || $dayOfWeek == 0)) {
                $isWorkingDay = false;
            } elseif ($hariKbm == 6 && $dayOfWeek == 0) {
                $isWorkingDay = false;
            }
            $isHoliday = in_array($dateKey, $monthlyHolidays);
            $presensiStatus = $monthlyPresensi[$dateKey] ?? null;

            if ($isWorkingDay && !$isHoliday) {
                $workingDays++;
                if ($presensiStatus === 'hadir') {
                    $hadir++;
                } elseif (in_array($presensiStatus, ['izin', 'sakit'])) {
                    $izin++;
                } elseif ($presensiStatus === 'alpha' || (!$presensiStatus && $date->isBefore($today->startOfDay()))) {
                    $alpha++;
                }
            }
        }

        $totalBasis = $workingDays;
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

        // Calculate daily performance activities
        $todayDate = $today->toDateString();

        // Check presensi masuk (ada waktu_masuk)
        $presensiMasuk = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->whereNotNull('waktu_masuk')
            ->first();
        $presensiMasukStatus = $presensiMasuk ? 'sudah' : 'belum';

        // Check presensi mengajar (berdasarkan jadwal dan attendance)
        $presensiMengajarStatus = 'tidak_ada_jadwal';
        if ($todaySchedulesWithAttendance->count() > 0) {
            $totalSchedules = $todaySchedulesWithAttendance->count();
            $completedSchedules = $todaySchedulesWithAttendance->where('attendance_status', 'sudah')->count();
            $presensiMengajarStatus = $completedSchedules === $totalSchedules ? 'sudah' : 'belum';
        }

        // Check presensi keluar (ada waktu_keluar)
        $presensiKeluar = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->whereNotNull('waktu_keluar')
            ->first();
        $presensiKeluarStatus = $presensiKeluar ? 'sudah' : 'belum';

        // Calculate percentage (33.33% per activity, max 100%)
        $completedActivities = 0;
        if ($presensiMasukStatus === 'sudah') $completedActivities++;
        if ($presensiMengajarStatus === 'sudah') $completedActivities++;
        if ($presensiKeluarStatus === 'sudah') $completedActivities++;
        $kinerjaPercent = round(($completedActivities / 3) * 100);

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
            'kehadiranPercent', 'hadir', 'totalBasis', 'izin', 'alpha', 'userInfo', 'todaySchedulesWithAttendance',
            'bannerImage', 'showBanner', 'monthlyPresensi', 'currentMonth', 'currentYear',
            'hariKbm', 'monthlyHolidays', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear',
            'presensiMasukStatus', 'presensiMengajarStatus', 'presensiKeluarStatus', 'kinerjaPercent', 'teachingSteps'
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

    // AJAX endpoint for stats data
    public function getStatsData(Request $request)
    {
        $user = Auth::user();

        // Get selected month and year for stats (default to current)
        $currentMonth = $request->get('month', Carbon::now()->month);
        $currentYear = $request->get('year', Carbon::now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(2020, min(2030, (int)$currentYear));

        // Calculate stats based on calendar logic
        $hariKbm = $user->madrasah->hari_kbm ?? 6;
        $monthlyHolidays = Holiday::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('is_active', true)
            ->pluck('date')
            ->toArray();

        // Get all presensi for the month
        $monthlyPresensi = Presensi::where('user_id', $user->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->pluck('status', 'tanggal')
            ->toArray();

        $hadir = 0;
        $izin = 0;
        $alpha = 0;
        $workingDays = 0;
        $daysInMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;
        $today = Carbon::now();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            $dateKey = $date->toDateString();
            $dayOfWeek = $date->dayOfWeek; // 0=Sunday, 6=Saturday
            $isWorkingDay = true;
            if ($hariKbm == 5 && ($dayOfWeek == 6 || $dayOfWeek == 0)) {
                $isWorkingDay = false;
            } elseif ($hariKbm == 6 && $dayOfWeek == 0) {
                $isWorkingDay = false;
            }
            $isHoliday = in_array($dateKey, $monthlyHolidays);
            $presensiStatus = $monthlyPresensi[$dateKey] ?? null;

            if ($isWorkingDay && !$isHoliday) {
                $workingDays++;
                if ($presensiStatus === 'hadir') {
                    $hadir++;
                } elseif (in_array($presensiStatus, ['izin', 'sakit'])) {
                    $izin++;
                } elseif ($presensiStatus === 'alpha' || (!$presensiStatus && $date->isBefore($today->startOfDay()))) {
                    $alpha++;
                }
            }
        }

        $totalBasis = $workingDays;
        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 2) : 0;

        return response()->json([
            'kehadiranPercent' => $kehadiranPercent,
            'hadir' => $hadir,
            'totalBasis' => $totalBasis,
            'izin' => $izin,
            'alpha' => $alpha,
        ]);
    }
}
