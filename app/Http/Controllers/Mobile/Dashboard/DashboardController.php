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

class DashboardController extends \App\Http\Controllers\Controller
{
    // Mobile dashboard for tenaga_pendidik
    public function dashboard()
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

        // Get presensi data for current month for calendar
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyPresensi = Presensi::where('user_id', $user->id)
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->pluck('status', 'tanggal')
            ->toArray();

        return view('mobile.dashboard', compact('kehadiranPercent', 'totalBasis', 'izin', 'alpha', 'userInfo', 'todaySchedulesWithAttendance', 'bannerImage', 'showBanner', 'monthlyPresensi', 'currentMonth', 'currentYear'));
    }
}
