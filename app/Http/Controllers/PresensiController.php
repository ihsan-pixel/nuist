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

        // Check if tenaga_pendidik and password not changed
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi.');
        }

        $query = Presensi::with(['user.madrasah', 'statusKepegawaian']);

        if ($user->role === 'tenaga_pendidik') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
        }

        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            $presensis = $query->latest('tanggal')->get();
        } else {
            $presensis = $query->latest('tanggal')->paginate(10);
        }

        return view('presensi.index', compact('presensis'));
    }


    public function create()
    {
        $user = Auth::user();

        // Check if tenaga_pendidik and password not changed
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi.');
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();

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

        // Ambil pengaturan waktu berdasarkan hari_kbm madrasah user
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $timeRanges = $this->getPresensiTimeRanges($user->madrasah->hari_kbm, $today);
            // Adjust for special users
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                $timeRanges['masuk_end'] = '08:00';
            }
        }

        return view('presensi.create', compact('presensiHariIni', 'isHoliday', 'holiday', 'timeRanges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Cek apakah sudah presensi hari ini
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Ambil data madrasah dari user yang sedang login
        $madrasah = $user->madrasah;
        $madrasahTambahan = $user->madrasahTambahan;

        // Validasi lokasi user berada di dalam poligon madrasah utama atau tambahan jika berlaku
        $isWithinPolygon = false;
        $validMadrasah = null;

        $madrasahsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasah;
        }
        if ($user->pemenuhan_beban_kerja_lain && $madrasahTambahan && $madrasahTambahan->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasahTambahan;
        }

        // Jika madrasah utama mengaktifkan dual polygon, tambahkan polygon kedua
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $madrasahsToCheck[] = (object)['polygon_koordinat' => $madrasah->polygon_koordinat_2];
        }

        if (empty($madrasahsToCheck)) {
            return response()->json([
                'success' => false,
                'message' => 'Area presensi (poligon) untuk madrasah Anda belum diatur. Silakan hubungi administrator.'
            ], 400);
        }

        foreach ($madrasahsToCheck as $m) {
            try {
                $polygonGeometry = json_decode($m->polygon_koordinat, true);
                $polygon = $polygonGeometry['coordinates'][0];
                if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                    $isWithinPolygon = true;
                    $validMadrasah = $m;
                    break;
                }
            } catch (\Exception $e) {
                continue; // Skip invalid polygon
            }
        }

        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area presensi yang telah ditentukan.'
            ], 400);
        }

        // Jika user memiliki pemenuhan beban kerja lain, lewati validasi waktu
        if ($user->pemenuhan_beban_kerja_lain) {
            $batasAwalMasuk = null;
            $batasAkhirMasuk = null;
            $batasPulang = null;
        } else {
            // Ambil pengaturan waktu berdasarkan hari_kbm madrasah yang valid
            $hariKbm = $validMadrasah ? $validMadrasah->hari_kbm : null;
            $timeRanges = $this->getPresensiTimeRanges($hariKbm, $today);
            $batasAwalMasuk = $timeRanges['masuk_start'];
            $batasAkhirMasuk = $timeRanges['masuk_end'];
            $batasPulang = $timeRanges['pulang_start'];
            // Adjust for special users
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                $batasAkhirMasuk = '08:00';
            }
        }

        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if (!$presensi) {
            // Validasi batas awal presensi masuk
            if ($batasAwalMasuk && $now < $batasAwalMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum waktunya presensi masuk.'
                ], 400);
            }

            // Validasi batas akhir presensi masuk
            if ($batasAkhirMasuk && $now > $batasAkhirMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu presensi masuk telah berakhir.'
                ], 400);
            }

            $waktuMasuk = $request->input('waktu_masuk') ?? $now;
            $keterangan = null;

            // Jika waktu presensi setelah 07:00, hitung keterlambatan
            if ($now > '07:00:00') {
                $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
                $sekarang = Carbon::now('Asia/Jakarta');
                $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

                // Pastikan keterlambatan tidak negatif dan bulatkan angkanya
                if ($sekarang->lessThan($batas)) {
                    $terlambatMenit = 0;
                } else {
                    $terlambatMenit = abs(round($terlambatMenit));
                }

                $keterangan = "Terlambat {$terlambatMenit} menit";
            } else {
                $keterangan = "tidak terlambat";
            }

            // Presensi masuk
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu_masuk' => $waktuMasuk,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'status' => 'hadir',
                'keterangan' => $keterangan,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

            $message = $keterangan === "tidak terlambat" ? 'Presensi masuk berhasil dicatat.' : "Presensi masuk berhasil dicatat dengan {$keterangan}.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $presensi
            ]);
        } else {
            if ($presensi->status === 'alpha') {
                // Update alpha to hadir, set waktu_masuk
                $waktuMasuk = $request->input('waktu_masuk') ?? $now;
                $keterangan = null;

                // Jika waktu presensi setelah 07:00, hitung keterlambatan
                if ($now > '07:00:00') {
                    $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
                    $sekarang = Carbon::now('Asia/Jakarta');
                    $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

                    // Pastikan keterlambatan tidak negatif dan bulatkan angkanya
                    if ($sekarang->lessThan($batas)) {
                        $terlambatMenit = 0;
                    } else {
                        $terlambatMenit = abs(round($terlambatMenit));
                    }

                    $keterangan = "Terlambat {$terlambatMenit} menit";
                } else {
                    $keterangan = "tidak terlambat";
                }

                $presensi->update([
                    'status' => 'hadir',
                    'waktu_masuk' => $waktuMasuk,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'keterangan' => $keterangan,
                ]);

                $message = $keterangan === "tidak terlambat" ? 'Presensi masuk berhasil dicatat.' : "Presensi masuk berhasil dicatat dengan {$keterangan}.";

                return response()->json([
                    'success' => true,
                    'message' => $message,
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

                // Validasi tanggal presensi keluar harus sama dengan tanggal presensi masuk
                $tanggalSekarang = Carbon::now('Asia/Jakarta')->toDateString();
                if ($tanggalSekarang !== $presensi->tanggal->toDateString()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi keluar harus dilakukan pada tanggal yang sama dengan presensi masuk.'
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

        // Check if tenaga_pendidik and password not changed
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi.');
        }

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
        // Super admin dan pengurus bisa melihat semua

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
            $pulangStart = '13:00';
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
