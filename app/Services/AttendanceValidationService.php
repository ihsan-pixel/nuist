<?php

namespace App\Services;

use App\Models\Madrasah;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AttendanceValidationService
{
    public function schoolContainsPoint(Madrasah $school, float $latitude, float $longitude): bool
    {
        $polygonsToCheck = [];

        if ($school->polygon_koordinat) {
            $polygonsToCheck[] = $school->polygon_koordinat;
        }

        if ($school->enable_dual_polygon && $school->polygon_koordinat_2) {
            $polygonsToCheck[] = $school->polygon_koordinat_2;
        }

        foreach ($polygonsToCheck as $polygonJson) {
            if ($this->polygonGeometryContainsPoint($polygonJson, $latitude, $longitude)) {
                return true;
            }
        }

        return false;
    }

    public function polygonGeometryContainsPoint(?string $polygonJson, float $latitude, float $longitude): bool
    {
        if (!$polygonJson) {
            return false;
        }

        try {
            $polygonGeometry = json_decode($polygonJson, true);

            return isset($polygonGeometry['coordinates'][0])
                && is_array($polygonGeometry['coordinates'][0])
                && $this->isPointInPolygon([$longitude, $latitude], $polygonGeometry['coordinates'][0]);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function validateLocationConsistency(?string $locationReadingsJson): array
    {
        try {
            $readings = json_decode((string) $locationReadingsJson, true);

            if (!is_array($readings) || count($readings) < 2) {
                return ['valid' => true, 'message' => ''];
            }

            $previousReading = null;
            $suspiciousJumpCount = 0;

            foreach ($readings as $reading) {
                if (
                    !is_array($reading)
                    || !isset($reading['latitude'], $reading['longitude'])
                    || !is_numeric($reading['latitude'])
                    || !is_numeric($reading['longitude'])
                ) {
                    continue;
                }

                if ($previousReading) {
                    $distanceKm = $this->calculateDistance(
                        (float) $previousReading['latitude'],
                        (float) $previousReading['longitude'],
                        (float) $reading['latitude'],
                        (float) $reading['longitude']
                    );

                    $timeDiffSeconds = null;
                    if (
                        isset($previousReading['timestamp'], $reading['timestamp'])
                        && is_numeric($previousReading['timestamp'])
                        && is_numeric($reading['timestamp'])
                    ) {
                        $timeDiffSeconds = max(1, abs(((int) $reading['timestamp']) - ((int) $previousReading['timestamp'])) / 1000);
                    }

                    if ($timeDiffSeconds !== null && $timeDiffSeconds <= 60 && $distanceKm > 2) {
                        $suspiciousJumpCount++;
                    }
                }

                $previousReading = $reading;
            }

            if ($suspiciousJumpCount >= 2) {
                return [
                    'valid' => false,
                    'message' => 'Pembacaan lokasi terdeteksi berpindah sangat jauh dalam waktu singkat. Silakan pastikan GPS aktif dan coba kembali.',
                ];
            }

            return ['valid' => true, 'message' => ''];
        } catch (\Throwable $e) {
            return ['valid' => true, 'message' => ''];
        }
    }

    public function validateLocationForFakeGps(
        array $payload,
        User $user,
        bool $isPresensiMasuk,
        ?Presensi $existingPresensi = null,
    ): array {
        $analysis = [
            'accuracy_check' => false,
            'consistency_check' => false,
            'speed_check' => false,
            'location_history_check' => false,
            'suspicious_indicators' => [],
        ];

        $isFake = false;
        $messages = [];
        $accuracy = isset($payload['accuracy']) ? (float) $payload['accuracy'] : null;

        if ($accuracy !== null && $accuracy > 0 && $accuracy < 3) {
            $analysis['accuracy_check'] = true;
            $analysis['suspicious_indicators'][] = 'accuracy_too_perfect';
            $isFake = true;
            $messages[] = 'Akurasi GPS terlalu sempurna (Terindikasi Lokasi Palsu)';
        }

        if (!empty($payload['location_readings'])) {
            $consistencyResult = $this->validateLocationConsistency($payload['location_readings']);
            if (!$consistencyResult['valid']) {
                $analysis['consistency_check'] = true;
                $analysis['suspicious_indicators'][] = 'location_consistency';
                $isFake = true;
                $messages[] = $consistencyResult['message'];
            }
        }

        $latitude = (float) ($payload['latitude'] ?? 0);
        $longitude = (float) ($payload['longitude'] ?? 0);

        if ($isPresensiMasuk) {
            $lastPresensi = Presensi::query()
                ->where('user_id', $user->id)
                ->where('status', 'hadir')
                ->whereDate('tanggal', '<', Carbon::today('Asia/Jakarta')->toDateString())
                ->latest('tanggal')
                ->first();

            if ($lastPresensi?->latitude && $lastPresensi?->longitude) {
                $distance = $this->calculateDistance(
                    (float) $lastPresensi->latitude,
                    (float) $lastPresensi->longitude,
                    $latitude,
                    $longitude
                );

                $lastTime = $lastPresensi->waktu_keluar ?? $lastPresensi->waktu_masuk;
                $hours = $lastTime ? max(Carbon::now('Asia/Jakarta')->diffInHours($lastTime), 1) : 24;
                $speed = $distance / $hours;

                if ($speed > 200) {
                    $analysis['speed_check'] = true;
                    $analysis['suspicious_indicators'][] = 'abnormal_speed';
                    $isFake = true;
                    $messages[] = 'Deteksi pergerakan tidak wajar (kemungkinan teleportasi GPS)';
                }
            }
        } else {
            $sourcePresensi = $existingPresensi;

            if (!$sourcePresensi) {
                $sourcePresensi = Presensi::query()
                    ->where('user_id', $user->id)
                    ->whereNotNull('waktu_masuk')
                    ->whereNull('waktu_keluar')
                    ->latest('tanggal')
                    ->first();
            }

            if ($sourcePresensi?->latitude && $sourcePresensi?->longitude) {
                $distance = $this->calculateDistance(
                    (float) $sourcePresensi->latitude,
                    (float) $sourcePresensi->longitude,
                    $latitude,
                    $longitude
                );

                if ($distance > 5) {
                    $analysis['location_history_check'] = true;
                    $analysis['suspicious_indicators'][] = 'location_jump';
                    $isFake = true;
                    $messages[] = 'Jarak lokasi masuk dan keluar terlalu jauh (kemungkinan fake GPS)';
                }
            }
        }

        $deviceInfo = strtolower(trim((string) ($payload['device_info'] ?? '')));
        if ($deviceInfo !== '') {
            foreach (['fake', 'mock', 'gps', 'location', 'spoof'] as $app) {
                if (str_contains($deviceInfo, $app)) {
                    $analysis['suspicious_indicators'][] = 'device_info_suspicious';
                    $isFake = true;
                    $messages[] = 'Informasi device menunjukkan penggunaan aplikasi GPS palsu';
                    break;
                }
            }
        }

        $latitudeRaw = (string) ($payload['latitude'] ?? '');
        $longitudeRaw = (string) ($payload['longitude'] ?? '');

        if ($latitudeRaw !== '' && $longitudeRaw !== '') {
            $latParts = explode('.', $latitudeRaw);
            $lngParts = explode('.', $longitudeRaw);
            $latDecimals = isset($latParts[1]) ? strlen($latParts[1]) : 0;
            $lngDecimals = isset($lngParts[1]) ? strlen($lngParts[1]) : 0;

            if ($latDecimals > 15 || $lngDecimals > 15) {
                $analysis['suspicious_indicators'][] = 'precision_too_high';
                $isFake = true;
                $messages[] = 'Presisi koordinat GPS tidak wajar';
            }

            if (fmod((float) $payload['latitude'], 1) == 0.0 || fmod((float) $payload['longitude'], 1) == 0.0) {
                $analysis['suspicious_indicators'][] = 'round_coordinates';
                $isFake = true;
                $messages[] = 'Koordinat GPS terlalu bulat (kemungkinan fake)';
            }
        }

        return [
            'is_fake' => $isFake,
            'message' => $messages === [] ? 'Lokasi GPS valid.' : implode('. ', $messages),
            'analysis' => $analysis,
        ];
    }

    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($lonDelta / 2) * sin($lonDelta / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function isValidBase64Image(string $data): bool
    {
        if (strlen($data) < 100) {
            return false;
        }

        if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $data)) {
            return false;
        }

        $base64Data = preg_replace('/^data:image\/(jpeg|jpg|png);base64,/', '', $data);
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64Data)) {
            return false;
        }

        $decoded = base64_decode($base64Data, true);
        if ($decoded === false) {
            return false;
        }

        foreach (["\xFF\xD8\xFF", "\x89\x50\x4E\x47"] as $signature) {
            if (strpos($decoded, $signature) === 0) {
                return true;
            }
        }

        return false;
    }

    public function processAndSaveSelfie(string $selfieData, int $userId, string $tanggal, bool $isMasuk): string
    {
        if (!$this->isValidBase64Image($selfieData)) {
            throw new \RuntimeException('Foto selfie atau hasil scan wajah tidak valid.');
        }

        $path = storage_path('app/public/presensi-selfies');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $type = $isMasuk ? 'masuk' : 'keluar';
            $namaFile = 'selfie_' . $userId . '_' . $type . '_' . time() . '.jpg';
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $selfieData));
            $tempFile = tempnam(sys_get_temp_dir(), 'selfie');
            file_put_contents($tempFile, $imageData);

            $file = new \Illuminate\Http\UploadedFile(
                $tempFile,
                $namaFile,
                'image/jpeg',
                null,
                true
            );

            $file->move($path, $namaFile);
            $fullPath = $path . '/' . $namaFile;

            if (!file_exists($fullPath) || filesize($fullPath) === 0 || !getimagesize($fullPath)) {
                throw new \RuntimeException('File selfie tidak berhasil disimpan.');
            }

            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            return 'presensi-selfies/' . $namaFile;
        } catch (\Throwable $e) {
            Log::error('Attendance selfie processing failed', [
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Gagal memproses foto presensi.');
        }
    }

    public function filterPresensiAttributes(array $attributes): array
    {
        static $columns = null;

        if ($columns === null) {
            try {
                $columns = array_flip(Schema::getColumnListing('presensis'));
            } catch (\Throwable $e) {
                $columns = [];
            }
        }

        if ($columns === []) {
            return $attributes;
        }

        return array_filter(
            $attributes,
            fn ($key) => isset($columns[$key]),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function isPointInPolygon(array $point, array $polygon): bool
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

            if ((($vertexiLat > $pointLat) !== ($vertexjLat > $pointLat))
                && ($pointLng < ($vertexjLng - $vertexiLng) * ($pointLat - $vertexiLat) / ($vertexjLat - $vertexiLat) + $vertexiLng)) {
                $isInside = !$isInside;
            }
        }

        return $isInside;
    }
}
