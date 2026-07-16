<?php

namespace App\Services;

use App\Models\User;

class FaceVerificationService
{
    private const FACE_DISTANCE_THRESHOLD = 0.55;
    private const LIVENESS_THRESHOLD = 0.78;
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

        $normalizedChallenges = $this->normalizeChallenges($challenges);
        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;

        if ($normalizedLivenessScore === null) {
            return [
                'success' => false,
                'message' => 'Skor liveness tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_liveness_score',
            ];
        }

        if (!$this->hasPassedChallenge($normalizedChallenges, self::REQUIRED_ATTENDANCE_CHALLENGE)) {
            return [
                'success' => false,
                'message' => 'Kedipan belum terverifikasi. Ulangi scan wajah lalu kedip satu kali.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
                'notes' => 'blink_challenge_missing',
            ];
        }

        if ($enforceAdvancedLiveness) {
            $dynamicChallenge = $this->firstPassedChallenge($normalizedChallenges, self::REQUIRED_DYNAMIC_CHALLENGES);
            if (!$dynamicChallenge) {
                return [
                    'success' => false,
                    'message' => 'Challenge wajah tambahan belum terpenuhi. Ulangi scan dan ikuti arahan gerakan wajah.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'dynamic_challenge_missing',
                ];
            }

            $lightingChallenge = $this->findChallenge($normalizedChallenges, 'lighting');
            if ($lightingChallenge && !$lightingChallenge['passed']) {
                return [
                    'success' => false,
                    'message' => 'Pencahayaan wajah belum cukup baik. Dekatkan wajah dan gunakan cahaya yang lebih jelas.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'lighting_insufficient',
                ];
            }

            $screenReplayRisk = $this->findChallenge($normalizedChallenges, 'screen_replay_risk');
            if (($screenReplayRisk['score'] ?? 0) > self::SCREEN_REPLAY_RISK_THRESHOLD) {
                return [
                    'success' => false,
                    'message' => 'Scan wajah terdeteksi berisiko seperti layar atau replay video. Gunakan wajah asli di depan kamera.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'screen_replay_risk_high',
                ];
            }
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

        foreach ($storedDescriptors as $storedDescriptor) {
            $distance = $this->euclideanDistance($storedDescriptor, $normalizedDescriptor);
            if ($bestDistance === null || $distance < $bestDistance) {
                $bestDistance = $distance;
            }
        }

        $bestDistance ??= INF;
        $bestSimilarity = $this->distanceToSimilarity($bestDistance);

        if ($normalizedLivenessScore < self::LIVENESS_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Scan wajah gagal diverifikasi. Wajah belum terbaca dengan cukup stabil.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => is_finite($bestDistance) ? round($bestDistance, 4) : null,
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
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
                'challenges' => $normalizedChallenges,
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
            'challenges' => $normalizedChallenges,
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

        $normalizedChallenges = $this->normalizeChallenges($challenges);
        $normalizedLivenessScore = is_numeric($livenessScore) ? (float) $livenessScore : null;

        if ($normalizedLivenessScore === null) {
            return [
                'success' => false,
                'message' => 'Skor liveness tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_liveness_score',
            ];
        }

        if (!$this->hasPassedChallenge($normalizedChallenges, self::REQUIRED_ATTENDANCE_CHALLENGE)) {
            return [
                'success' => false,
                'message' => 'Kedipan belum terverifikasi. Ulangi scan wajah lalu kedip satu kali.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
                'notes' => 'blink_challenge_missing',
            ];
        }

        if ($enforceAdvancedLiveness) {
            $dynamicChallenge = $this->firstPassedChallenge($normalizedChallenges, self::REQUIRED_DYNAMIC_CHALLENGES);
            if (!$dynamicChallenge) {
                return [
                    'success' => false,
                    'message' => 'Challenge wajah tambahan belum terpenuhi. Ulangi scan dan ikuti arahan gerakan wajah.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'dynamic_challenge_missing',
                ];
            }

            $lightingChallenge = $this->findChallenge($normalizedChallenges, 'lighting');
            if ($lightingChallenge && !$lightingChallenge['passed']) {
                return [
                    'success' => false,
                    'message' => 'Pencahayaan wajah belum cukup baik. Dekatkan wajah dan gunakan cahaya yang lebih jelas.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'lighting_insufficient',
                ];
            }

            $screenReplayRisk = $this->findChallenge($normalizedChallenges, 'screen_replay_risk');
            if (($screenReplayRisk['score'] ?? 0) > self::SCREEN_REPLAY_RISK_THRESHOLD) {
                return [
                    'success' => false,
                    'message' => 'Scan wajah terdeteksi berisiko seperti layar atau replay video. Gunakan wajah asli di depan kamera.',
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'challenges' => $normalizedChallenges,
                    'notes' => 'screen_replay_risk_high',
                ];
            }
        }

        if ($normalizedLivenessScore < self::LIVENESS_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Scan wajah gagal diverifikasi. Wajah belum terbaca dengan cukup stabil.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
                'notes' => 'liveness_below_threshold',
            ];
        }

        $bestUser = null;
        $bestDistance = null;

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
                    $bestDistance = $distance;
                    $bestUser = $user;
                }
            }
        }

        if (!$bestUser || $bestDistance === null) {
            return [
                'success' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
                'notes' => 'no_enrolled_teachers',
            ];
        }

        $bestSimilarity = $this->distanceToSimilarity($bestDistance);
        if ($bestDistance > self::FACE_DISTANCE_THRESHOLD) {
            return [
                'success' => false,
                'message' => 'Wajah tidak dikenali. Pastikan guru sudah mendaftarkan wajah yang benar.',
                'similarity' => round($bestSimilarity, 4),
                'face_distance' => round($bestDistance, 4),
                'face_distance_threshold' => self::FACE_DISTANCE_THRESHOLD,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'challenges' => $normalizedChallenges,
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
            'challenges' => $normalizedChallenges,
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
                    $bestDistance = $distance;
                    $bestUser = $user;
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
