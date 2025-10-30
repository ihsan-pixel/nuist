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

        // Analyze each presensi for fake location indicators (both masuk and keluar)
        $fakeLocationData = [];
        foreach ($presensis as $presensi) {
            $issues = [];

            // Check if this presensi has fake location detection data for masuk
            if ($presensi->is_fake_location && $presensi->fake_location_analysis) {
                $analysisData = $presensi->fake_location_analysis;

                // Handle both JSON string and array formats (for backward compatibility)
                if (is_string($analysisData)) {
                    $analysisData = json_decode($analysisData, true);
                }

                if ($analysisData) {
                    // Generate detailed issues based on the stored analysis
                    $issues = $this->generateDetailedIssues($analysisData, $presensi, 'masuk');
                }
            }

            // Check if this presensi has fake location detection data for keluar
            if ($presensi->is_fake_location_keluar && $presensi->fake_location_analysis_keluar) {
                $analysisDataKeluar = $presensi->fake_location_analysis_keluar;

                // Handle both JSON string and array formats (for backward compatibility)
                if (is_string($analysisDataKeluar)) {
                    $analysisDataKeluar = json_decode($analysisDataKeluar, true);
                }

                if ($analysisDataKeluar) {
                    // Generate detailed issues based on the stored analysis for keluar
                    $keluarIssues = $this->generateDetailedIssues($analysisDataKeluar, $presensi, 'keluar');
                    $issues = array_merge($issues, $keluarIssues);
                }
            }

            // Tambahkan detail masalah untuk presensi di luar waktu yang ditentukan
            $madrasah = $presensi->user->madrasah;
            if ($presensi->waktu_masuk && $madrasah) {
                $timeRanges = $this->getPresensiTimeRanges($madrasah->hari_kbm, $presensi->tanggal);
                $presensiTime = Carbon::parse($presensi->waktu_masuk)->format('H:i');

                if ($presensiTime < $timeRanges['masuk_start'] || $presensiTime > $timeRanges['masuk_end']) {
                    $issues[] = 'Presensi masuk diluar waktu yang ditentukan';
                }
            }

            // Check waktu keluar jika ada
            if ($presensi->waktu_keluar && $madrasah) {
                $timeRanges = $this->getPresensiTimeRanges($madrasah->hari_kbm, $presensi->tanggal);
                $keluarTime = Carbon::parse($presensi->waktu_keluar)->format('H:i');

                if ($keluarTime < $timeRanges['pulang_start']) {
                    $issues[] = 'Presensi keluar diluar waktu yang ditentukan';
                }
            }

            $isSuspicious = $presensi->is_fake_location || $presensi->is_fake_location_keluar;

            $analysis = [
                'is_suspicious' => $isSuspicious,
                'issues' => $issues,
                'severity' => count($issues),
                'severity_label' => $this->getSeverityLabel(count($issues)),
                'distance' => $presensi->user->madrasah && $presensi->user->madrasah->latitude ?
                    $this->calculateDistance(
                        $presensi->user->madrasah->latitude,
                        $presensi->user->madrasah->longitude,
                        $presensi->latitude,
                        $presensi->longitude
                    ) : 0,
                'fake_location_masuk' => $presensi->is_fake_location,
                'fake_location_keluar' => $presensi->is_fake_location_keluar
            ];

            if ($isSuspicious) {
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
     * Generate detailed issues from stored fake location analysis
     */
    private function generateDetailedIssues($analysisData, $presensi, $type = 'masuk')
    {
        $issues = [];

        $reason = $analysisData['reason'] ?? '';
        $detectedAt = $analysisData['detected_at'] ?? '';

        // Count how many times the same coordinate appears
        $coordinateCounts = [];
        $accuracyCounts = [];

        // Collect all readings
        $readings = [];
        foreach ($analysisData as $key => $value) {
            if (preg_match('/^reading\d+$/', $key) && is_array($value)) {
                $readings[] = $value;
                $coordKey = number_format($value['latitude'], 6) . ',' . number_format($value['longitude'], 6);
                $coordinateCounts[$coordKey] = ($coordinateCounts[$coordKey] ?? 0) + 1;

                if (isset($value['accuracy'])) {
                    $accuracyKey = number_format($value['accuracy'], 1);
                    $accuracyCounts[$accuracyKey] = ($accuracyCounts[$accuracyKey] ?? 0) + 1;
                }
            }
        }

        // Find the most common coordinate
        $mostCommonCoord = '';
        $maxCount = 0;
        foreach ($coordinateCounts as $coord => $count) {
            if ($count > $maxCount) {
                $maxCount = $count;
                $mostCommonCoord = $coord;
            }
        }

        // Find the most common accuracy
        $mostCommonAccuracy = '';
        $maxAccuracyCount = 0;
        foreach ($accuracyCounts as $accuracy => $count) {
            if ($count > $maxAccuracyCount) {
                $maxAccuracyCount = $count;
                $mostCommonAccuracy = $accuracy;
            }
        }

        // Generate issues based on the reason
        $typeLabel = $type === 'keluar' ? 'keluar' : 'masuk';
        if (strpos($reason, 'All 4 location readings are identical') !== false) {
            if ($mostCommonCoord) {
                $issues[] = 'Koordinat ' . $mostCommonCoord . ' muncul ' . $maxCount . ' kali - indikasi fake GPS (' . $typeLabel . ')';
            }
            $issues[] = 'Semua 4 pembacaan lokasi memiliki koordinat yang identik (' . $typeLabel . ')';
            if ($mostCommonAccuracy && $mostCommonAccuracy <= 1) {
                $issues[] = 'Akurasi lokasi terlalu sempurna (' . $mostCommonAccuracy . ' meter) pada ' . $maxAccuracyCount . ' pembacaan (' . $typeLabel . ')';
            }
        } elseif (strpos($reason, 'Identical coordinates between') !== false) {
            if ($mostCommonCoord) {
                $issues[] = 'Koordinat ' . $mostCommonCoord . ' muncul ' . $maxCount . ' kali - indikasi fake GPS (' . $typeLabel . ')';
            }
            $issues[] = 'Koordinat terdeteksi: ' . $mostCommonCoord . ' muncul ' . $maxCount . ' kali (' . $typeLabel . ')';
            if ($mostCommonAccuracy && $mostCommonAccuracy <= 1) {
                $issues[] = 'Akurasi lokasi terlalu sempurna (' . $mostCommonAccuracy . ' meter) pada ' . $maxAccuracyCount . ' pembacaan (' . $typeLabel . ')';
            }
        } elseif (strpos($reason, 'Location readings are too close together') !== false) {
            $issues[] = 'Pembacaan lokasi terlalu berdekatan (< 10 meter) (' . $typeLabel . ')';
            if ($mostCommonCoord) {
                $issues[] = 'Koordinat ' . $mostCommonCoord . ' muncul ' . $maxCount . ' kali - indikasi fake GPS (' . $typeLabel . ')';
            }
        } elseif (strpos($reason, 'Suspicious time differences') !== false) {
            $issues[] = 'Waktu pembacaan lokasi tidak natural (' . $typeLabel . ')';
            if ($mostCommonCoord) {
                $issues[] = 'Koordinat ' . $mostCommonCoord . ' muncul ' . $maxCount . ' kali - indikasi fake GPS (' . $typeLabel . ')';
            }
        }

        // Add general issues if we have suspicious patterns
        if (count($readings) >= 4) {
            // Check for perfect accuracy across readings
            $perfectAccuracyCount = 0;
            foreach ($readings as $reading) {
                if (isset($reading['accuracy']) && $reading['accuracy'] <= 1) {
                    $perfectAccuracyCount++;
                }
            }
            if ($perfectAccuracyCount >= 4) {
                $issues[] = 'Akurasi lokasi terlalu sempurna (≤1 meter) pada 4 pembacaan';
            }
        }

        return $issues;
    }

    /**
     * Analyze a presensi record for fake location indicators (fallback)
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
