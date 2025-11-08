<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Presensi;
use Illuminate\Support\Facades\Crypt;

class FaceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_enroll_api_stores_encrypted_face_data_with_liveness()
    {
        // create user
        $user = User::factory()->create(['role' => 'tenaga_pendidik']);

        $this->actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'face_data' => [0.1, 0.2, 0.3, 0.4, 0.5], // face descriptor array
            'liveness_score' => 0.85,
            'liveness_challenges' => ['blink', 'smile', 'head_turn'],
            'device_info' => 'Test Device'
        ];

        $resp = $this->postJson('/api/face/enroll', $payload);
        $resp->assertStatus(200)->assertJson(['success' => true]);

        $user->refresh();
        $this->assertNotNull($user->face_data);
        $this->assertNotNull($user->face_id);
        $this->assertNotNull($user->face_registered_at);
        $this->assertTrue($user->face_verification_required);

        // Verify data is encrypted and contains expected structure
        $decrypted = json_decode(Crypt::decryptString($user->face_data), true);
        $this->assertArrayHasKey('face_descriptor', $decrypted);
        $this->assertArrayHasKey('liveness_score', $decrypted);
        $this->assertArrayHasKey('liveness_challenges', $decrypted);
        $this->assertEquals(0.85, $decrypted['liveness_score']);
        $this->assertCount(3, $decrypted['liveness_challenges']);
    }

    public function test_enroll_api_rejects_low_liveness_score()
    {
        $user = User::factory()->create(['role' => 'tenaga_pendidik']);
        $this->actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'face_data' => [0.1, 0.2, 0.3, 0.4, 0.5],
            'liveness_score' => 0.5, // Below threshold
            'liveness_challenges' => ['blink', 'smile']
        ];

        $resp = $this->postJson('/api/face/enroll', $payload);
        $resp->assertStatus(400)->assertJson(['success' => false]);
    }

    public function test_verify_api_validates_face_with_liveness()
    {
        // Create user with enrolled face data
        $user = User::factory()->create(['role' => 'tenaga_pendidik']);

        $enrollmentData = [
            'face_descriptor' => [0.1, 0.2, 0.3, 0.4, 0.5],
            'liveness_score' => 0.9,
            'liveness_challenges' => ['blink', 'smile', 'head_turn'],
            'enrolled_at' => now()->toISOString(),
            'enrolled_by' => $user->id,
        ];

        $user->face_data = Crypt::encryptString(json_encode($enrollmentData));
        $user->face_id = 'test-face-id';
        $user->face_registered_at = now();
        $user->face_verification_required = true;
        $user->save();

        $this->actingAs($user);

        $payload = [
            'face_descriptor' => [0.1, 0.2, 0.3, 0.4, 0.5], // Exact match
            'liveness_score' => 0.8,
            'liveness_challenges' => ['blink', 'smile', 'head_turn']
        ];

        $resp = $this->postJson('/api/face/verify', $payload);
        $resp->assertStatus(200)->assertJson([
            'success' => true,
            'face_verified' => true,
            'similarity' => 1.0 // Exact match
        ]);
    }

    public function test_verify_api_rejects_low_similarity()
    {
        $user = User::factory()->create(['role' => 'tenaga_pendidik']);

        $enrollmentData = [
            'face_descriptor' => [0.1, 0.2, 0.3, 0.4, 0.5],
            'liveness_score' => 0.9,
            'liveness_challenges' => ['blink', 'smile', 'head_turn'],
            'enrolled_at' => now()->toISOString(),
            'enrolled_by' => $user->id,
        ];

        $user->face_data = Crypt::encryptString(json_encode($enrollmentData));
        $user->face_id = 'test-face-id';
        $user->face_registered_at = now();
        $user->face_verification_required = true;
        $user->save();

        $this->actingAs($user);

        $payload = [
            'face_descriptor' => [0.9, 0.8, 0.7, 0.6, 0.5], // Different face
            'liveness_score' => 0.8,
            'liveness_challenges' => ['blink', 'smile', 'head_turn']
        ];

        $resp = $this->postJson('/api/face/verify', $payload);
        $resp->assertStatus(200)->assertJson([
            'success' => true,
            'face_verified' => false
        ]);
    }

    public function test_presensi_requires_face_verification_when_enabled()
    {
        $user = User::factory()->create([
            'role' => 'tenaga_pendidik',
            'face_verification_required' => true
        ]);

        $this->actingAs($user);

        $payload = [
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'lokasi' => 'Test Location',
            'accuracy' => 10.0,
            'altitude' => 100.0,
            'speed' => 0.0,
            'device_info' => 'Test Device',
            'location_readings' => '[]',
            // Missing face verification data
        ];

        $resp = $this->postJson('/presensi', $payload);
        $resp->assertStatus(400)->assertJson([
            'success' => false,
            'message' => 'Verifikasi wajah diperlukan untuk melakukan presensi. Silakan lakukan verifikasi wajah terlebih dahulu.'
        ]);
    }

    public function test_presensi_accepts_valid_face_verification()
    {
        $user = User::factory()->create([
            'role' => 'tenaga_pendidik',
            'face_verification_required' => true
        ]);

        // Mock madrasah with polygon
        $madrasah = \App\Models\Madrasah::factory()->create([
            'polygon_koordinat' => json_encode([
                'type' => 'Polygon',
                'coordinates' => [[[106.845, -6.208], [106.846, -6.208], [106.846, -6.209], [106.845, -6.209], [106.845, -6.208]]]
            ])
        ]);

        $user->madrasah_id = $madrasah->id;
        $user->save();

        $this->actingAs($user);

        $payload = [
            'latitude' => -6.2085,
            'longitude' => 106.8455,
            'lokasi' => 'Test Location',
            'accuracy' => 10.0,
            'altitude' => 100.0,
            'speed' => 0.0,
            'device_info' => 'Test Device',
            'location_readings' => '[]',
            // Valid face verification data
            'face_verified' => true,
            'face_similarity_score' => 0.9,
            'liveness_score' => 0.8,
            'liveness_challenges' => ['blink', 'smile', 'head_turn'],
            'face_id_used' => 'test-face-id',
            'face_verification_notes' => 'Verification successful'
        ];

        $resp = $this->postJson('/presensi', $payload);
        $resp->assertStatus(200)->assertJson(['success' => true]);

        // Verify presensi was created with face data
        $presensi = Presensi::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($presensi);
        $this->assertTrue($presensi->face_verified);
        $this->assertEquals(0.9, $presensi->face_similarity_score);
        $this->assertEquals(0.8, $presensi->liveness_score);
    }
}
