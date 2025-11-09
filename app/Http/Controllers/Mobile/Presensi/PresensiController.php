<?php

namespace App\Http\Controllers\Mobile\Presensi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Holiday;

class PresensiController extends \App\Http\Controllers\Controller
{
    // Presensi view (mobile)
    public function presensi(Request $request)
    {
        $user = Auth::user();

        // Allow all tenaga_pendidik to access presensi form; kepala madrasah will see madrasah-level monitoring data
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // If kepala madrasah, fetch madrasah-level presensi lists; otherwise, leave empty (non-kepala see personal presensi only)
        $presensis = collect();
        $belumPresensi = collect();
        $mapData = [];
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            // Get presensi data for the madrasah
            $presensis = Presensi::with(['user', 'statusKepegawaian'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                })
                ->whereDate('tanggal', $selectedDate)
                ->orderBy('waktu_masuk', 'desc')
                ->get();

            // Get users who haven't done presensi
            $belumPresensi = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                    $q->whereDate('tanggal', $selectedDate);
                })
                ->get();

            // Prepare map data
            $madrasahLat = $user->madrasah->latitude ?? -6.2088; // Default Jakarta coordinates
            $madrasahLng = $user->madrasah->longitude ?? 106.8456;

            // Add markers for users who have done presensi
            foreach ($presensis as $presensi) {
                $mapData[] = [
                    'id' => $presensi->user->id,
                    'name' => $presensi->user->name,
                    'status' => $presensi->status,
                    'latitude' => $presensi->latitude ?? $madrasahLat,
                    'longitude' => $presensi->longitude ?? $madrasahLng,
                    'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : null,
                    'waktu_keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : null,
                    'lokasi' => $presensi->lokasi ?? 'Lokasi tidak tersedia',
                    'marker_type' => 'presensi',
                    'status_kepegawaian' => $presensi->user->statusKepegawaian?->name ?? '-'
                ];
            }

            // Add markers for users who haven't done presensi (at madrasah location)
            foreach ($belumPresensi as $userBelum) {
                $mapData[] = [
                    'id' => $userBelum->id,
                    'name' => $userBelum->name,
                    'status' => 'belum_presensi',
                    'latitude' => $madrasahLat,
                    'longitude' => $madrasahLng,
                    'waktu_masuk' => null,
                    'waktu_keluar' => null,
                    'lokasi' => $user->madrasah->alamat ?? 'Alamat madrasah',
                    'marker_type' => 'belum_presensi',
                    'status_kepegawaian' => $userBelum->statusKepegawaian?->name ?? '-'
                ];
            }
        }

        // Additional data expected by the mobile.presensi view
        $dateString = $selectedDate->toDateString();

        // Check holiday
        $isHoliday = Holiday::isHoliday($dateString);
        $holiday = $isHoliday ? Holiday::getHoliday($dateString) : null;

        // Presensi of the current user for the selected date (all madrasahs for dual presensi)
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $selectedDate)
            ->get();

        // Determine presensi time ranges based on madrasah hari_kbm (fallbacks included)
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $hariKbm = $user->madrasah->hari_kbm;
            $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek; // 0=Sunday, 5=Friday

            if ($hariKbm == '5') {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                $pulangStart = '15:00';
                $pulangEnd = '22:00';
            } elseif ($hariKbm == '6') {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                // Khusus hari Jumat untuk 6 hari KBM, presensi pulang mulai pukul 14:30
                $pulangStart = ($dayOfWeek == 5) ? '14:30' : '15:00';
                $pulangEnd = '22:00';
            } else {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                $pulangStart = '15:00';
                $pulangEnd = '22:00';
            }

            $timeRanges = [
                'masuk_start' => $masukStart,
                'masuk_end' => $masukEnd,
                'pulang_start' => $pulangStart,
                'pulang_end' => $pulangEnd,
            ];

            // Remove masuk_end to indicate no time limit for presensi entry
            $timeRanges['masuk_end'] = null;
        }

        return view('mobile.presensi', compact('presensis', 'belumPresensi', 'selectedDate', 'isHoliday', 'holiday', 'presensiHariIni', 'timeRanges', 'mapData', 'user'));
    }

    // Store presensi (mobile)
    public function storePresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'accuracy' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'location_readings' => 'nullable|string',

        ]);

        $tanggal = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        // Check if it's a holiday or Sunday - prevent presensi
        $isHoliday = Holiday::isHoliday($tanggal);
        $isSunday = Carbon::parse($tanggal)->dayOfWeek === Carbon::SUNDAY;

        if ($isHoliday || $isSunday) {
            $holiday = $isHoliday ? Holiday::getHoliday($tanggal) : null;
            $reason = $isHoliday ? "hari libur ({$holiday->name})" : "hari Minggu";
            return response()->json([
                'success' => false,
                'message' => "Presensi tidak dapat dilakukan pada {$reason}."
            ], 400);
        }

        // Check if time is after 22:00 - mark as alpha
        if ($now->format('H:i:s') > '22:00:00') {
            // Check if user already has presensi for today
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if (!$existingPresensi) {
                // Create alpha record
                Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                    'status' => 'alpha',
                    'keterangan' => 'Tidak masuk',
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'accuracy' => $request->accuracy,
                    'altitude' => $request->altitude,
                    'speed' => $request->speed,
                    'device_info' => $request->device_info,
                    'location_readings' => $request->location_readings,
                    'status_kepegawaian_id' => $user->status_kepegawaian_id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Presensi setelah pukul 22:00 otomatis dicatat sebagai tidak masuk.'
                ], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi hari ini sudah dicatat.'
                ], 400);
            }
        }

        // Check if user already has presensi for today with stricter validation
        $existingPresensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Determine presensi type with additional checks
        $isPresensiMasuk = !$existingPresensi || (!$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar);
        $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;

        // Prevent double submission for masuk if already exists
        if ($isPresensiMasuk && $existingPresensi && $existingPresensi->waktu_masuk) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi masuk hari ini sudah dicatat. Silakan lakukan presensi keluar jika belum.'
            ], 400);
        }

        // Prevent double submission for keluar if already exists
        if ($isPresensiKeluar && $existingPresensi && $existingPresensi->waktu_keluar) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi keluar hari ini sudah dicatat. Presensi hari ini sudah lengkap.'
            ], 400);
        }



        // New validation: Check location consistency in readings
        $locationValidationResult = $this->validateLocationConsistency($request->location_readings);
        if (!$locationValidationResult['valid']) {
            return response()->json([
                'success' => false,
                'message' => $locationValidationResult['message']
            ], 400);
        }

        // Location validation using polygon from madrasah
        $madrasah = $user->madrasah;
        $isWithinPolygon = false;

        $polygonsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat;
        }
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat_2;
        }

        if (!empty($polygonsToCheck)) {
            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinPolygon = true;
                            break; // Jika sudah ada yang valid, tidak perlu cek yang lain
                        }
                    }
                } catch (\Exception $e) {
                    continue; // Skip invalid polygon
                }
            }
        }

        // Location validation using polygon from madrasah
        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        // Add location validation note if outside polygon
        $locationNote = '';

        // Determine if this is presensi masuk or keluar
        if ($isPresensiMasuk) {
            // Presensi Masuk
            $status = 'hadir';
            $waktuMasuk = $now;
            $waktuKeluar = null;

            // Calculate lateness - only set keterangan if late (after 07:00)
            $keterangan = "";
            if ($user->pemenuhan_beban_kerja_lain) {
                $keterangan = "tidak terlambat";
            } else {
                // Jika waktu presensi setelah 07:00, hitung keterlambatan
                if ($now->format('H:i:s') > '07:00:00') {
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
            }

            // Create new presensi record
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'waktu_masuk' => $waktuMasuk,
                'waktu_keluar' => $waktuKeluar,
                'status' => $status,
                'keterangan' => $keterangan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info,
                'location_readings' => $request->location_readings,

                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

            $message = 'Presensi masuk berhasil dicatat!';

        } elseif ($isPresensiKeluar) {
            // Check if it's time to go home (after pulang_start time)
            $pulangStart = '15:00:00'; // Default pulang start time
            if ($user->madrasah && $user->madrasah->hari_kbm) {
                $hariKbm = $user->madrasah->hari_kbm;
                if ($hariKbm == '5' || $hariKbm == '6') {
                    $pulangStart = '15:00:00';
                } else {
                    $pulangStart = '15:00:00';
                }
            }

            if ($now->format('H:i:s') < $pulangStart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi keluar belum dapat dilakukan. Waktu presensi keluar dimulai pukul ' . substr($pulangStart, 0, 5) . '.'
                ], 400);
            }

            // Presensi Keluar - update existing record
            $existingPresensi->update([
                'waktu_keluar' => $now,
                'latitude_keluar' => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'lokasi_keluar' => $request->lokasi,
                'accuracy_keluar' => $request->accuracy,
                'altitude_keluar' => $request->altitude,
                'speed_keluar' => $request->speed,
                'device_info_keluar' => $request->device_info,
                'location_readings_keluar' => $request->location_readings,
            ]);

            $presensi = $existingPresensi;
            $message = 'Presensi keluar berhasil dicatat!';

        } else {
            // Both masuk and keluar already done or invalid state
            return response()->json([
                'success' => false,
                'message' => 'Presensi hari ini sudah lengkap atau dalam keadaan tidak valid.'
            ], 400);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'presensi' => $presensi
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // Riwayat presensi
    public function riwayatPresensi(Request $request)
    {
        $user = Auth::user();

        // only tenaga_pendidik may access mobile pages
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // allow optional month navigation via ?month=2025-10-01
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        // Fetch presensi for the selected month for the authenticated user
        $presensiHistory = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.riwayat-presensi', compact('presensiHistory'));
    }

    // Riwayat presensi alpha
    public function riwayatPresensiAlpha(Request $request)
    {
        $user = Auth::user();

        // only tenaga_pendidik may access mobile pages
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // allow optional month navigation via ?month=2025-10-01
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        // Fetch only alpha presensi for the selected month for the authenticated user
        $presensiHistory = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->where('status', 'alpha')
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.riwayat-presensi-alpha', compact('presensiHistory'));
    }

    /**
     * Validates location consistency in readings to detect potential fake locations.
     * @param string $locationReadingsJson JSON string containing location readings array
     * @return array ['valid' => bool, 'message' => string]
     */
    private function validateLocationConsistency(string $locationReadingsJson): array
    {
        try {
            $readings = json_decode($locationReadingsJson, true);

            if (!is_array($readings) || count($readings) < 4) {
                // If less than 4 readings, allow presensi (backward compatibility)
                return ['valid' => true, 'message' => ''];
            }

            // Take first 4 readings for validation (exclude the final reading on button click)
            $firstFourReadings = array_slice($readings, 0, 4);

            // Tolerance for location consistency (approximately 10 meters)
            $tolerance = 0.0001; // degrees

            // Check if all 4 readings are within tolerance of each other
            $referenceLat = $firstFourReadings[0]['latitude'];
            $referenceLng = $firstFourReadings[0]['longitude'];

            $consistentCount = 0;
            foreach ($firstFourReadings as $reading) {
                $latDiff = abs($reading['latitude'] - $referenceLat);
                $lngDiff = abs($reading['longitude'] - $referenceLng);

                if ($latDiff <= $tolerance && $lngDiff <= $tolerance) {
                    $consistentCount++;
                }
            }

            // If all 4 readings are consistent (same location), reject presensi
            if ($consistentCount >= 4) {
                return [
                    'valid' => false,
                    'message' => 'Peringatan, presensi anda terindikasi sebagai lokasi tidak sesuai. Silahkan geser atau pindah dari posisi sebelumnya.'
                ];
            }

            // If only 3 or fewer are consistent, allow presensi
            return ['valid' => true, 'message' => ''];

        } catch (\Exception $e) {
            // If there's any error parsing readings, allow presensi for safety
            return ['valid' => true, 'message' => ''];
        }
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
