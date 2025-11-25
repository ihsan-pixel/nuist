<?php

namespace App\Http\Controllers\Mobile\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\TeachingSchedule;
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

        // Fetch madrasah hari_kbm setting for user
        $hariKbm = $user->madrasah ? $user->madrasah->hari_kbm : '5'; // Default to 5 if not set

        // Calculate working days between startDate and today based on hari_kbm excluding holidays
        $workingDays = 0;
        $datePeriod = new \DatePeriod($startDate, new \DateInterval('P1D'), $today->addDay());

        foreach ($datePeriod as $date) {
            $dayOfWeek = $date->dayOfWeek; // 0=Sunday,...6=Saturday
            // Skip holiday
            if (\App\Models\Holiday::isHoliday($date->toDateString())) {
                continue;
            }
            // Check hari kbm
            if ($hariKbm == '5' && in_array($dayOfWeek, [1, 2, 3, 4, 5])) {
                $workingDays++;
            } elseif ($hariKbm == '6' && in_array($dayOfWeek, [1, 2, 3, 4, 5, 6])) {
                $workingDays++;
            }
        }

        // Aggregate presensi counts for the user between startDate and today with status group
        $presensiCounts = Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $today])
            ->get()
            ->groupBy('status')
            ->map->count();

        $hadir = $presensiCounts['hadir'] ?? 0;
        $izin = $presensiCounts['izin'] ?? 0;
        $sakit = $presensiCounts['sakit'] ?? 0;
        $alpha = $presensiCounts['alpha'] ?? 0;

        // Adjust alpha by considering only alpha presensi on working days and users who have at least one presensi
        if ($hadir + $izin + $sakit + $alpha > 0) {
            // But alpha should not exceed workingDays - hadir - izin - sakit
            $alpha = min($alpha, $workingDays - $hadir - $izin - $sakit);
            $totalBasis = $workingDays > 0 ? $workingDays : 1;
        } else {
            // User has no presensi record yet, alpha count should be zero and total basis accordingly
            $alpha = 0;
            $totalBasis = 0;
        }

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

        return view('mobile.dashboard', compact('kehadiranPercent', 'totalBasis', 'izin', 'alpha', 'userInfo', 'todaySchedules', 'bannerImage'));
    }
}
