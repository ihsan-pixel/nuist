<?php

namespace App\Services;

use App\Models\User;

class FaceVerificationService
{
    private const LIVENESS_THRESHOLD = 0.55;
    private const DEFAULT_FACE_SIMILARITY_THRESHOLD = 0.90;

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
        if (!$state['enrolled']) {
            return [
                'success' => false,
                'message' => 'Wajah Anda belum terdaftar. Silakan lakukan pendaftaran wajah sebelum presensi.',
                'notes' => 'face_not_enrolled',
            ];
        }

        $provided = $this->normalizeDescriptor($embedding);
        if ($provided === []) {
            $provided = $this->normalizeDescriptor($descriptor);
        }
        if ($provided === []) {
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
        if ($storedDescriptors === []) {
            return [
                'success' => false,
                'message' => 'Data wajah terdaftar tidak dapat dibaca. Silakan lakukan pendaftaran wajah ulang.',
                'notes' => 'stored_face_data_unavailable',
            ];
        }

        return $this->matchAgainstStoredDescriptors(
            $storedDescriptors,
            $provided,
            $normalizedLivenessScore,
            $deviceInfo,
            $user->face_id,
            $user
        );
    }

    public function identifyBestMatchingUser(
        iterable $users,
        mixed $descriptor,
        mixed $livenessScore,
        mixed $challenges,
        bool $enforceAdvancedLiveness = false,
    ): array {
        $provided = $this->normalizeDescriptor($descriptor);
        if ($provided === []) {
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
        $bestSimilarity = null;
        $secondBestSimilarity = null;

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
            if ($storedDescriptors === []) {
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
                $similarity = $this->cosineSimilarity($storedDescriptor, $provided);
                if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                    $secondBestSimilarity = $bestSimilarity;
                    $bestSimilarity = $similarity;
                    $bestUser = $user;
                } elseif ($secondBestSimilarity === null || $similarity > $secondBestSimilarity) {
                    $secondBestSimilarity = $similarity;
                }
            }
        }

        if (!$bestUser || $bestSimilarity === null) {
            return [
                'success' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'no_enrolled_teachers',
            ];
        }

        $threshold = $this->faceSimilarityThreshold();
        if ($secondBestSimilarity !== null) {
            $similarityGap = $bestSimilarity - $secondBestSimilarity;
            if ($similarityGap < 0.05) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'matched' => false,
                    'threshold' => $threshold,
                    'similarity_gap' => round($similarityGap, 4),
                    'similarity_gap_threshold' => 0.05,
                    'liveness_score' => round($normalizedLivenessScore, 4),
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }

        if ($bestSimilarity < $threshold) {
            return [
                'success' => false,
                'message' => 'Wajah tidak dikenali. Pastikan guru sudah mendaftarkan wajah yang benar.',
                'similarity' => round($bestSimilarity, 4),
                'matched' => false,
                'threshold' => $threshold,
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
            'matched' => true,
            'threshold' => $threshold,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'embedding_dimension' => 128,
            'embedding_norm' => 1.0,
            'notes' => 'face_identified',
        ];
    }

