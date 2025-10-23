<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MobileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:tenaga_pendidik']);
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Hitung data presensi bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $firstPresensiDate = \App\Models\Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = \Carbon\Carbon::parse($firstPresensiDate);
        } else {
            $startDate = \Carbon\Carbon::parse($user->created_at);
        }

        $workingDays = $this->calculateWorkingDaysInMonth($startDate, now());

        $presensiCounts = \App\Models\Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, now()])
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hadir = $presensiCounts['hadir'] ?? 0;
        $totalBasis = array_sum($presensiCounts);
        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 1) : 0;

        $attendanceData = [
            'kehadiran' => $kehadiranPercent,
            'total_presensi' => $totalBasis,
            'hadir' => $hadir,
            'izin' => $presensiCounts['izin'] ?? 0,
            'sakit' => $presensiCounts['sakit'] ?? 0,
            'alpha' => $presensiCounts['alpha'] ?? 0,
        ];

        // Get today's schedule
        $today = Carbon::now()->locale('id')->dayName;
        $todaySchedule = \App\Models\TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->where('day', $this->translateDayName($today))
            ->orderBy('start_time')
            ->get();

        return view('mobile.dashboard', compact('attendanceData', 'todaySchedule'));
    }

    public function presensi()
    {
        $user = Auth::user();

        // Check if password not changed
        if (!$user->password_changed) {
            return redirect()->route('mobile.dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu.');
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $presensiHariIni = \App\Models\Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $isHoliday = \App\Models\Holiday::isHoliday($today);
        $holiday = null;
        if ($isHoliday) {
            $holiday = \App\Models\Holiday::getHoliday($today);
        }

        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $timeRanges = $this->getPresensiTimeRanges($user->madrasah->hari_kbm, $today);
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                $timeRanges['masuk_end'] = '12:00';
            }
        }

        return view('mobile.presensi', compact('presensiHariIni', 'isHoliday', 'holiday', 'timeRanges'));
    }

    public function storePresensi(Request $request)
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

        $presensi = \App\Models\Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $madrasah = $user->madrasah;
        $madrasahTambahan = $user->madrasahTambahan;

        $isFakeLocation = false;
        $fakeLocationAnalysis = [];

        if ($request->has('location_readings')) {
            $locationReadings = json_decode($request->location_readings, true);

            if (count($locationReadings) >= 3) {
                $reading1 = $locationReadings[0];
                $reading2 = $locationReadings[1];
                $reading3 = $locationReadings[2];

                $distance12 = $this->calculateDistance(
                    $reading1['latitude'], $reading1['longitude'],
                    $reading2['latitude'], $reading2['longitude']
                );

                $distance23 = $this->calculateDistance(
                    $reading2['latitude'], $reading2['longitude'],
                    $reading3['latitude'], $reading3['longitude']
                );

                $distance13 = $this->calculateDistance(
                    $reading1['latitude'], $reading1['longitude'],
                    $reading3['latitude'], $reading3['longitude']
                );

                $issues = [];
                $severity = 0;

                if ($distance12 < 0.0001) {
                    $issues[] = 'Reading 1 dan Reading 2 memiliki koordinat sama persis';
                    $severity += 2;
                }

                if ($distance23 < 0.0001) {
                    $issues[] = 'Reading 2 dan Reading 3 memiliki koordinat sama persis';
                    $severity += 2;
                }

                if ($distance13 < 0.0001) {
                    $issues[] = 'Reading 1 dan Reading 3 memiliki koordinat sama persis';
                    $severity += 2;
                }

                if (count($issues) > 0) {
                    $isFakeLocation = true;
                    $fakeLocationAnalysis = [
                        'fake_gps_detected' => true,
                        'distances' => [
                            'reading1_reading2' => $distance12,
                            'reading2_reading3' => $distance23,
                            'reading1_reading3' => $distance13
                        ],
                        'issues' => $issues,
                        'severity' => min($severity, 5),
                        'severity_label' => $this->getSeverityLabel($severity)
                    ];
                }
            }
        }

        $isWithinPolygon = false;
        $validMadrasah = null;

        $madrasahsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasah;
        }
        if ($user->pemenuhan_beban_kerja_lain && $madrasahTambahan && $madrasahTambahan->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasahTambahan;
        }

        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $madrasahsToCheck[] = (object)['polygon_koordinat' => $madrasah->polygon_koordinat_2];
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
                continue;
            }
        }

        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area presensi yang telah ditentukan.'
            ], 400);
        }

        if ($isFakeLocation) {
            \Log::warning('Fake location detected - presensi allowed but flagged', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'analysis' => $fakeLocationAnalysis
            ]);
        }

        if ($user->pemenuhan_beban_kerja_lain) {
            $batasAwalMasuk = null;
            $batasAkhirMasuk = null;
            $batasPulang = null;
        } else {
            $hariKbm = $validMadrasah ? $validMadrasah->hari_kbm : null;
            $timeRanges = $this->getPresensiTimeRanges($hariKbm, $today);
            $batasAwalMasuk = $timeRanges['masuk_start'];
            $batasAkhirMasuk = $timeRanges['masuk_end'];
            $batasPulang = $timeRanges['pulang_start'];
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                $batasAkhirMasuk = '12:00';
            }
        }

        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if (!$presensi) {
            if ($batasAwalMasuk && $now < $batasAwalMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum waktunya presensi masuk.'
                ], 400);
            }

            if ($batasAkhirMasuk && $now > $batasAkhirMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu presensi masuk telah berakhir.'
                ], 400);
            }

            $waktuMasuk = $request->input('waktu_masuk') ?? $now;
            $keterangan = null;

            if ($now > '07:00:00') {
                $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
                $sekarang = Carbon::now('Asia/Jakarta');
                $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

                if ($sekarang->lessThan($batas)) {
                    $terlambatMenit = 0;
                } else {
                    $terlambatMenit = abs(round($terlambatMenit));
                }

                $keterangan = "Terlambat {$terlambatMenit} menit";
            } else {
                $keterangan = "tidak terlambat";
            }

            $presensi = \App\Models\Presensi::create([
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
                $waktuMasuk = $request->input('waktu_masuk') ?? $now;
                $keterangan = null;

                if ($now > '07:00:00') {
                    $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
                    $sekarang = Carbon::now('Asia/Jakarta');
                    $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

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
                if ($batasPulang && $now < $batasPulang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya presensi pulang.'
                    ], 400);
                }

                $tanggalSekarang = Carbon::now('Asia/Jakarta')->toDateString();
                if ($tanggalSekarang !== $presensi->tanggal->toDateString()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi keluar harus dilakukan pada tanggal yang sama dengan presensi masuk.'
                    ], 400);
                }

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

    public function jadwal()
    {
        $user = Auth::user();

        $schedules = \App\Models\TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $grouped = $schedules->groupBy('day');

        return view('mobile.jadwal', compact('grouped'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('mobile.profile', compact('user'));
    }

    private function calculateWorkingDaysInMonth($startDate, $endDate)
    {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            if ($currentDate->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                if (!\App\Models\Holiday::isHoliday($currentDate)) {
                    $workingDays++;
                }
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    private function getPresensiTimeRanges($hariKbm, $today)
    {
        $dayOfWeek = Carbon::parse($today)->dayOfWeek;

        if ($hariKbm == '5') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30';
            $pulangEnd = '17:00';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 6) ? '12:00' : '13:00';
            $pulangEnd = '17:00';
        } else {
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

    private function translateDayName($englishDay)
    {
        $translations = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        return $translations[$englishDay] ?? $englishDay;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

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

            if ((($vertexiLat > $pointLat) != ($vertexjLat > $pointLat)) &&
                ($pointLng < ($vertexjLng - $vertexiLng) * ($pointLat - $vertexiLat) / ($vertexjLat - $vertexiLat) + $vertexiLng)) {
                $isInside = !$isInside;
            }
        }

        return $isInside;
    }

    private function getSeverityLabel($severity)
    {
        if ($severity >= 8) return 'Sangat Tinggi';
        if ($severity >= 5) return 'Tinggi';
        if ($severity >= 3) return 'Sedang';
        if ($severity >= 1) return 'Rendah';
        return 'Tidak';
    }
}
