<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        // Get user information for display
        $userInfo = [
            'nuist_id' => $user->nuist_id ?? '-',
            'status_kepegawaian' => $user->statusKepegawaian ? $user->statusKepegawaian->name : '-',
            'ketugasan' => $user->ketugasan ?? '-',
            'tempat_lahir' => $user->tempat_lahir ?? '-',
            'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-',
            'tmt' => $user->tmt ? Carbon::parse($user->tmt)->format('d F Y') : '-',
            'nuptk' => $user->nuptk ?? '-',
            'npk' => $user->npk ?? '-',
            'kartanu' => $user->kartanu ?? '-',
            'nip' => $user->nip ?? '-',
            'pendidikan_terakhir' => $user->pendidikan_terakhir ?? '-',
            'program_studi' => $user->program_studi ?? '-',
        ];

        return view('mobile.dashboard', compact(
            'kehadiranPercent',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'totalBasis',
            'todayPresensi',
            'todaySchedules',
            'userInfo'
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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed' => true,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
        }

        return back()->with('success', 'Foto profil berhasil diubah.');
    }

    public function laporan()
    {
        $user = Auth::user();
        // Add logic for laporan if needed
        return view('mobile.laporan', compact('user'));
    }

    public function izin()
    {
        $user = Auth::user();
        // Add logic for izin if needed
        return view('mobile.izin', compact('user'));
    }

    public function pengaturan()
    {
        $user = Auth::user();
        // Add logic for pengaturan if needed
        return view('mobile.pengaturan', compact('user'));
    }

    public function storePresensi(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        // Cek apakah sudah presensi hari ini
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            // Presensi masuk - langsung simpan tanpa validasi
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu_masuk' => $now,
                'latitude' => $request->latitude ?? 0,
                'longitude' => $request->longitude ?? 0,
                'lokasi' => $request->lokasi ?? 'Lokasi tidak diketahui',
                'accuracy' => $request->accuracy ?? 5.0,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info ?? 'Mobile App',
                'status' => 'hadir',
                'keterangan' => 'Presensi mobile',
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi masuk berhasil dicatat.',
                'data' => $presensi
            ]);
        } else {
            if ($presensi->status === 'alpha') {
                // Update alpha to hadir
                $presensi->update([
                    'status' => 'hadir',
                    'waktu_masuk' => $now,
                    'latitude' => $request->latitude ?? 0,
                    'longitude' => $request->longitude ?? 0,
                    'lokasi' => $request->lokasi ?? 'Lokasi tidak diketahui',
                    'accuracy' => $request->accuracy ?? 5.0,
                    'altitude' => $request->altitude,
                    'speed' => $request->speed,
                    'device_info' => $request->device_info ?? 'Mobile App',
                    'keterangan' => 'Presensi mobile',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Presensi masuk berhasil dicatat.',
                    'data' => $presensi
                ]);
            } else {
                // Presensi keluar - langsung update tanpa validasi
                $presensi->update([
                    'waktu_keluar' => $now,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Presensi keluar berhasil dicatat.',
                    'data' => $presensi
                ]);
            }
        }
    }



    /**
     * Get presensi time ranges based on madrasah hari_kbm and current day.
     * @param string|null $hariKbm
     * @param string $today
     * @return array
     */
    private function getPresensiTimeRanges($hariKbm, $today)
    {
        $dayOfWeek = Carbon::parse($today)->dayOfWeek; // 0=Sunday, 5=Friday

        if ($hariKbm == '5') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30'; // Friday starts at 14:00
            $pulangEnd = '17:00';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 6) ? '12:00' : '13:00'; // Saturday starts at 12:00, other days at 13:00
            $pulangEnd = '17:00';
        } else {
            // Default or fallback
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '13:00';
            $pulangEnd = '17:00';
        }

        return [
            'masuk_start' => $masukStart,
            'masuk_end' => $masukEnd,
            'pulang_start' => $pulangStart,
            'pulang_end' => $pulangEnd,
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Checks if a point is inside a polygon using the ray-casting algorithm.
     * @param array $point The point to check, in [longitude, latitude] format.
     * @param array $polygon An array of polygon vertices, each in [longitude, latitude] format.
     * @return bool True if the point is inside the polygon, false otherwise.
     */
    private function isPointInPolygon(array $point, array $polygon): bool
    {
        $pointLng = $point[0];
        $pointLat = $point[1];
        $isInside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $j = $i++) {
            $vertexiLat = $polygon[$i][1];
            $vertexiLng = $polygon[$i][0];
            $vertexjLat = $polygon[$j][1];
            $vertexjLng = $polygon[$j][0];

            // This is the core of the ray-casting algorithm
            if ((($vertexiLat > $pointLat) != ($vertexjLat > $pointLat)) &&
                ($pointLng < ($vertexjLng - $vertexiLng) * ($pointLat - $vertexiLat) / ($vertexjLat - $vertexiLat) + $vertexiLng)) {
                $isInside = !$isInside;
            }
        }

        return $isInside;
    }
}
