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
                $timeRanges['masuk_end'] = '2:00';
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
            'accuracy' => 'nullable|numeric|min:0|max:999.99',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric|min:0',
            'device_info' => 'nullable|string|max:255',
            'location_readings' => 'nullable|string',
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

        // Initialize variables
        $isFakeLocation = false;
        $fakeLocationAnalysis = [];

        // Deteksi fake GPS dengan analisis 2 koordinat yang sama persis
        if ($request->location_readings) {
            $locationReadings = json_decode($request->location_readings, true);

            // Jika ada 2 readings, cek apakah koordinat sama persis
            if (count($locationReadings) == 2) {
                $reading1 = $locationReadings[0];
                $reading2 = $locationReadings[1];

                // Cek apakah latitude dan longitude sama persis
                $isIdentical = (
                    abs($reading1['latitude'] - $reading2['latitude']) < 0.000001 &&
                    abs($reading1['longitude'] - $reading2['longitude']) < 0.000001
                );

                if ($isIdentical) {
                    \Log::warning('Fake GPS detected - identical coordinates in 2 readings', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'reading1' => $reading1,
                        'reading2' => $reading2,
                        'time_diff' => $reading2['timestamp'] - $reading1['timestamp']
                    ]);

                    // Jika terdeteksi fake GPS, tetap izinkan presensi tapi tandai sebagai fake location
                    $isFakeLocation = true;
                    $fakeLocationAnalysis = array_merge($fakeLocationAnalysis, [
                        'fake_gps_detected' => true,
                        'fake_gps_reason' => 'Koordinat latitude dan longitude sama persis dalam 2 pengukuran',
                        'readings' => $locationReadings
                    ]);
                }
            }
        }

        // Analisis fake location sebelum validasi poligon
        $additionalFakeLocationAnalysis = $this->analyzeFakeLocation($request, $user, $madrasah, $madrasahTambahan);
        $isFakeLocation = $isFakeLocation || $additionalFakeLocationAnalysis['is_fake'];
        $fakeLocationAnalysis = array_merge($fakeLocationAnalysis, $additionalFakeLocationAnalysis);

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

        // Jika terdeteksi fake location, tetap izinkan presensi tapi log untuk monitoring
        // Super admin dapat memeriksa di menu deteksi fake location
        if ($isFakeLocation) {
            \Log::warning('Fake location detected - presensi allowed but flagged', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'madrasah' => $madrasah ? $madrasah->name : 'N/A',
                'analysis' => $fakeLocationAnalysis
            ]);
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
                $batasAkhirMasuk = '12:00';
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

            // Keterangan fake GPS akan ditampilkan di menu deteksi fake location untuk super admin
            // Tidak perlu tambahkan keterangan di field keterangan presensi

            // Presensi masuk
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu_masuk' => $waktuMasuk,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'is_fake_location' => $isFakeLocation,
                'fake_location_analysis' => $isFakeLocation ? $fakeLocationAnalysis : null,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info,
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

                // Keterangan fake GPS akan ditampilkan di menu deteksi fake location untuk super admin
                // Tidak perlu tambahkan keterangan di field keterangan presensi

                $presensi->update([
                    'status' => 'hadir',
                    'waktu_masuk' => $waktuMasuk,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'is_fake_location' => $isFakeLocation,
                    'fake_location_analysis' => $isFakeLocation ? $fakeLocationAnalysis : null,
                    'accuracy' => $request->accuracy,
                    'altitude' => $request->altitude,
                    'speed' => $request->speed,
                    'device_info' => $request->device_info,
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
     * Check for fake GPS by analyzing multiple location readings
     * @param array $readings
     * @return array
     */
    private function checkFakeGpsByMultipleReadings(array $readings)
    {
        if (empty($readings) || count($readings) < 2) {
            return [
                'is_fake' => false,
                'readings_count' => count($readings),
                'identical_count' => 0,
                'readings' => $readings,
                'time_span' => 0,
                'reason' => 'Insufficient readings for analysis'
            ];
        }

        $identicalCount = 0;
        $firstReading = $readings[0];

        // Analyze readings for identical coordinates
        foreach ($readings as $reading) {
            if (isset($reading['latitude']) && isset($reading['longitude'])) {
                // Check if coordinates are identical (within a very small tolerance for floating point)
                $latDiff = abs($reading['latitude'] - $firstReading['latitude']);
                $lngDiff = abs($reading['longitude'] - $firstReading['longitude']);

                // If difference is less than 0.000001 degrees (about 0.1 meter), consider identical
                if ($latDiff < 0.000001 && $lngDiff < 0.000001) {
                    $identicalCount++;
                }
            }
        }

        // If all readings are identical, likely fake GPS
        $isFake = $identicalCount >= count($readings);

        // Calculate time span
        $timeSpan = 0;
        if (count($readings) > 1 && isset($readings[0]['timestamp']) && isset(end($readings)['timestamp'])) {
            $timeSpan = end($readings)['timestamp'] - $readings[0]['timestamp'];
        }

        return [
            'is_fake' => $isFake,
            'readings_count' => count($readings),
            'identical_count' => $identicalCount,
            'readings' => $readings,
            'time_span' => $timeSpan,
            'reason' => $isFake ? 'All location readings are identical - indicates fake GPS usage' : 'Location readings vary naturally'
        ];
    }

    /**
     * Analyze location data for fake location indicators
     * @param Request $request
     * @param User $user
     * @param Madrasah|null $madrasah
     * @param Madrasah|null $madrasahTambahan
     * @return array
     */
    private function analyzeFakeLocation(Request $request, $user, $madrasah, $madrasahTambahan)
    {
        $issues = [];
        $severity = 0;

        // Check 1: Invalid coordinates (0,0 or default coordinates)
        if (($request->latitude == 0 && $request->longitude == 0) ||
            ($request->latitude == -7.7956 && $request->longitude == 110.3695)) {
            $issues[] = 'Koordinat default atau tidak valid (0,0 atau koordinat template)';
            $severity += 3;
        }

        // Check 2: Suspicious accuracy (too perfect or too low)
        if ($request->accuracy !== null) {
            if ($request->accuracy == 0) {
                $issues[] = 'Akurasi GPS tidak valid (0 meter)';
                $severity += 2;
            } elseif ($request->accuracy > 100) {
                $issues[] = 'Akurasi GPS terlalu rendah (' . $request->accuracy . ' meter)';
                $severity += 1;
            }
        }

        // Check 3: Suspicious speed (moving too fast for presensi)
        if ($request->speed !== null && $request->speed > 50) { // > 50 m/s = 180 km/h
            $issues[] = 'Kecepatan terlalu tinggi untuk aktivitas presensi (' . round($request->speed * 3.6, 1) . ' km/h)';
            $severity += 2;
        }

        // Check 4: Altitude check (suspicious if too high/low for school area)
        if ($request->altitude !== null) {
            if ($request->altitude < -100 || $request->altitude > 3000) {
                $issues[] = 'Ketinggian lokasi tidak wajar (' . $request->altitude . ' meter)';
                $severity += 1;
            }
        }

        // Check 5: Device info analysis
        if ($request->device_info) {
            $suspiciousDevicePatterns = [
                '/emulator/i',
                '/fake/i',
                '/mock/i',
                '/test/i',
                '/virtual/i'
            ];

            foreach ($suspiciousDevicePatterns as $pattern) {
                if (preg_match($pattern, $request->device_info)) {
                    $issues[] = 'Informasi perangkat mencurigakan';
                    $severity += 2;
                    break;
                }
            }
        }

        // Check 6: Location consistency with previous presensi
        $previousPresensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', '<', Carbon::now('Asia/Jakarta')->toDateString())
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($previousPresensi && $previousPresensi->latitude && $previousPresensi->longitude) {
            $distance = $this->calculateDistance(
                $previousPresensi->latitude,
                $previousPresensi->longitude,
                $request->latitude,
                $request->longitude
            );

            // If distance from previous location is suspiciously far (>500km in one day)
            if ($distance > 500) {
                $issues[] = 'Jarak dari lokasi presensi sebelumnya terlalu jauh (' . round($distance, 1) . ' km)';
                $severity += 1;
            }
        }

        // Check 7: Time-based anomalies (presensi at impossible times)
        $now = Carbon::now('Asia/Jakarta');
        $hour = $now->hour;

        // Suspicious if presensi at 3-5 AM (very unlikely for school)
        if ($hour >= 3 && $hour <= 5) {
            $issues[] = 'Waktu presensi pada jam yang tidak wajar (' . $hour . ':00)';
            $severity += 1;
        }

        // Check 8: Location name analysis
        if ($request->lokasi) {
            $suspiciousLocationPatterns = [
                '/\b(test|dummy|fake|contoh|sample)\b/i',
                '/\b(unknown|tidak diketahui|belum diisi)\b/i',
                '/\d{1,2}\.\d{6},\s*\d{1,2}\.\d{6}/', // Raw coordinates in location field
                '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', // Exact coordinate format
            ];

            foreach ($suspiciousLocationPatterns as $pattern) {
                if (preg_match($pattern, $request->lokasi)) {
                    $issues[] = 'Nama lokasi mencurigakan atau berisi koordinat mentah';
                    $severity += 1;
                    break;
                }
            }
        }

        // Check 9: Multiple presensi attempts in short time (possible automation)
        $recentAttempts = Presensi::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subMinutes(5))
            ->count();

        if ($recentAttempts > 2) {
            $issues[] = 'Terlalu banyak percobaan presensi dalam waktu singkat';
            $severity += 2;
        }

        // Check 10: Distance from madrasah center (if coordinates available)
        if ($madrasah && $madrasah->latitude && $madrasah->longitude) {
            $distanceFromSchool = $this->calculateDistance(
                $madrasah->latitude,
                $madrasah->longitude,
                $request->latitude,
                $request->longitude
            );

            // If distance is suspiciously far (>100km), flag it
            if ($distanceFromSchool > 100) {
                $issues[] = 'Jarak dari pusat madrasah terlalu jauh (' . round($distanceFromSchool, 2) . ' km)';
                $severity += 2;
            }
        }

        return [
            'is_fake' => count($issues) > 0,
            'issues' => $issues,
            'severity' => $severity,
            'severity_label' => $this->getSeverityLabel($severity),
            'checked_at' => Carbon::now('Asia/Jakarta')->toISOString(),
            'location_data' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info,
            ]
        ];
    }

    /**
     * Get severity label based on score
     */
    private function getSeverityLabel($severity)
    {
        if ($severity >= 8) return 'Sangat Tinggi';
        if ($severity >= 5) return 'Tinggi';
        if ($severity >= 3) return 'Sedang';
        if ($severity >= 1) return 'Rendah';
        return 'Tidak';
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
