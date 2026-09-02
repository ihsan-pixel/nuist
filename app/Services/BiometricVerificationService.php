<?php

namespace App\Services;

use App\Models\BiometricProfile;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BiometricVerificationService
{
    public const MAX_DIMENSION = 2048;

    public function validateProbe(array $embedding, string $engine, string $model, int $dimension, ?string $modelVersion = null): array
    {
        if ($engine === '' || $model === '') {
            return $this->error('INVALID_BIOMETRIC_PAYLOAD', 'engine dan model wajib diisi.');
        }

        if ($dimension <= 0 || $dimension > self::MAX_DIMENSION) {
            return $this->error('BIOMETRIC_DIMENSION_INVALID', 'Dimensi embedding tidak valid.');
        }

        if (count($embedding) !== $dimension) {
            return $this->error('REQUEST_VECTOR_DIMENSION_MISMATCH', 'Panjang embedding request tidak sesuai dengan dimension.');
        }

        $normalized = [];
        foreach ($embedding as $value) {
            if (!is_numeric($value)) {
                return $this->error('BIOMETRIC_VECTOR_INVALID', 'Embedding harus berupa angka numerik.');
            }

            $floatValue = (float) $value;
            if (!is_finite($floatValue)) {
                return $this->error('BIOMETRIC_VECTOR_INVALID', 'Embedding mengandung nilai tidak valid.');
            }

            $normalized[] = $floatValue;
        }

        return [
            'ok' => true,
            'embedding' => $normalized,
            'engine' => $engine,
            'model' => $model,
            'model_version' => $modelVersion,
            'dimension' => $dimension,
        ];
    }

    public function findCompatibleProfile(User $user, string $engine, string $model, int $dimension, ?string $modelVersion = null): ?BiometricProfile
    {
        return $user->biometricProfiles()
            ->where('engine', $engine)
            ->where('model', $model)
            ->where('dimension', $dimension)
            ->where('status', 'active')
            ->when($modelVersion !== null && $modelVersion !== '', function ($query) use ($modelVersion) {
                $query->where(function ($inner) use ($modelVersion) {
                    $inner->whereNull('model_version')
                        ->orWhere('model_version', $modelVersion);
                });
            })
            ->orderByDesc('enrolled_at')
            ->orderByDesc('id')
            ->first();
    }

    public function cosineSimilarity(array $probe, array $reference): float
    {
        if ($probe === [] || $reference === [] || count($probe) !== count($reference)) {
            throw new \InvalidArgumentException('COSINE_VECTOR_INVALID');
        }

        $dot = 0.0;
        $normProbe = 0.0;
        $normReference = 0.0;

        foreach ($probe as $idx => $value) {
            $a = (float) $value;
            $b = (float) $reference[$idx];
            if (!is_finite($a) || !is_finite($b)) {
                throw new \InvalidArgumentException('COSINE_VECTOR_INVALID');
            }

            $dot += $a * $b;
            $normProbe += $a * $a;
            $normReference += $b * $b;
        }

        if ($normProbe <= 0 || $normReference <= 0) {
            throw new \InvalidArgumentException('COSINE_VECTOR_INVALID');
        }

        return $dot / (sqrt($normProbe) * sqrt($normReference));
    }

    public function verify(
        User $user,
        array $embedding,
        string $engine,
        string $model,
        int $dimension,
        ?string $modelVersion = null,
        ?float $threshold = null
    ): array {
        $probe = $this->validateProbe($embedding, $engine, $model, $dimension, $modelVersion);
        if (!($probe['ok'] ?? false)) {
            return $probe;
        }

        $requestVectorType = get_debug_type($probe['embedding']);
        $requestStats = $this->vectorStats($probe['embedding']);
        Log::debug('[BIOMETRIC_VERIFY][DIMENSION_TRACE]', [
            'user_id' => $user->id,
            'request_declared_dimension' => $dimension,
            'request_embedding_count' => $requestStats['count'] ?? count($probe['embedding']),
            'request_norm' => $requestStats['norm'] ?? null,
            'request_embedding_type' => $requestVectorType,
        ]);

        Log::debug('[BIOMETRIC_VERIFY][REQUEST_VECTOR]', $requestStats);

        $profiles = $user->biometricProfiles()
            ->where('engine', $engine)
            ->where('model', $model)
            ->where('dimension', $dimension)
            ->where('status', 'active')
            ->when($modelVersion === null || $modelVersion === '',
                fn ($query) => $query->whereNull('model_version'),
                fn ($query) => $query->where('model_version', $modelVersion))
            ->orderByDesc('enrolled_at')
            ->orderByDesc('id')
            ->get();

        if ($profiles->isEmpty()) {
            $compatibleExists = $user->biometricProfiles()
                ->where('engine', $engine)
                ->where('model', $model)
                ->where('dimension', $dimension)
                ->exists();

            $versionMismatchExists = false;
            if ($modelVersion !== null && $modelVersion !== '') {
                $versionMismatchExists = $user->biometricProfiles()
                    ->where('engine', $engine)
                    ->where('model', $model)
                    ->where('dimension', $dimension)
                    ->where('status', 'active')
                    ->whereNot(function ($query) use ($modelVersion) {
                        $query->whereNull('model_version')
                            ->orWhere('model_version', $modelVersion);
                    })
                    ->exists();
            }

            return [
                'ok' => false,
                'code' => $versionMismatchExists
                    ? 'BIOMETRIC_MODEL_VERSION_MISMATCH'
                    : ($compatibleExists ? 'BIOMETRIC_PROFILE_INCOMPATIBLE' : 'BIOMETRIC_PROFILE_NOT_FOUND'),
                'message' => $versionMismatchExists
                    ? 'Versi model biometrik tidak kompatibel.'
                    : ($compatibleExists
                    ? 'Profil biometrik tidak kompatibel dengan engine pengenalan wajah yang digunakan.'
                    : 'Profil wajah untuk aplikasi ini belum tersedia.'),
            ];
        }

        $bestProfile = null;
        $bestSimilarity = null;
        $bestProfileStats = null;
        $validProfileCount = 0;

        foreach ($profiles as $profile) {
            $reference = $this->normalizeVector($profile->embedding);
            $profileStats = $this->vectorStats($reference);

            if (($profileStats['count'] ?? 0) !== $dimension || ($profileStats['norm'] ?? 0) <= 0) {
                continue;
            }

            $validProfileCount++;
            try {
                $similarity = $this->cosineSimilarity($probe['embedding'], $reference);
            } catch (\InvalidArgumentException) {
                continue;
            }

            if ($bestSimilarity === null || $similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestProfile = $profile;
                $bestProfileStats = $profileStats;
            }
        }

        if (!$bestProfile || $bestSimilarity === null) {
            return $validProfileCount === 0
                ? $this->error('PROFILE_VECTOR_INVALID', 'Profil biometrik tersimpan tidak valid.')
                : $this->error('BIOMETRIC_VECTOR_INVALID', 'Embedding tidak valid untuk dibandingkan.');
        }

        $similarity = $bestSimilarity;
        $resolvedThreshold = (float) ($threshold ?? config('biometric_v2.default_threshold', config('biometric.default_threshold', 0.75)));
        Log::info('[BIOMETRIC_TEST][VERIFY]', [
            'user_id' => $user->id,
            'profile_id' => $bestProfile->id,
            'profile_count' => $profiles->count(),
            'engine' => $engine,
            'model' => $model,
            'dimension' => $dimension,
            'request_embedding_count' => $requestStats['count'] ?? count($probe['embedding']),
            'profile_embedding_count' => $bestProfileStats['count'] ?? 0,
            'similarity' => round($similarity, 4),
            'threshold' => $resolvedThreshold,
            'verified' => $similarity >= $resolvedThreshold,
        ]);
        Log::info('[BIOMETRIC_VERIFY][RESULT]', [
            'auth_user_id' => $user->id,
            'profile_user_id' => $bestProfile->user_id,
            'profile_id' => $bestProfile->id,
            'profile_count' => $profiles->count(),
            'request_dimension' => $dimension,
            'profile_dimension' => $bestProfile->dimension,
            'request_norm' => $requestStats['norm'] ?? null,
            'profile_norm' => $bestProfileStats['norm'] ?? null,
            'similarity' => round($similarity, 4),
            'threshold' => $resolvedThreshold,
            'verified' => $similarity >= $resolvedThreshold,
            'failure_code' => $similarity >= $resolvedThreshold ? 'FACE_VERIFIED' : 'SIMILARITY_BELOW_THRESHOLD',
        ]);

        return [
            'ok' => true,
            'profile' => $bestProfile,
            'similarity' => round($similarity, 4),
            'threshold' => $resolvedThreshold,
            'matched' => $similarity >= $resolvedThreshold,
            'code' => $similarity >= $resolvedThreshold ? 'FACE_VERIFIED' : 'SIMILARITY_BELOW_THRESHOLD',
            'message' => $similarity >= $resolvedThreshold
                ? 'Wajah cocok dengan profil biometrik aktif.'
                : 'Wajah tidak cocok dengan profil biometrik aktif.',
            'engine' => $engine,
            'model' => $model,
            'model_version' => $modelVersion,
            'dimension' => $dimension,
        ];
    }

    private function normalizeVector(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        if (count($value) === 1 && is_array($value[0] ?? null)) {
            $first = $value[0];
            $flatFirst = [];
            foreach ($first as $item) {
                if (!is_numeric($item)) {
                    $flatFirst = [];
                    break;
                }
                $floatValue = (float) $item;
                if (!is_finite($floatValue)) {
                    $flatFirst = [];
                    break;
                }
                $flatFirst[] = $floatValue;
            }

            if ($flatFirst !== []) {
                return $flatFirst;
            }
        }

        $normalized = [];
        foreach ($value as $item) {
            if (!is_numeric($item)) {
                return [];
            }
            $floatValue = (float) $item;
            if (!is_finite($floatValue)) {
                return [];
            }
            $normalized[] = $floatValue;
        }

        return $normalized;
    }

    private function vectorStats(array $vector): array
    {
        $count = count($vector);
        $numeric = true;
        $finite = true;
        $nonZero = false;
        $normSum = 0.0;

        foreach ($vector as $value) {
            if (!is_int($value) && !is_float($value) && !is_numeric($value)) {
                $numeric = false;
                $finite = false;
                continue;
            }

            $floatValue = (float) $value;
            if (!is_finite($floatValue)) {
                $finite = false;
                continue;
            }

            if ($floatValue != 0.0) {
                $nonZero = true;
            }

            $normSum += $floatValue * $floatValue;
        }

        return [
            'count' => $count,
            'numeric' => $numeric,
            'finite' => $finite,
            'nonZero' => $nonZero,
            'norm' => $normSum > 0 ? sqrt($normSum) : 0.0,
        ];
    }

    private function error(string $code, string $message): array
    {
        return [
            'ok' => false,
            'code' => $code,
            'message' => $message,
        ];
    }
}
