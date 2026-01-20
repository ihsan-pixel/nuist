<?php

namespace App\Http\Controllers\Mobile\Presensi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Holiday;

class PresensiController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    // Presensi view (mobile)
    public function presensi(Request $request)
    {
        $user = Auth::user();

        // Allow all tenaga_pendidik to access presensi form; kepala madrasah will see madrasah-level monitoring data
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Check if user has pending izin terlambat for today - block access to presensi menu
        $pendingIzinTerlambat = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->where('keterangan', 'like', '%terlambat%')
            ->first();

        if ($pendingIzinTerlambat) {
            return redirect()->back()->with('error', 'Izin terlambat Anda sedang menunggu persetujuan kepala sekolah. Presensi akan dapat dilakukan setelah izin disetujui.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // If kepala madrasah, fetch madrasah-level presensi lists; otherwise, leave empty (non-kepala see personal presensi only)
        $presensis = collect();
        $belumPresensi = collect();
        $mapData = [];
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            // Get presensi data for the madrasah (including users with dual presensi)
            $presensis = Presensi::with(['user', 'statusKepegawaian', 'madrasah'])
                ->where('madrasah_id', $user->madrasah_id)
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
        // Only get actual presensi records (status = 'hadir'), not izin records
        $presensiHariIni = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $selectedDate)
            ->where('status', 'hadir')
            ->get();

        // For penjaga sekolah, if no presensi for today, show the last presensi to display status
        if ($user->ketugasan === 'penjaga sekolah' && $presensiHariIni->isEmpty()) {
            $lastPresensi = Presensi::with('madrasah')
                ->where('user_id', $user->id)
                ->where('status', 'hadir')
                ->orderBy('tanggal', 'desc')
                ->first();
            if ($lastPresensi) {
                $presensiHariIni = collect([$lastPresensi]);
            }
        }



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
                // Khusus hari Jumat untuk 6 hari KBM, presensi pulang mulai pukul 13:00, Sabtu pukul 12:00
                $pulangStart = ($dayOfWeek == 5) ? '13:00' : (($dayOfWeek == 6) ? '12:00' : '14:00');
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
            'selfie_data' => 'required|string|min:100', // Ensure it's not empty and has minimum length

        ]);

        // Additional validation for selfie data
        if (!$this->isValidBase64Image($request->selfie_data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data foto selfie tidak valid atau corrupt. Silakan ambil foto lagi.'
            ], 400);
        }

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

        // Special handling for penjaga sekolah - no time restrictions
        if ($user->ketugasan !== 'penjaga sekolah') {
            // Check if time is after 22:00 - mark as alpha (only for non-penjaga sekolah)
            if ($now->format('H:i:s') > '22:00:00') {
                // Check if user already has presensi for today
                $existingPresensi = Presensi::where('user_id', $user->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                if (!$existingPresensi) {
                    // Create alpha record without saving selfie
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
                        'selfie_masuk_path' => null, // Don't save selfie for failed presensi
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
        }

        // Determine madrasah based on location for users with additional madrasah
        $determinedMadrasahId = null;
        $isWithinAnyPolygon = false;

        // If user has pemenuhan_beban_kerja_lain and madrasah_id_tambahan, check additional madrasah first
        if ($user->pemenuhan_beban_kerja_lain && $user->madrasah_id_tambahan) {
            $additionalMadrasah = $user->madrasahTambahan;
            $polygonsToCheck = [];
            if ($additionalMadrasah && $additionalMadrasah->polygon_koordinat) {
                $polygonsToCheck[] = $additionalMadrasah->polygon_koordinat;
            }
            if ($additionalMadrasah && $additionalMadrasah->enable_dual_polygon && $additionalMadrasah->polygon_koordinat_2) {
                $polygonsToCheck[] = $additionalMadrasah->polygon_koordinat_2;
            }

            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinAnyPolygon = true;
                            $determinedMadrasahId = $user->madrasah_id_tambahan;
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // If not within additional madrasah (or no additional), check main madrasah
        if (!$isWithinAnyPolygon) {
            $mainMadrasah = $user->madrasah;
            $polygonsToCheck = [];
            if ($mainMadrasah && $mainMadrasah->polygon_koordinat) {
                $polygonsToCheck[] = $mainMadrasah->polygon_koordinat;
            }
            if ($mainMadrasah && $mainMadrasah->enable_dual_polygon && $mainMadrasah->polygon_koordinat_2) {
                $polygonsToCheck[] = $mainMadrasah->polygon_koordinat_2;
            }

            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinAnyPolygon = true;
                            $determinedMadrasahId = $user->madrasah_id;
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // If not within any polygon, reject presensi
        if (!$isWithinAnyPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        // Check existing presensi - different logic for penjaga sekolah
        if ($user->ketugasan === 'penjaga sekolah') {
            // For penjaga sekolah: check for any open presensi (masuk without keluar) regardless of date
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->orderBy('tanggal', 'desc')
                ->first();
        } else {
            // For other users: check presensi for today only
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();
        }

        // Check if user has pending izin terlambat for today - block presensi if pending
        $pendingIzinTerlambat = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->where('keterangan', 'like', '%terlambat%')
            ->first();

        if ($pendingIzinTerlambat) {
            return response()->json([
                'success' => false,
                'message' => 'Izin terlambat Anda sedang menunggu persetujuan kepala sekolah. Presensi akan dapat dilakukan setelah izin disetujui.'
            ], 400);
        }



        // Determine presensi type with additional checks
        if ($user->ketugasan === 'penjaga sekolah') {
            // For penjaga sekolah: if there's an open presensi, this is keluar; otherwise masuk
            $isPresensiMasuk = !$existingPresensi;
            $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        } else {
            // For other users: standard logic
            $isPresensiMasuk = !$existingPresensi || (!$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar);
            $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        }

        // Check if entry presensi is attempted before allowed time
        if ($isPresensiMasuk) {
            if ($user->ketugasan === 'penjaga sekolah') {
                // Penjaga sekolah: no time restrictions for presensi masuk
                // Can presensi anytime 24 hours
            } elseif ($user->pemenuhan_beban_kerja_lain) {
                // User with beban kerja lain: presensi masuk 05:00 - 22:00
                if ($now->format('H:i:s') < '05:00:00') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi masuk belum dapat dilakukan. Waktu presensi masuk dimulai pukul 05:00.'
                    ], 400);
                }
            } else {
                // User biasa: presensi masuk sesuai hari KBM
                $minTimeMasuk = '05:00:00'; // Default for all days
                if ($now->format('H:i:s') < $minTimeMasuk) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi masuk belum dapat dilakukan. Waktu presensi masuk dimulai pukul 05:00.'
                    ], 400);
                }
            }
        }

        // Validate location for fake GPS detection
        $locationValidation = $this->validateLocationForFakeGPS($request, $user, $isPresensiMasuk);

        if ($locationValidation['is_fake']) {
            return response()->json([
                'success' => false,
                'message' => $locationValidation['message']
            ], 400);
        }

        // Process and save selfie image
        $selfiePath = $this->processAndSaveSelfie($request->selfie_data, $user->id, $tanggal, $isPresensiMasuk);

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

        // Determine if this is presensi masuk or keluar
        if ($isPresensiMasuk) {
            // Presensi Masuk
            $status = 'hadir';
            $waktuMasuk = $now;
            $waktuKeluar = null;

            // For penjaga sekolah, use current date for tanggal
            if ($user->ketugasan === 'penjaga sekolah') {
                $tanggal = Carbon::today()->toDateString();
            }

            // Check if user has approved izin terlambat for today
            $approvedIzinTerlambat = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->where('status', 'izin')
                ->where('status_izin', 'approved')
                ->where('keterangan', 'like', '%terlambat%')
                ->first();

            // Calculate lateness - only set keterangan if late (after 07:00)
            $keterangan = "";
            if ($approvedIzinTerlambat) {
                // If has approved izin terlambat, mark as "terlambat sudah izin"
                $keterangan = "terlambat sudah izin";
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

            // Special handling for early presensi (between 01:00 and 05:00)
            if ($now->format('H:i:s') >= '01:00:00' && $now->format('H:i:s') < '05:00:00') {
                $keterangan = "Presensi dini (sebelum pukul 05:00)";
            }



            // Create new presensi record
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'madrasah_id' => $determinedMadrasahId,
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
                'selfie_masuk_path' => $selfiePath,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
                'is_fake_location' => $locationValidation['is_fake'] ?? false,
                'fake_location_analysis' => $locationValidation['analysis'] ?? null,
            ]);

            $message = 'Presensi masuk berhasil dicatat!';

        } elseif ($isPresensiKeluar) {
            // Check if it's time to go home (after pulang_start time)
            if ($user->ketugasan === 'penjaga sekolah') {
                // Penjaga sekolah: no time restrictions for presensi keluar
                // Can presensi anytime 24 hours
            } elseif (!$user->pemenuhan_beban_kerja_lain) {
                // User biasa: presensi keluar sesuai hari KBM
                $pulangStart = '15:00:00'; // Default fallback

                if ($user->madrasah && $user->madrasah->hari_kbm) {
                    $hariKbm = $user->madrasah->hari_kbm;
                    $dayOfWeek = Carbon::now('Asia/Jakarta')->dayOfWeek; // 0=Sunday, 5=Friday

                    if ($hariKbm == '5') {
                        // KBM 5 hari: Senin-Jumat presensi keluar mulai 15:00
                        $pulangStart = '15:00:00';
                    } elseif ($hariKbm == '6') {
                        // KBM 6 hari: Senin-Kamis 14:00, Jumat 13:00, Sabtu 12:00
                        if ($dayOfWeek == 5) { // Friday
                            $pulangStart = '13:00:00';
                        } elseif ($dayOfWeek == 6) { // Saturday
                            $pulangStart = '12:00:00';
                        } else { // Monday-Thursday
                            $pulangStart = '14:00:00';
                        }
                    }
                }

                if ($now->format('H:i:s') < $pulangStart) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi keluar belum dapat dilakukan. Waktu presensi keluar dimulai pukul ' . substr($pulangStart, 0, 5) . '.'
                    ], 400);
                }
            }
            // User with beban kerja lain: no time restriction for presensi keluar

            // Presensi Keluar - update existing record
            // For penjaga sekolah, update the existing open presensi record
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
                'selfie_keluar_path' => $selfiePath,
                'is_fake_location_keluar' => $locationValidation['is_fake'] ?? false,
                'fake_location_analysis_keluar' => $locationValidation['analysis'] ?? null,
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
                'presensi' => $presensi,
                'madrasah_name' => $user->madrasah?->name ?? 'Madrasah',
                'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : now()->format('H:i')
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
            ->orderBy('waktu_masuk', 'desc')
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
     * Comprehensive fake GPS detection validation
     * @param Request $request
     * @param User $user
     * @param bool $isPresensiMasuk
     * @return array ['is_fake' => bool, 'message' => string, 'analysis' => array]
     */
    private function validateLocationForFakeGPS(Request $request, $user, bool $isPresensiMasuk): array
    {
        $analysis = [
            'accuracy_check' => false,
            'consistency_check' => false,
            'speed_check' => false,
            'location_history_check' => false,
            'suspicious_indicators' => []
        ];

        $isFake = false;
        $messages = [];

        // 1. Check GPS accuracy - fake GPS often has unrealistically high accuracy
        if ($request->accuracy && $request->accuracy < 2) {
            // Accuracy better than 1 meter is suspicious (too perfect)
            $analysis['accuracy_check'] = true;
            $analysis['suspicious_indicators'][] = 'accuracy_too_perfect';
            $isFake = true;
            $messages[] = 'Akurasi GPS terlalu sempurna (kemungkinan fake GPS)';
        }

        // 2. Check location consistency from readings
        if ($request->location_readings) {
            $consistencyResult = $this->validateLocationConsistency($request->location_readings);
            if (!$consistencyResult['valid']) {
                $analysis['consistency_check'] = true;
                $analysis['suspicious_indicators'][] = 'location_consistency';
                $isFake = true;
                $messages[] = $consistencyResult['message'];
            }
        }

        // 3. Check for abnormal movement speed (teleportation detection)
        if ($isPresensiMasuk) {
            // For presensi masuk, check against last known location
            $lastPresensi = Presensi::where('user_id', $user->id)
                ->where('status', 'hadir')
                ->whereDate('tanggal', '<', Carbon::today())
                ->orderBy('tanggal', 'desc')
                ->first();

            if ($lastPresensi && $lastPresensi->latitude && $lastPresensi->longitude) {
                $distance = $this->calculateDistance(
                    $lastPresensi->latitude,
                    $lastPresensi->longitude,
                    $request->latitude,
                    $request->longitude
                );

                // Calculate time difference in hours
                $lastPresensiTime = $lastPresensi->waktu_keluar ?? $lastPresensi->waktu_masuk;
                $timeDiffHours = $lastPresensiTime ? Carbon::now()->diffInHours($lastPresensiTime) : 24;

                if ($timeDiffHours > 0) {
                    $speedKmh = $distance / $timeDiffHours; // km/h

                    // If speed > 200 km/h (impossible for human travel), flag as suspicious
                    if ($speedKmh > 200) {
                        $analysis['speed_check'] = true;
                        $analysis['suspicious_indicators'][] = 'abnormal_speed';
                        $isFake = true;
                        $messages[] = 'Deteksi pergerakan tidak wajar (kemungkinan teleportasi GPS)';
                    }
                }
            }
        } else {
            // For presensi keluar, check against presensi masuk location
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->orderBy('tanggal', 'desc')
                ->first();

            if ($existingPresensi && $existingPresensi->latitude && $existingPresensi->longitude) {
                $distance = $this->calculateDistance(
                    $existingPresensi->latitude,
                    $existingPresensi->longitude,
                    $request->latitude,
                    $request->longitude
                );

                // If distance > 5km between masuk and keluar, suspicious
                if ($distance > 5) {
                    $analysis['location_history_check'] = true;
                    $analysis['suspicious_indicators'][] = 'location_jump';
                    $isFake = true;
                    $messages[] = 'Jarak lokasi masuk dan keluar terlalu jauh (kemungkinan fake GPS)';
                }
            }
        }

        // 4. Check for suspicious device info patterns
        if ($request->device_info) {
            $deviceInfo = strtolower($request->device_info);
            $suspiciousApps = ['fake', 'mock', 'gps', 'location', 'spoof'];

            foreach ($suspiciousApps as $app) {
                if (strpos($deviceInfo, $app) !== false) {
                    $analysis['suspicious_indicators'][] = 'device_info_suspicious';
                    $isFake = true;
                    $messages[] = 'Informasi device menunjukkan penggunaan aplikasi GPS palsu';
                    break;
                }
            }
        }

        // 5. Check for perfect coordinates (common fake GPS pattern)
        if ($request->latitude && $request->longitude) {
            $latStr = (string)$request->latitude;
            $lngStr = (string)$request->longitude;

            // Check if coordinates have unrealistically high precision (>15 decimal places)
            // Normal GPS precision is around 6-8 decimal places, iOS can provide up to 12-14
            $latParts = explode('.', $latStr);
            $lngParts = explode('.', $lngStr);

            $latDecimals = isset($latParts[1]) ? strlen($latParts[1]) : 0;
            $lngDecimals = isset($lngParts[1]) ? strlen($lngParts[1]) : 0;

            // Only flag as suspicious if precision is extremely high (>15 decimal places)
            if ($latDecimals > 15 || $lngDecimals > 15) {
                $analysis['suspicious_indicators'][] = 'precision_too_high';
                $isFake = true;
                $messages[] = 'Presisi koordinat GPS tidak wajar';
            }

            // Check for exact round numbers (suspicious)
            if (fmod($request->latitude, 1) == 0 || fmod($request->longitude, 1) == 0) {
                $analysis['suspicious_indicators'][] = 'round_coordinates';
                $isFake = true;
                $messages[] = 'Koordinat GPS terlalu bulat (kemungkinan fake)';
            }
        }

        return [
            'is_fake' => $isFake,
            'message' => !empty($messages) ? implode('. ', $messages) : 'Lokasi GPS valid',
            'analysis' => $analysis
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Distance in kilometers
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validates if the given string is a valid base64 encoded image
     * @param string $data Base64 string to validate
     * @return bool True if valid base64 image
     */
    private function isValidBase64Image(string $data): bool
    {
        // Check minimum length
        if (strlen($data) < 100) {
            return false;
        }

        // Check if it starts with data:image/ pattern
        if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $data)) {
            return false;
        }

        // Extract base64 part
        $base64Data = preg_replace('/^data:image\/(jpeg|jpg|png);base64,/', '', $data);

        // Check if it's valid base64
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64Data)) {
            return false;
        }

        // Try to decode and check if it's valid image data
        $decoded = base64_decode($base64Data, true);
        if ($decoded === false) {
            return false;
        }

        // Check if decoded data looks like image (starts with image signature)
        $imageSignatures = [
            "\xFF\xD8\xFF", // JPEG
            "\x89\x50\x4E\x47", // PNG
        ];

        $isValidImage = false;
        foreach ($imageSignatures as $signature) {
            if (strpos($decoded, $signature) === 0) {
                $isValidImage = true;
                break;
            }
        }

        return $isValidImage;
    }

    /**
     * Process and save selfie image
     * @param string $selfieData Base64 image data
     * @param int $userId User ID
     * @param string $tanggal Date string
     * @param bool $isMasuk Whether this is presensi masuk
     * @return string Path to saved image
     */
    private function processAndSaveSelfie(string $selfieData, int $userId, string $tanggal, bool $isMasuk): string
    {
        try {
            // Path to storage/app/public for Laravel consistency
            $path = storage_path('app/public/presensi-selfies');

            // Pastikan folder sudah ada
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Generate filename
            $type = $isMasuk ? 'masuk' : 'keluar';
            $timestamp = time();
            $namaFile = "selfie_{$userId}_{$type}_{$timestamp}.jpg";

            // Decode base64 image data
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $selfieData));

            // Save to temporary file first
            $tempFile = tempnam(sys_get_temp_dir(), 'selfie');
            file_put_contents($tempFile, $imageData);

            // Create file instance and move to destination
            $file = new \Illuminate\Http\UploadedFile(
                $tempFile,
                $namaFile,
                'image/jpeg',
                null,
                true
            );

            $file->move($path, $namaFile);

            // Verify file was actually saved
            $fullPath = $path . '/' . $namaFile;
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                throw new \Exception('File was not saved successfully or is empty');
            }

            // Additional validation: check if file is readable and has valid image content
            if (!is_readable($fullPath)) {
                throw new \Exception('Saved file is not readable');
            }

            // Try to get image info to ensure it's a valid image
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                // If getimagesize fails, delete the invalid file
                unlink($fullPath);
                throw new \Exception('Saved file is not a valid image');
            }

            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            // Return path yang disimpan ke database
            return 'presensi-selfies/' . $namaFile;

        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Selfie processing failed', [
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'is_masuk' => $isMasuk,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'path_used' => $path ?? 'unknown',
                'filename' => $namaFile ?? 'unknown'
            ]);

            // Throw exception instead of returning empty string to prevent blank photos
            throw new \Exception('Gagal memproses foto selfie: ' . $e->getMessage());
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
