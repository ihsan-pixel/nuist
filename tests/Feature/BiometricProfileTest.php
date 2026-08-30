<?php

namespace Tests\Feature;

use App\Models\BiometricProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BiometricProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', '/tmp/nuist_biometric_test.sqlite');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
    }

    public function test_user_has_single_biometric_profile_row(): void
    {
        $user = User::factory()->create([
            'face_id' => 'legacy-face-id',
            'face_data' => json_encode(['legacy' => true]),
        ]);

        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => '1.0',
            'dimension' => 192,
            'embedding' => [0.3, 0.4],
            'samples' => [['type' => 'front']],
            'quality_score' => 0.88,
            'liveness_score' => 0.94,
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => ['source' => 'flutter'],
            'enrolled_at' => now(),
        ]);

        $this->assertCount(1, $user->biometricProfiles);
    }

    public function test_biometric_profile_casts_work(): void
    {
        $user = User::factory()->create();
        $profile = BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'dimension' => 192,
            'embedding' => [1, 2, 3],
            'samples' => [['a' => 1]],
            'quality_score' => 0.91,
            'liveness_score' => 0.95,
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => ['foo' => 'bar'],
            'enrolled_at' => now(),
        ]);

        $this->assertIsArray($profile->embedding);
        $this->assertIsArray($profile->samples);
        $this->assertIsArray($profile->metadata);
        $this->assertIsFloat($profile->quality_score);
        $this->assertIsFloat($profile->liveness_score);
        $this->assertNotNull($profile->enrolled_at);
    }

    public function test_biometric_profile_relations_work(): void
    {
        $user = User::factory()->create();
        $profile = BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'dimension' => 192,
            'embedding' => [1, 2, 3],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        $this->assertTrue($profile->user->is($user));
        $this->assertTrue($user->biometricProfiles->first()->is($profile));
    }

    public function test_creating_biometric_profile_does_not_change_legacy_face_fields(): void
    {
        $user = User::factory()->create([
            'face_id' => 'legacy-face-id',
            'face_data' => json_encode(['legacy' => true]),
        ]);

        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'dimension' => 192,
            'embedding' => [1, 2, 3],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        $user->refresh();

        $this->assertSame('legacy-face-id', $user->face_id);
        $this->assertSame(json_encode(['legacy' => true]), $user->face_data);
    }

    public function test_active_and_inactive_profiles_can_coexist(): void
    {
        $user = User::factory()->create();

        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'dimension' => 192,
            'embedding' => [1, 2, 3],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'dimension' => 192,
            'embedding' => [4, 5, 6],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'inactive',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        $this->assertSame(1, $user->biometricProfiles()->where('status', 'active')->count());
        $this->assertSame(1, $user->biometricProfiles()->where('status', 'inactive')->count());
    }
}