    public function identifyByDescriptorOnly(iterable $users, mixed $descriptor): array
    {
        $provided = $this->normalizeDescriptor($descriptor);
        if ($provided === []) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ];
        }

        $bestUser = null;
        $bestSimilarity = null;
        $secondBestSimilarity = null;

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $storedDescriptors = $this->storedDescriptors($user);
            if ($storedDescriptors === []) {
                continue;
            }

            foreach ($storedDescriptors as $storedDescriptor) {
                $similarity = $this->cosineSimilarity($storedDescriptor, $provided);
                if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                    $secondBestSimilarity = $bestSimilarity;
                    $bestSimilarity = $similarity;
                    $bestUser = $user;
                } elseif ($secondBestSimilarity === null || $similarity > $secondBestSimilarity) {
                    $secondBestSimilarity = $similarity;
                }
            }
        }

        if (!$bestUser || $bestSimilarity === null) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Belum ada data wajah guru yang siap dicocokkan di kiosk ini.',
                'notes' => 'no_enrolled_teachers',
            ];
        }

        $threshold = $this->faceSimilarityThreshold();
        if ($secondBestSimilarity !== null) {
            $similarityGap = $bestSimilarity - $secondBestSimilarity;
            if ($similarityGap < 0.05) {
                return [
                    'success' => false,
                    'face_verified' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'matched' => false,
                    'threshold' => $threshold,
                    'similarity_gap' => round($similarityGap, 4),
                    'similarity_gap_threshold' => 0.05,
                    'notes' => 'face_ambiguous_match',
                ];
            }
        }

        if ($bestSimilarity < $threshold) {
            return [
                'success' => false,
                'face_verified' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
                'matched' => false,
                'threshold' => $threshold,
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
            'matched' => true,
            'threshold' => $threshold,
            'notes' => 'face_verified',
        ];
    }

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

            if ($descriptors !== []) {
                return $descriptors;
            }
        }

        if (isset($stored['face_descriptor'])) {
            $descriptor = $this->normalizeDescriptor($stored['face_descriptor']);
            return $descriptor === [] ? [] : [$descriptor];
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

    private function faceSimilarityThreshold(): float
    {
        $threshold = config('kiosk_face.similarity_threshold', self::DEFAULT_FACE_SIMILARITY_THRESHOLD);
        return is_numeric($threshold) ? (float) $threshold : self::DEFAULT_FACE_SIMILARITY_THRESHOLD;
    }

    private function matchAgainstStoredDescriptors(
        array $storedDescriptors,
        array $provided,
        float $normalizedLivenessScore,
        mixed $deviceInfo,
        ?string $faceIdUsed,
        User $user,
    ): array {
        $bestSimilarity = null;
        $secondBestSimilarity = null;

        foreach ($storedDescriptors as $storedDescriptor) {
            $similarity = $this->cosineSimilarity($storedDescriptor, $provided);
            if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                $secondBestSimilarity = $bestSimilarity;
                $bestSimilarity = $similarity;
            } elseif ($secondBestSimilarity === null || $similarity > $secondBestSimilarity) {
                $secondBestSimilarity = $similarity;
            }
        }

        $bestSimilarity ??= -1.0;
        $threshold = $this->faceSimilarityThreshold();

        if ($secondBestSimilarity !== null) {
            $similarityGap = $bestSimilarity - $secondBestSimilarity;
            if ($similarityGap < 0.02) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum cukup yakin untuk dipastikan. Silakan ulangi scan dengan posisi lebih tegak dan pencahayaan lebih stabil.',
                    'similarity' => round($bestSimilarity, 4),
                    'matched' => false,
                    'threshold' => $threshold,
                    'similarity_gap' => round($similarityGap, 4),
                    'similarity_gap_threshold' => 0.02,
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
                'matched' => false,
                'threshold' => $threshold,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'liveness_below_threshold',
            ];
        }

        if ($bestSimilarity < $threshold) {
            return [
                'success' => false,
                'message' => 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                'similarity' => round($bestSimilarity, 4),
                'matched' => false,
                'threshold' => $threshold,
                'liveness_score' => round($normalizedLivenessScore, 4),
                'notes' => 'face_similarity_below_threshold',
            ];
        }

        return [
            'success' => true,
            'message' => 'Scan wajah berhasil diverifikasi.',
            'face_id_used' => $faceIdUsed,
            'similarity' => round($bestSimilarity, 4),
            'matched' => true,
            'threshold' => $threshold,
            'liveness_score' => round($normalizedLivenessScore, 4),
            'device_info' => $deviceInfo,
            'embedding_dimension' => 128,
            'embedding_norm' => 1.0,
            'notes' => 'face_verified',
            'user' => $user,
        ];
    }
}
