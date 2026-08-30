<?php

namespace App\Services;

use App\Models\BiometricProfile;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BiometricProfileService
{
    public function createProfile(array $attributes): BiometricProfile
    {
        $payload = array_merge([
            'enrollment_uuid' => (string) Str::uuid(),
            'status' => 'active',
        ], $attributes);

        $profile = BiometricProfile::create($payload);
        $this->logStoredProfile($profile);
        return $profile;
    }

    public function createProfileWithReEnrollment(User $user, array $attributes): BiometricProfile
    {
        return DB::transaction(function () use ($user, $attributes) {
            $payload = array_merge([
                'user_id' => $user->id,
                'enrollment_uuid' => (string) Str::uuid(),
                'status' => 'active',
            ], $attributes);

            $profile = BiometricProfile::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($profile) {
                $profile->fill($payload);
                $profile->save();
            } else {
                $profile = BiometricProfile::create($payload);
            }

            $this->logStoredProfile($profile);
            return $profile->refresh();
        });
    }

    public function findCompatibleProfile(
        User $user,
        string $engine,
        string $model,
        int $dimension,
        ?string $modelVersion = null
    ): ?BiometricProfile {
        $query = $user->biometricProfiles()
            ->where('engine', $engine)
            ->where('model', $model)
            ->where('dimension', $dimension)
            ->where('status', 'active');

        if ($modelVersion === null || $modelVersion === '') {
            $query->whereNull('model_version');
        } else {
            $query->where('model_version', $modelVersion);
        }

        return $query->orderByDesc('enrolled_at')
            ->orderByDesc('id')
            ->first();
    }

    public function findActiveProfiles(User $user)
    {
        return $user->biometricProfiles()
            ->where('status', 'active')
            ->orderByDesc('enrolled_at')
            ->orderByDesc('id')
            ->get();
    }

    public function deactivateProfile(BiometricProfile $profile): bool
    {
        $profile->status = 'inactive';
        return $profile->save();
    }

    public function deactivateCompatibleProfiles(
        User $user,
        string $engine,
        string $model,
        int $dimension,
        ?string $modelVersion = null
    ): int {
        $query = $user->biometricProfiles()
            ->where('engine', $engine)
            ->where('model', $model)
            ->where('dimension', $dimension)
            ->where('status', 'active');

        if ($modelVersion === null || $modelVersion === '') {
            $query->whereNull('model_version');
        } else {
            $query->where('model_version', $modelVersion);
        }

        return $query->update(['status' => 'inactive']);
    }

    private function logStoredProfile(BiometricProfile $profile): void
    {
        $embedding = is_array($profile->embedding) ? $profile->embedding : [];
        Log::info('[BIOMETRIC_PROFILE][STORED]', [
            'user_id' => $profile->user_id,
            'profile_id' => $profile->id,
            'engine' => $profile->engine,
            'model' => $profile->model,
            'model_version' => $profile->model_version,
            'dimension' => $profile->dimension,
            'embedding_count' => count($embedding),
            'status' => $profile->status,
            'fingerprint' => $this->fingerprintEmbedding($embedding),
        ]);
    }

    private function fingerprintEmbedding(array $embedding): string
    {
        $canonical = array_map(
            static fn ($value) => is_numeric($value) ? number_format((float) $value, 8, '.', '') : 'nan',
            $embedding,
        );

        return hash('sha256', json_encode($canonical, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
