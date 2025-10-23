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
            'totalBasis',
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

        // Cek apakah hari ini libur
        $isHoliday = \App\Models\Holiday::where('date', $today)
            ->where('is_active', true)
            ->exists();

        // Ambil pengaturan waktu berdasarkan hari_kbm madrasah user
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $timeRanges = $this->getPresensiTimeRanges($user->madrasah->hari_kbm, $today);
        }

        return view('mobile.presensi', compact('presensiHariIni', 'isHoliday', 'timeRanges'));
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

    public function storePresensi(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        // Cek apakah hari ini libur
        $isHoliday = \App\Models\Holiday::where('date', $today)
            ->where('is_active', true)
            ->exists();

        if ($isHoliday) {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini adalah hari libur, presensi tidak dapat dilakukan.'
            ]);
        }

        // Cek presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Validasi lokasi
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if (!$latitude || !$longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid. Pastikan GPS aktif.'
            ]);
        }

        // Cek jarak dari madrasah (dalam meter)
        if ($user->madrasah && $user->madrasah->latitude && $user->madrasah->longitude) {
            $distance = $this->calculateDistance(
                $latitude, $longitude,
                $user->madrasah->latitude, $user->madrasah->longitude
            );

            // Jika jarak lebih dari 500 meter, tolak presensi
            if ($distance > 500) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar jangkauan madrasah. Presensi hanya dapat dilakukan dalam radius 500 meter dari lokasi madrasah.'
                ]);
            }
        }

        if (!$presensiHariIni) {
            // Presensi masuk
            $presensi = new Presensi();
            $presensi->user_id = $user->id;
            $presensi->tanggal = $today;
            $presensi->waktu_masuk = $now;
            $presensi->status = 'hadir';
            $presensi->latitude_masuk = $latitude;
            $presensi->longitude_masuk = $longitude;
            $presensi->lokasi_masuk = $request->lokasi;
            $presensi->device_info = $request->device_info;
            $presensi->location_readings = $request->location_readings;
            $presensi->save();

            return response()->json([
                'success' => true,
                'message' => 'Presensi masuk berhasil dicatat pada ' . $now->format('H:i:s')
            ]);
        } else {
            // Presensi keluar
            if ($presensiHariIni->waktu_keluar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi keluar sudah dicatat hari ini.'
                ]);
            }

            $presensiHariIni->waktu_keluar = $now;
            $presensiHariIni->latitude_keluar = $latitude;
            $presensiHariIni->longitude_keluar = $longitude;
            $presensiHariIni->lokasi_keluar = $request->lokasi;
            $presensiHariIni->save();

            return response()->json([
                'success' => true,
                'message' => 'Presensi keluar berhasil dicatat pada ' . $now->format('H:i:s')
            ]);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
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
