<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\TeachingSchedule;
use Carbon\Carbon;

class MobileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung data presensi bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $firstPresensiDate = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = Carbon::parse($firstPresensiDate);
        } else {
            $startDate = Carbon::parse($user->created_at);
        }

        $today = now();
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

        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 1) : 0;

        // Cek presensi hari ini
        $todayPresensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today->toDateString())
            ->first();

        // Jadwal hari ini
        $todaySchedules = TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->where('day', $today->locale('id')->dayName)
            ->orderBy('start_time')
            ->get();

        return view('mobile.dashboard', compact(
            'kehadiranPercent',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'todayPresensi',
            'todaySchedules'
        ));
    }

    public function presensi()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Cek apakah sudah presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Ambil pengaturan waktu berdasarkan hari_kbm madrasah user
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $timeRanges = $this->getPresensiTimeRanges($user->madrasah->hari_kbm, $today);
        }

        return view('mobile.presensi', compact('presensiHariIni', 'timeRanges'));
    }

    public function jadwal()
    {
        $user = Auth::user();

        // Group jadwal by day
        $schedules = TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('mobile.jadwal', compact('schedules'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('mobile.profile', compact('user'));
    }

    private function getPresensiTimeRanges($hariKbm, $date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        // Map hari_kbm to time ranges
        $timeRanges = [
            'senin_jumat' => [
                'masuk' => ['start' => '07:00', 'end' => '08:00'],
                'pulang' => ['start' => '14:00', 'end' => '15:00']
            ],
            'senin_sabtu' => [
                'masuk' => ['start' => '07:00', 'end' => '08:00'],
                'pulang' => ['start' => '11:00', 'end' => '12:00']
            ]
        ];

        return $timeRanges[$hariKbm] ?? $timeRanges['senin_jumat'];
    }
}
