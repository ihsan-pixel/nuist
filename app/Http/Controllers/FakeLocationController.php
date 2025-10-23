<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FakeLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $selectedMadrasah = $request->input('madrasah_id');
        $selectedKabupaten = $request->input('kabupaten');

        // Get all madrasah for filter dropdown
        $madrasahs = Madrasah::orderBy('kabupaten')->orderBy('name')->get();

        // Get kabupaten list
        $kabupatenList = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        // Query presensi data with potential fake locations
        $query = Presensi::with(['user.madrasah', 'statusKepegawaian'])
            ->whereDate('tanggal', $selectedDate);

        // Apply filters
        if ($selectedMadrasah) {
            $query->whereHas('user', function ($q) use ($selectedMadrasah) {
                $q->where('madrasah_id', $selectedMadrasah);
            });
        }

        if ($selectedKabupaten) {
            $query->whereHas('user.madrasah', function ($q) use ($selectedKabupaten) {
                $q->where('kabupaten', $selectedKabupaten);
            });
        }

        $presensis = $query->orderBy('created_at', 'desc')->get();

        // Analyze each presensi for fake location indicators
        $fakeLocationData = [];
        foreach ($presensis as $presensi) {
            // Use the new database fields if available, otherwise analyze from data
            if ($presensi->is_fake_location !== null) {
                $analysis = [
                    'is_suspicious' => $presensi->is_fake_location,
                    'issues' => $presensi->fake_location_analysis ? $presensi->fake_location_analysis['issues'] : [],
                    'severity' => $presensi->fake_location_analysis ? $presensi->fake_location_analysis['severity'] : 0,
                    'severity_label' => $presensi->fake_location_analysis ? $presensi->fake_location_analysis['severity_label'] : 'Tidak',
                    'distance' => $presensi->user->madrasah && $presensi->user->madrasah->latitude ?
                        $this->calculateDistance(
                            $presensi->user->madrasah->latitude,
                            $presensi->user->madrasah->longitude,
                            $presensi->latitude,
                            $presensi->longitude
                        ) : 0
                ];

                // Tambahkan detail masalah untuk fake GPS detection (updated for 2 readings)
                if ($presensi->fake_location_analysis && isset($presensi->fake_location_analysis['issues'])) {
                    // Check if readings are identical (fake GPS indicator)
                    if (isset($presensi->fake_location_analysis['distances'])) {
                        $distances = $presensi->fake_location_analysis['distances'];
                        if (isset($distances['reading1_reading2']) && $distances['reading1_reading2'] < 0.0001) {
                            // Add specific issue for readings being identical
                            $analysis['issues'][] = 'Kedua pembacaan lokasi identik - indikasi pengguna fake GPS';
                        }
                    }
                }

                // Tambahkan detail masalah untuk presensi di luar waktu yang ditentukan
                $madrasah = $presensi->user->madrasah;
                if ($presensi->waktu_masuk && $madrasah) {
                    $timeRanges = $this->getPresensiTimeRanges($madrasah->hari_kbm, $presensi->tanggal);
                    $presensiTime = Carbon::parse($presensi->waktu_masuk)->format('H:i');

                    if ($presensiTime < $timeRanges['masuk_start'] || $presensiTime > $timeRanges['masuk_end']) {
                        $analysis['issues'][] = 'Presensi diluar waktu yang ditentukan';
                    }
                }

            } else {
                // Fallback to analysis method for older records
                $analysis = $this->analyzePresensiForFakeLocation($presensi);
            }

            if ($analysis['is_suspicious']) {
                $fakeLocationData[] = [
                    'presensi' => $presensi,
                    'analysis' => $analysis
                ];
            }
        }

        // Sort by severity (most suspicious first)
        usort($fakeLocationData, function($a, $b) {
            return count($b['analysis']['issues']) <=> count($a['analysis']['issues']);
        });

        return view('fake-location.index', compact(
            'fakeLocationData',
            'selectedDate',
            'selectedMadrasah',
            'selectedKabupaten',
            'madrasahs',
            'kabupatenList'
        ));
    }

    /**
     * Analyze a presensi record for fake location indicators
     */
    private function analyzePresensiForFakeLocation($presensi)
    {
        $issues = [];
        $severity = 0;

        // Check 1: Invalid coordinates (0,0 or default coordinates)
        if (($presensi->latitude == 0 && $presensi->longitude == 0) ||
            ($presensi->latitude == -7.7956 && $presensi->longitude == 110.3695)) {
            $issues[] = 'Koordinat default atau tidak valid (0,0 atau koordinat template)';
            $severity += 3;
        }

        // Check 2: Coordinates outside madrasah polygon
        $madrasah = $presensi->user->madrasah;
        if ($madrasah && $madrasah->polygon_koordinat) {
            $isInPolygon = $this->isPointInPolygon(
                [$presensi->longitude, $presensi->latitude],
                $madrasah->polygon_koordinat
            );

            if (!$isInPolygon) {
                // Check dual polygon if enabled
                $isInDualPolygon = false;
                if ($madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
                    $isInDualPolygon = $this->isPointInPolygon(
                        [$presensi->longitude, $presensi->latitude],
                        $madrasah->polygon_koordinat_2
                    );
                }

                if (!$isInDualPolygon) {
                    $issues[] = 'Lokasi berada di luar area poligon madrasah';
                    $severity += 2;
                }
            }
        } else {
            $issues[] = 'Madrasah tidak memiliki poligon koordinat yang didefinisikan';
            $severity += 1;
        }

        // Check 3: Suspicious location name
        if ($presensi->lokasi) {
            $suspiciousPatterns = [
                '/\b(test|dummy|fake|contoh|sample)\b/i',
                '/\b(unknown|tidak diketahui|belum diisi)\b/i',
                '/\d{1,2}\.\d{6},\s*\d{1,2}\.\d{6}/', // Raw coordinates in location field
            ];

            foreach ($suspiciousPatterns as $pattern) {
                if (preg_match($pattern, $presensi->lokasi)) {
                    $issues[] = 'Nama lokasi mencurigakan atau berisi koordinat mentah';
                    $severity += 1;
                    break;
                }
            }
        }

        // Check 4: Time outside allowed presensi hours
        if ($presensi->waktu_masuk) {
            $timeRanges = $this->getPresensiTimeRanges($madrasah ? $madrasah->hari_kbm : null, $presensi->tanggal);
            $presensiTime = Carbon::parse($presensi->waktu_masuk)->format('H:i');

            if ($presensiTime < $timeRanges['masuk_start'] || $presensiTime > $timeRanges['masuk_end']) {
                $issues[] = 'Waktu presensi di luar jam yang diperbolehkan';
                $severity += 1;
            }
        }

        // Check 5: Multiple presensi from same IP/location in short time (if we had IP logging)
        // This would require additional logging of IP addresses

        // Check 6: Distance from madrasah center (if coordinates available)
        if ($madrasah && $madrasah->latitude && $madrasah->longitude) {
            $distance = $this->calculateDistance(
                $madrasah->latitude, $madrasah->longitude,
                $presensi->latitude, $presensi->longitude
            );

            // If distance is suspiciously far (>50km), flag it
            if ($distance > 50) {
                $issues[] = 'Jarak dari pusat madrasah terlalu jauh (' . round($distance, 2) . ' km)';
                $severity += 2;
            }
        }

        return [
            'is_suspicious' => count($issues) > 0,
            'issues' => $issues,
            'severity' => $severity,
            'severity_label' => $this->getSeverityLabel($severity)
        ];
    }

    /**
     * Get severity label based on score
     */
    private function getSeverityLabel($severity)
    {
        if ($severity >= 5) return 'Sangat Tinggi';
        if ($severity >= 3) return 'Tinggi';
        if ($severity >= 2) return 'Sedang';
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
     * Get presensi time ranges based on hari_kbm
     */
    private function getPresensiTimeRanges($hariKbm, $date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        if ($hariKbm == '5') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 6) ? '12:00' : '13:00';
        } else {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '13:00';
        }

        return [
            'masuk_start' => $masukStart,
            'masuk_end' => $masukEnd,
            'pulang_start' => $pulangStart,
        ];
    }

    /**
     * Check if point is inside polygon using ray-casting algorithm
     */
    private function isPointInPolygon(array $point, $polygonJson)
    {
        try {
            $polygonGeometry = json_decode($polygonJson, true);
            if (!isset($polygonGeometry['coordinates'][0])) {
                return false;
            }

            $polygon = $polygonGeometry['coordinates'][0];
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
        } catch (\Exception $e) {
            return false;
        }
    }
}
