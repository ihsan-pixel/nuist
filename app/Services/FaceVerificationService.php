<?php

namespace App\Services;

use App\Models\User;

class FaceVerificationService
{
    private const FACE_DISTANCE_THRESHOLD = 0.68;
    private const FACE_DISTANCE_MARGIN_THRESHOLD = 0.03;
    private const LIVENESS_THRESHOLD = 0.45;
    private const REQUIRED_ATTENDANCE_CHALLENGE = 'blink';
    private const REQUIRED_DYNAMIC_CHALLENGES = ['turn_left', 'turn_right', 'look_up', 'look_down', 'mouth_open'];
    private const SCREEN_REPLAY_RISK_THRESHOLD = 0.55;

    public function requirementState(User $user): array
    {
        $hasEnrollment = $this->hasEnrollment($user);

        return [
            'required' => true,
            'enrolled' => $hasEnrollment,
            'registered_at' => optional($user->face_registered_at)?->toIso8601String(),
            'message' => $hasEnrollment
                ? 'Scan wajah aktif untuk presensi kehadiran.'
                : 'Wajah Anda belum terdaftar. Lakukan pendaftaran wajah sebelum presensi.',
        ];
    }

    public function verifyForAttendance(
        User $user,
        mixed $descriptor,
        mixed $livenessScore,
        mixed $challenges,
        bool $enforceAdvancedLiveness = false,
    ): array {
        $state = $this->requirementState($user);

        if (!$state['enrolled']) {
            return [
                'success' => false,
                'message' => 'Wajah Anda belum terdaftar. Silakan lakukan pendaftaran wajah sebelum presensi.',
                'notes' => 'face_not_enrolled',
            ];
        }

        $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        if (empty($normalizedDescriptor)) {
            return [
                'success' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;

        if ($normalizedLivenessScore === null) {
            return [
                'success' => false,
                'message' => 'Skor liveness tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_liveness_score',
            ];
        }

        $storedDescriptors = $this->storedDescriptors($user);
        if (empty($storedDescriptors)) {
            return [
                'success' => false,
                'message' => 'Data wajah terdaftar tidak dapat dibaca. Silakan lakukan pendaftaran wajah ulang.',
                'notes' => 'stored_face_data_unavailable',
            ];
        }

        $bestDistance = null;
        $secondBestDistance = null;

        foreach ($storedDescriptors as $storedDescriptor) {
            $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
            if ($bestDistance === null || $distance < $bestDistance) {
                $secondBestDistance = $bestDistance;
                $bestDistance = $distance;
            } elseif ($secondBestDistance === null || $distance < $secondBestDistance) {
                $secondBestDistance = $distance;
            }
        }

        $bestDistance ??= INF;
        $bestSimilarity = $this->distanceToSimilarity($bestDistance);

        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < self::FACE_DISTANCE_MARGIN_THRESHOLD) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'face_distance' => is_finite($bestDistance) ? round($bestDistance, 4) : null,
                    'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => self::FACE_DISTANCE_MARGIN_THRESHOLD,
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }

        if ($normalizedLivenessScore < self::LIVENESS_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Scan wajah gagal diverifikasi. Wajah belum terbaca dengan cukup stabil.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => is_finite($bestDistance) ? round($bestDistance, 4) : null,
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'liveness_below_threshold',
            ];
        }

        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'face_similarity_below_threshold',
            ];
        }

        return [
            'success' => true,
            'message' => 'Scan wajah berhasil diverifikasi.',
            'face_id_used' => $user->face_id,
            'similarity' => round($bestSimilarity, 4),
            'face_distance' => round($bestDistance, 4),
            'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'notes' => 'face_verified',
        ];
    }

    public function identifyBestMatchingUser(
        iterable $users,
        mixed $descriptor,
        mixed $livenessScore,
        mixed $challenges,
        bool $enforceAdvancedLiveness = false,
    ): array {
        $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        if (empty($normalizedDescriptor)) {
            return [
                'success' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;

        if ($normalizedLivenessScore === null) {
            return [
                'success' => false,
                'message' => 'Skor liveness tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_liveness_score',
            ];
        }

        if ($normalizedLivenessScore < self::LIVENESS_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Scan wajah gagal diverifikasi. Wajah belum terbaca dengan cukup stabil.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'liveness_below_threshold',
            ];
        }

        $bestUser = null;
        $bestDistance = null;
        $secondBestDistance = null;

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
            if (empty($storedDescriptors)) {
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
                $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
                if ($bestDistance === null || $distance < $bestDistance) {
                    $secondBestDistance = $bestDistance;
                    $bestDistance = $distance;
                    $bestUser = $user;
                } elseif ($secondBestDistance === null || $distance < $secondBestDistance) {
                    $secondBestDistance = $distance;
                }
            }
        }

        if (!$bestUser || $bestDistance === null) {
            return [
                'success' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'no_enrolled_teachers',
            ];
        }

        $bestSimilarity = $this->distanceToSimilarity($bestDistance);
        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < self::FACE_DISTANCE_MARGIN_THRESHOLD) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'face_distance' => round($bestDistance, 4),
                    'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => self::FACE_DISTANCE_MARGIN_THRESHOLD,
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }
        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Wajah tidak dikenali. Pastikan guru sudah mendaftarkan wajah yang benar.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'face_similarity_below_threshold',
            ];
        }

        return [
            'success' => true,
            'message' => 'Identitas wajah berhasil dikenali.',
            'user' => $bestUser,
            'face_id_used' => $bestUser->face_id,
            'similarity' => round($bestSimilarity, 4),
            'face_distance' => round($bestDistance, 4),
            'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'notes' => 'face_identified',
        ];
    }

    public function identifyByDescriptorOnly(iterable $users, mixed $descriptor): array
    {
        $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        if (empty($normalizedDescriptor)) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $bestUser = null;
        $bestDistance = null;
        $secondBestDistance = null;

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
            if (empty($storedDescriptors)) {
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
                $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
                if ($bestDistance === null || $distance < $bestDistance) {
                    $secondBestDistance = $bestDistance;
                    $bestDistance = $distance;
                    $bestUser = $user;
                } elseif ($secondBestDistance === null || $distance < $secondBestDistance) {
                    $secondBestDistance = $distance;
                }
            }
        }

        if (!$bestUser || $bestDistance === null) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'notes' => 'no_enrolled_teachers',
            ];
        }

        $bestSimilarity = $this->distanceToSimilarity($bestDistance);
        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < self::FACE_DISTANCE_MARGIN_THRESHOLD) {
                return [
                    'success' => false,
                    'face_verified' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'face_distance' => round($bestDistance, 4),
                    'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => self::FACE_DISTANCE_MARGIN_THRESHOLD,
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }
        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'notes' => 'face_similarity_below_threshold',
            ];
        }

        return [
            'success' => true,
            'face_verified' => true,
            'message' => 'Wajah cocok dengan data terdaftar.',
            'user' => $bestUser,
            'face_id_used' => $bestUser->face_id,
            'similarity' => round($bestSimilarity, 4),
            'face_distance' => round($bestDistance, 4),
            'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
            'notes' => 'face_verified',
        ];
    }

    private function hasEnrollment(User $user): bool
    {
        return !empty($this->storedDescriptors($user));
    }

    private function storedDescriptors(User $user): array
    {
        $stored = $user->decodedFaceData();

        if (!is_array($stored) || $stored === []) {
            return [];
        }

        if (isset($stored['face_descriptor'])) {
            $descriptor = $this->normalizeDescriptor($stored['face_descriptor']);

            return $descriptor === [] ? [] : [$descriptor];
        }

        if (isset($stored['descriptors']) && is_array($stored['descriptors'])) {
            return collect($stored['descriptors'])
                ->map(fn ($item) => $this->normalizeDescriptor($item))
                ->filter(fn ($item) => $item !== [])
                ->values()
                ->all();
        }

        $descriptor = $this->normalizeDescriptor($stored);

        return $descriptor === [] ? [] : [$descriptor];
    }

    private function normalizeDescriptor(mixed $descriptor): array
    {
        if (!is_array($descriptor)) {
            return [];
        }

        $normalized = [];

        foreach ($descriptor as $value) {
            if (!is_numeric($value)) {
                return [];
            }

            $normalized[] = (float) $value;
        }

        return count($normalized) === 128 ? $normalized : [];
    }

    private function normalizeChallenges(mixed $challenges): array
    {
        if (!is_array($challenges)) {
            return [];
        }

        return collect($challenges)
            ->filter(fn ($challenge) => is_array($challenge))
            ->map(function (array $challenge) {
                return [
                    'type' => (string) ($challenge['type'] ?? 'unknown'),
                    'passed' => (bool) ($challenge['passed'] ?? false),
                    'score' => is_numeric($challenge['score'] ?? null) ? round((float) $challenge['score'], 4) : null,
                    'detail' => isset($challenge['detail']) ? (string) $challenge['detail'] : null,
                    'timestamp' => $challenge['timestamp'] ?? now()->timestamp,
                ];
            })
            ->values()
            ->all();
    }

    private function hasPassedChallenge(array $challenges, string $type): bool
    {
        return collect($challenges)->contains(function (array $challenge) use ($type) {
            return ($challenge['type'] ?? null) === $type
                && ($challenge['passed'] ?? false) === true;
        });
    }

    private function firstPassedChallenge(array $challenges, array $types): ?array
    {
        $found = collect($challenges)->first(function (array $challenge) use ($types) {
            return in_array($challenge['type'] ?? null, $types, true)
                && ($challenge['passed'] ?? false) === true;
        });

        return is_array($found) ? $found : null;
    }

    private function findChallenge(array $challenges, string $type): ?array
    {
        $found = collect($challenges)->first(function (array $challenge) use ($type) {
            return ($challenge['type'] ?? null) === $type;
        });

        return is_array($found) ? $found : null;
    }

    private function euclideanDistance(array $first, array $second): float
    {
        if (count($first) !== 128 || count($second) !== 128) {
            return INF;
        }

        $sum = 0.0;

        for ($index = 0; $index < 128; $index++) {
            $delta = (float) $first[$index] - (float) $second[$index];
            $sum += $delta * $delta;
        }

        return sqrt($sum);
    }

    private function distanceToSimilarity(float $distance): float
    {
        return is_finite($distance) ? max(0.0, 1.0 - $distance) : 0.0;
    }
}
