<?php

namespace Tests\Feature;

use App\Models\BiometricProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class BiometricV2AuditTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
    }

    public function test_unauthenticated_enrollment_is_rejected(): void
    {
        $this->postJson('/api/biometric/enroll', [])->assertStatus(401);
    }

    public function test_unauthenticated_verify_is_rejected(): void
    {
        $this->postJson('/api/biometric/verify', [])->assertStatus(401);
    }

    public function test_biometric_enrollment_generates_server_uuid_and_keeps_legacy_fields(): void
    {
        $user = User::factory()->create([
            'face_id' => 'legacy-face-id',
            'face_data' => json_encode(['legacy' => true]),
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/biometric/enroll', [
            'embedding' => array_fill(0, 4, 0.25),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => null,
            'dimension' => 4,
            'metadata' => [],
        ]);

        $response->assertOk()
            ->assertJsonMissingPath('profile.embedding')
            ->assertJsonMissingPath('profile.samples');

        $this->assertSame('legacy-face-id', $user->fresh()->face_id);
        $this->assertSame(json_encode(['legacy' => true]), $user->fresh()->face_data);
        $this->assertDatabaseCount('biometric_profiles', 1);
        $this->assertNotNull(BiometricProfile::first()->enrollment_uuid);
    }

    public function test_status_endpoint_sanitizes_payload(): void
    {
        $user = User::factory()->create();
        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => null,
            'dimension' => 4,
            'embedding' => [1, 0, 0, 0],
            'samples' => [[1, 0, 0, 0]],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => ['secret' => 'x'],
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/face/biometric/status');

        $response->assertOk()
            ->assertJsonMissingPath('profile.embedding')
            ->assertJsonMissingPath('profile.samples')
            ->assertJsonPath('registered', true)
            ->assertJsonPath('profile.engine', 'mobilefacenet');
    }

    public function test_verify_rejects_invalid_vector_shapes_and_values(): void
    {
        $user = User::factory()->create();

        $cases = [
            ['embedding' => [], 'dimension' => 4, 'code' => 'BIOMETRIC_VECTOR_INVALID'],
            ['embedding' => [1, 2, 3], 'dimension' => 4, 'code' => 'BIOMETRIC_VECTOR_INVALID'],
            ['embedding' => [1, 'a', 3, 4], 'dimension' => 4, 'code' => 'BIOMETRIC_VECTOR_INVALID'],
            ['embedding' => [0, 0, 0, 0], 'dimension' => 4, 'code' => 'BIOMETRIC_PROFILE_NOT_FOUND'],
        ];

        foreach ($cases as $case) {
            $response = $this->actingAs($user, 'sanctum')->postJson('/api/biometric/verify', [
                'embedding' => $case['embedding'],
                'engine' => 'mobilefacenet',
                'model' => 'mobilefacenet',
                'model_version' => null,
                'dimension' => $case['dimension'],
            ]);

            if ($case['code'] === 'BIOMETRIC_VECTOR_INVALID') {
                $response->assertStatus(422)->assertJsonPath('code', 'BIOMETRIC_VECTOR_INVALID');
            }
        }
    }

    public function test_compatible_profile_can_be_verified_and_similarity_is_high(): void
    {
        $user = User::factory()->create();
        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => null,
            'dimension' => 4,
            'embedding' => [1, 0, 0, 0],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/biometric/verify', [
            'embedding' => [1, 0, 0, 0],
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => null,
            'dimension' => 4,
        ]);

        $response->assertOk()
            ->assertJsonPath('face_verified', true)
            ->assertJsonPath('similarity', 1.0)
            ->assertJsonMissingPath('embedding')
            ->assertJsonMissingPath('samples');
    }

    public function test_engine_and_model_version_mismatches_fail_explicitly(): void
    {
        $user = User::factory()->create();
        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => '1.0',
            'dimension' => 4,
            'embedding' => [1, 0, 0, 0],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now(),
        ]);

        $engineMismatch = $this->actingAs($user, 'sanctum')->postJson('/api/biometric/verify', [
            'embedding' => [1, 0, 0, 0],
            'engine' => 'faceapi',
            'model' => 'mobilefacenet',
            'model_version' => '1.0',
            'dimension' => 4,
        ]);

        $engineMismatch->assertStatus(422)->assertJsonPath('code', 'BIOMETRIC_PROFILE_NOT_FOUND');

        $versionMismatch = $this->actingAs($user, 'sanctum')->postJson('/api/biometric/verify', [
            'embedding' => [1, 0, 0, 0],
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => '2.0',
            'dimension' => 4,
        ]);

        $versionMismatch->assertStatus(422)->assertJsonPath('code', 'BIOMETRIC_MODEL_VERSION_MISMATCH');
    }

    public function test_reenrollment_updates_single_profile_record(): void
    {
        $user = User::factory()->create();
        $existing = BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => 'old-version',
            'dimension' => 4,
            'embedding' => [1, 0, 0, 0],
            'samples' => [],
            'source' => 'flutter',
            'status' => 'active',
            'metadata' => [],
            'enrolled_at' => now()->subDay(),
        ]);

        $this->actingAs($user, 'sanctum')->postJson('/api/biometric/enroll', [
            'embedding' => [0, 1, 0, 0],
            'engine' => 'mobilefacenet',
            'model' => 'mobilefacenet',
            'model_version' => 'mobilefacenet-be4bc7cf',
            'dimension' => 4,
            'metadata' => [],
        ])->assertOk();

        $this->assertDatabaseCount('biometric_profiles', 1);
        $fresh = $existing->fresh();
        $this->assertSame('mobilefacenet-be4bc7cf', $fresh->model_version);
        $this->assertSame('active', $fresh->status);
    }
}
