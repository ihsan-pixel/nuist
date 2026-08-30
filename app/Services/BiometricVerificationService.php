<?php

namespace App\Services;

use App\Models\BiometricProfile;
use App\Models\User;

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
            return $this->error('BIOMETRIC_VECTOR_INVALID', 'Panjang embedding tidak sesuai dengan dimension.');
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
            return -1.0;
        }

        $dot = 0.0;
        $normProbe = 0.0;
        $normReference = 0.0;

        foreach ($probe as $idx => $value) {
            $a = (float) $value;
            $b = (float) $reference[$idx];
            if (!is_finite($a) || !is_finite($b)) {
                return -1.0;
            }

            $dot += $a * $b;
            $normProbe += $a * $a;
            $normReference += $b * $b;
        }

        if ($normProbe <= 0 || $normReference <= 0) {
            return -1.0;
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

        $profile = $this->findCompatibleProfile($user, $engine, $model, $dimension, $modelVersion);
        if (!$profile) {
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

        $reference = $this->normalizeVector($profile->embedding);
        if ($reference === []) {
            return $this->error('BIOMETRIC_PROFILE_INCOMPATIBLE', 'Profil biometrik tidak kompatibel dengan engine pengenalan wajah yang digunakan.');
        }

        $similarity = $this->cosineSimilarity($probe['embedding'], $reference);
        if ($similarity < 0) {
            return $this->error('BIOMETRIC_VECTOR_INVALID', 'Embedding tidak valid untuk dibandingkan.');
        }

        $resolvedThreshold = (float) config('biometric.default_threshold', 0.75);

        return [
            'ok' => true,
            'profile' => $profile,
            'similarity' => round($similarity, 4),
            'threshold' => $resolvedThreshold,
            'matched' => $similarity >= $resolvedThreshold,
            'code' => $similarity >= $resolvedThreshold ? 'FACE_VERIFIED' : 'FACE_NOT_VERIFIED',
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

    private function error(string $code, string $message): array
    {
        return [
            'ok' => false,
            'code' => $code,
            'message' => $message,
        ];
    }
}
