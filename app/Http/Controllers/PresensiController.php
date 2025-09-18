<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Presensi::with(['user.madrasah']);

        if ($user->role === 'tenaga_pendidik') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
        }

        $presensis = $query->latest('tanggal')->paginate(10);

        return view('presensi.index', compact('presensis'));
    }


    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if today is a holiday
        $isHoliday = \App\Models\Holiday::isHoliday($today);
        $holiday = null;
        if ($isHoliday) {
            $holiday = \App\Models\Holiday::getHoliday($today);
        }

        // Cek apakah sudah presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        return view('presensi.create', compact('presensiHariIni', 'isHoliday', 'holiday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah sudah presensi hari ini
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Ambil koordinat madrasah dari data user yang sedang login
        $user = Auth::user();
        $madrasah = $user->madrasah;

        if (!$madrasah || !$madrasah->latitude || !$madrasah->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi madrasah belum diatur. Silakan hubungi administrator.'
            ], 400);
        }

        // Gunakan koordinat madrasah yang sudah diatur
        $madrasahLat = $madrasah->latitude;
        $madrasahLng = $madrasah->longitude;

        // Ambil pengaturan presensi
        $settings = \App\Models\PresensiSettings::first();
        $radius = $settings && $settings->radius_presensi ? $settings->radius_presensi : 100;
        $batasAkhirMasuk = $settings ? $settings->waktu_akhir_presensi_masuk : null;
        $batasPulang = $settings ? $settings->waktu_mulai_presensi_pulang : null;

        // Hitung jarak menggunakan formula Haversine
        $distance = $this->calculateDistance($request->latitude, $request->longitude, $madrasahLat, $madrasahLng);

        if ($distance > $radius) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar radius ' . $radius . ' meter dari madrasah.'
            ], 400);
        }

        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if (!$presensi) {
            // Validasi batas akhir presensi masuk
            if ($batasAkhirMasuk && $now > $batasAkhirMasuk) {
                // Hitung keterlambatan dalam menit
                $batas = Carbon::createFromFormat('H:i:s', $batasAkhirMasuk, 'Asia/Jakarta');
                $sekarang = Carbon::now('Asia/Jakarta');
                $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

                // Pastikan keterlambatan tidak negatif dan bulatkan angkanya
                if ($sekarang->lessThan($batas)) {
                    $terlambatMenit = 0;
                } else {
                    $terlambatMenit = abs(round($terlambatMenit));
                }

                // Presensi masuk dengan keterangan keterlambatan
                $waktuMasuk = $request->input('waktu_masuk') ?? $now;
                $presensi = Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'waktu_masuk' => $waktuMasuk,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'status' => 'hadir',
                    'keterangan' => "Terlambat {$terlambatMenit} menit",
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Presensi masuk berhasil dicatat dengan keterlambatan {$terlambatMenit} menit.",
                    'data' => $presensi
                ]);
            }

            // Presensi masuk
            $waktuMasuk = $request->input('waktu_masuk') ?? $now;
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu_masuk' => $waktuMasuk,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'status' => 'hadir',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi masuk berhasil dicatat.',
                'data' => $presensi
            ]);
        } else {
            if ($presensi->status === 'alpha') {
                // Update alpha to hadir, set waktu_masuk
                $waktuMasuk = $request->input('waktu_masuk') ?? $now;
                $presensi->update([
                    'status' => 'hadir',
                    'waktu_masuk' => $waktuMasuk,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'keterangan' => null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Presensi masuk berhasil dicatat.',
                    'data' => $presensi
                ]);
            } else {
                // Validasi batas diperbolehkan presensi pulang
                if ($batasPulang && $now < $batasPulang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya presensi pulang.'
                    ], 400);
                }

                // Presensi keluar
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

    public function laporan(Request $request)
    {
        $user = Auth::user();

        $query = Presensi::with('user.madrasah');

        // Filter berdasarkan role
        if ($user->role === 'tenaga_pendidik') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            // Admin bisa melihat presensi dari madrasah yang sama
            if ($user->madrasah_id) {
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                });
            }
        }
        // Super admin bisa melihat semua

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        // Filter berdasarkan madrasah
        if ($request->filled('madrasah_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('madrasah_id', $request->madrasah_id);
            });
        }

        $presensis = $query->orderBy('tanggal', 'desc')->paginate(15);
        $madrasahs = Madrasah::all();

        return view('presensi.laporan', compact('presensis', 'madrasahs'));
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
