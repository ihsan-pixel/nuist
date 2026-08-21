<?php

namespace App\Services;

use App\Models\User;

class FaceVerificationService
{
<<<<<<< ours
    private const LIVENESS_THRESHOLD = 0.45;
    private const DEFAULT_FACE_SIMILARITY_THRESHOLD = 0.80;
=======
    private const FACE_DISTANCE_THRESHOLD = 0.72;
    private const FACE_DISTANCE_MARGIN_THRESHOLD = 0.10;
    private const LIVENESS_THRESHOLD = 0.45;
    private const REQUIRED_ATTENDANCE_CHALLENGE = 'blink';
    private const REQUIRED_DYNAMIC_CHALLENGES = ['turn_left', 'turn_right', 'look_up', 'look_down', 'mouth_open'];
    private const SCREEN_REPLAY_RISK_THRESHOLD = 0.55;
>>>>>>> theirs

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
        mixed $embedding = null,
        mixed $deviceInfo = null,
        bool $enforceAdvancedLiveness = false,
    ): array {
        $state = $this->requirementState($user);
<<<<<<< ours
=======

>>>>>>> theirs
        if (!$state['enrolled']) {
            return [
                'success' => false,
                'message' => 'Wajah Anda belum terdaftar. Silakan lakukan pendaftaran wajah sebelum presensi.',
                'notes' => 'face_not_enrolled',
            ];
        }

<<<<<<< ours
        $provided = $this->normalizeDescriptor($embedding);
        if ($provided === []) {
            $provided = $this->normalizeDescriptor($descriptor);
        }
        if ($provided === []) {
=======
        $normalizedDescriptor = $this->normalizeDescriptor($embedding);
        if (empty($normalizedDescriptor)) {
            $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        }
        if (empty($normalizedDescriptor)) {
>>>>>>> theirs
            return [
                'success' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;
<<<<<<< ours
=======

>>>>>>> theirs
        if ($normalizedLivenessScore === null) {
            return [
                'success' => false,
                'message' => 'Skor liveness tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_liveness_score',
            ];
        }

        $storedDescriptors = $this->storedDescriptors($user);
<<<<<<< ours
        if ($storedDescriptors === []) {
=======
        if (empty($storedDescriptors)) {
>>>>>>> theirs
            return [
                'success' => false,
                'message' => 'Data wajah terdaftar tidak dapat dibaca. Silakan lakukan pendaftaran wajah ulang.',
                'notes' => 'stored_face_data_unavailable',
            ];
        }

<<<<<<< ours
        return $this->matchAgainstStoredDescriptors(
            $storedDescriptors,
            $provided,
            $normalizedLivenessScore,
            $deviceInfo,
            $user->face_id,
            $user
        );
=======
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
        $isFlutterClient = is_string($deviceInfo) && str_contains(strtolower($deviceInfo), 'flutter');
        $distanceThreshold = $isFlutterClient ? 1.10 : self::FACE_DISTANCE_THRESHOLD;
        $marginThreshold = $isFlutterClient ? 0.02 : self::FACE_DISTANCE_MARGIN_THRESHOLD;

        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < $marginThreshold) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'face_distance' => is_finite($bestDistance) ? round($bestDistance, 4) : null,
                    'face_distance_threshold' => $distanceThreshold,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => $marginThreshold,
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
                'face_distance_threshold' => $distanceThreshold,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'liveness_below_threshold',
            ];
        }

        if ($bestDistance > $distanceThreshold) {
            return [
                'success' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => $distanceThreshold,
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
            'face_distance_threshold' => $distanceThreshold,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'device_info' => $deviceInfo,
            'notes' => 'face_verified',
        ];
>>>>>>> theirs
    }

    public function identifyBestMatchingUser(
        iterable $users,
        mixed $descriptor,
        mixed $livenessScore,
        mixed $challenges,
        bool $enforceAdvancedLiveness = false,
    ): array {
<<<<<<< ours
        $provided = $this->normalizeDescriptor($descriptor);
        if ($provided === []) {
=======
        $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        if (empty($normalizedDescriptor)) {
>>>>>>> theirs
            return [
                'success' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;
<<<<<<< ours
=======

>>>>>>> theirs
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
<<<<<<< ours
        $bestSimilarity = null;
        $secondBestSimilarity = null;
=======
        $bestDistance = null;
        $secondBestDistance = null;
>>>>>>> theirs

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
<<<<<<< ours
            if ($storedDescriptors === []) {
=======
            if (empty($storedDescriptors)) {
>>>>>>> theirs
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
<<<<<<< ours
                $similarity = $this->cosineSimilarity($storedDescriptor, $provided);
                if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                    $secondBestSimilarity = $bestSimilarity;
                    $bestSimilarity = $similarity;
                    $bestUser = $user;
                } elseif ($secondBestSimilarity === null || $similarity > $secondBestSimilarity) {
                    $secondBestSimilarity = $similarity;
=======
                $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
                if ($bestDistance === null || $distance < $bestDistance) {
                    $secondBestDistance = $bestDistance;
                    $bestDistance = $distance;
                    $bestUser = $user;
                } elseif ($secondBestDistance === null || $distance < $secondBestDistance) {
                    $secondBestDistance = $distance;
>>>>>>> theirs
                }
            }
        }

<<<<<<< ours
        if (!$bestUser || $bestSimilarity === null) {
=======
        if (!$bestUser || $bestDistance === null) {
>>>>>>> theirs
            return [
                'success' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'no_enrolled_teachers',
            ];
        }

<<<<<<< ours
        $threshold = $this->faceSimilarityThreshold();
        if ($secondBestSimilarity !== null) {
            $similarityGap = $bestSimilarity - $secondBestSimilarity;
            if ($similarityGap < 0.02) {
=======
        $bestSimilarity = $this->distanceToSimilarity($bestDistance);
        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < self::FACE_DISTANCE_MARGIN_THRESHOLD) {
>>>>>>> theirs
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
<<<<<<< ours
                    'matched' => false,
                    'threshold' => $threshold,
                    'similarity_gap' => round($similarityGap, 4),
                    'similarity_gap_threshold' => 0.02,
=======
                    'face_distance' => round($bestDistance, 4),
                    'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => self::FACE_DISTANCE_MARGIN_THRESHOLD,
>>>>>>> theirs
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }
<<<<<<< ours

        if ($bestSimilarity < $threshold) {
=======
        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
>>>>>>> theirs
            return [
                'success' => false,
                'message' => 'Wajah tidak dikenali. Pastikan guru sudah mendaftarkan wajah yang benar.',
                'similarity' => round($bestSimilarity, 4),
<<<<<<< ours
                'matched' => false,
                'threshold' => $threshold,
=======
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
>>>>>>> theirs
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
<<<<<<< ours
            'matched' => true,
            'threshold' => $threshold,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'embedding_dimension' => 128,
            'embedding_norm' => 1.0,
=======
            'face_distance' => round($bestDistance, 4),
            'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
            'liveness_score' => round($normalizedLivenessScore, 4),
>>>>>>> theirs
            'notes' => 'face_identified',
        ];
    }

    public function identifyByDescriptorOnly(iterable $users, mixed $descriptor): array
    {
<<<<<<< ours
        $provided = $this->normalizeDescriptor($descriptor);
        if ($provided === []) {
=======
        $normalizedDescriptor = $this->normalizeDescriptor($descriptor);
        if (empty($normalizedDescriptor)) {
>>>>>>> theirs
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $bestUser = null;
<<<<<<< ours
        $bestSimilarity = null;
        $secondBestSimilarity = null;
=======
        $bestDistance = null;
        $secondBestDistance = null;
>>>>>>> theirs

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
<<<<<<< ours
            if ($storedDescriptors === []) {
=======
            if (empty($storedDescriptors)) {
>>>>>>> theirs
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
<<<<<<< ours
                $similarity = $this->cosineSimilarity($storedDescriptor, $provided);
                if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                    $secondBestSimilarity = $bestSimilarity;
                    $bestSimilarity = $similarity;
                    $bestUser = $user;
                } elseif ($secondBestSimilarity === null || $similarity > $secondBestSimilarity) {
                    $secondBestSimilarity = $similarity;
=======
                $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
                if ($bestDistance === null || $distance < $bestDistance) {
                    $secondBestDistance = $bestDistance;
                    $bestDistance = $distance;
                    $bestUser = $user;
                } elseif ($secondBestDistance === null || $distance < $secondBestDistance) {
                    $secondBestDistance = $distance;
>>>>>>> theirs
                }
            }
        }

<<<<<<< ours
        if (!$bestUser || $bestSimilarity === null) {
=======
        if (!$bestUser || $bestDistance === null) {
>>>>>>> theirs
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'notes' => 'no_enrolled_teachers',
            ];
        }

<<<<<<< ours
        $threshold = $this->faceSimilarityThreshold();
        if ($secondBestSimilarity !== null) {
            $similarityGap = $bestSimilarity - $secondBestSimilarity;
            if ($similarityGap < 0.02) {
=======
        $bestSimilarity = $this->distanceToSimilarity($bestDistance);
        if ($secondBestDistance !== null) {
            $distanceGap = $secondBestDistance - $bestDistance;
            if ($distanceGap < self::FACE_DISTANCE_MARGIN_THRESHOLD) {
>>>>>>> theirs
                return [
                    'success' => false,
                    'face_verified' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
<<<<<<< ours
                    'matched' => false,
                    'threshold' => $threshold,
                    'similarity_gap' => round($similarityGap, 4),
                    'similarity_gap_threshold' => 0.02,
=======
                    'face_distance' => round($bestDistance, 4),
                    'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                    'face_distance_gap' => round($distanceGap, 4),
                    'face_distance_gap_threshold' => self::FACE_DISTANCE_MARGIN_THRESHOLD,
>>>>>>> theirs
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }
<<<<<<< ours

        if ($bestSimilarity < $threshold) {
=======
        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
>>>>>>> theirs
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
<<<<<<< ours
                'matched' => false,
                'threshold' => $threshold,
=======
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
>>>>>>> theirs
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
<<<<<<< ours
            'matched' => true,
            'threshold' => $threshold,
=======
            'face_distance' => round($bestDistance, 4),
            'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
>>>>>>> theirs
            'notes' => 'face_verified',
        ];
    }

<<<<<<< ours
    public function compareFaceEmbeddings(array $storedEmbedding, array $providedEmbedding): array
    {
        $stored = $this->normalizeDescriptor($storedEmbedding);
        $provided = $this->normalizeDescriptor($providedEmbedding);

        if ($stored === [] || $provided === []) {
            return [
                'similarity' => -1.0,
                'matched' => false,
                'threshold' => $this->faceSimilarityThreshold(),
            ];
        }

        $similarity = $this->cosineSimilarity($stored, $provided);
        $threshold = $this->faceSimilarityThreshold();

        return [
            'similarity' => round($similarity, 4),
            'matched' => $similarity >= $threshold,
            'threshold' => $threshold,
        ];
    }

=======
>>>>>>> theirs
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

        if (isset($stored['face_embedding'])) {
            $descriptor = $this->normalizeDescriptor($stored['face_embedding']);
<<<<<<< ours
=======

>>>>>>> theirs
            if ($descriptor !== []) {
                return [$descriptor];
            }
        }

        if (isset($stored['descriptors']) && is_array($stored['descriptors'])) {
            $descriptors = collect($stored['descriptors'])
                ->map(fn ($item) => $this->normalizeDescriptor($item))
                ->filter(fn ($item) => $item !== [])
                ->values()
                ->all();

<<<<<<< ours
            if ($descriptors !== []) {
=======
            if (!empty($descriptors)) {
>>>>>>> theirs
                return $descriptors;
            }
        }

        if (isset($stored['face_descriptor'])) {
            $descriptor = $this->normalizeDescriptor($stored['face_descriptor']);
<<<<<<< ours
=======

>>>>>>> theirs
            return $descriptor === [] ? [] : [$descriptor];
        }

        $descriptor = $this->normalizeDescriptor($stored);
<<<<<<< ours
=======

>>>>>>> theirs
        return $descriptor === [] ? [] : [$descriptor];
    }

    private function normalizeDescriptor(mixed $descriptor): array
    {
        if (!is_array($descriptor)) {
            return [];
        }

        $normalized = [];
<<<<<<< ours
=======

>>>>>>> theirs
        foreach ($descriptor as $value) {
            if (!is_numeric($value)) {
                return [];
            }

<<<<<<< ours
            $floatValue = (float) $value;
            if (!is_finite($floatValue)) {
                return [];
            }
            $normalized[] = $floatValue;
        }

        if (count($normalized) !== 128) {
            return [];
        }

        $norm = sqrt(array_sum(array_map(fn ($value) => $value * $value, $normalized)));
        if ($norm > 0) {
            foreach ($normalized as $index => $value) {
                $normalized[$index] = $value / $norm;
            }
        }

        return $normalized;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (count($a) !== 128 || count($b) !== 128) {
            return -1.0;
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        for ($i = 0; $i < 128; $i++) {
            $va = (float) $a[$i];
            $vb = (float) $b[$i];
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }

        if ($normA <= 0 || $normB <= 0) {
            return -1.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
=======
            $normalized[] = (float) $value;
        }

        return count($normalized) === 128 ? $normalized : [];
>>>>>>> theirs
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

<<<<<<< ours
    private function faceSimilarityThreshold(): float
    {
        $threshold = config('kiosk_face.similarity_threshold', self::DEFAULT_FACE_SIMILARITY_THRESHOLD);

        return is_numeric($threshold) ? (float) $threshold : self::DEFAULT_FACE_SIMILARITY_THRESHOLD;
=======
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
>>>>>>> theirs
    }
}
