<?php

namespace Tests\Feature;

use App\Services\FirebaseAppCheckService;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class FirebaseAppCheckTest extends TestCase
{
    public function test_enforce_mode_rejects_request_without_app_check_token(): void
    {
        config()->set('firebase.app_check.mode', 'enforce');

        $this->postJson('/api/mobile/login', [])
            ->assertUnauthorized()
            ->assertJsonPath('code', 'firebase_app_check_failed');
    }

    public function test_monitor_mode_allows_request_without_app_check_token(): void
    {
        config()->set('firebase.app_check.mode', 'monitor');

        $this->postJson('/api/mobile/login', [])
            ->assertUnprocessable();
    }

    public function test_enforce_mode_allows_a_verified_app_check_token(): void
    {
        config()->set('firebase.app_check.mode', 'enforce');

        $this->mock(FirebaseAppCheckService::class, function ($mock): void {
            $mock->shouldReceive('verify')
                ->once()
                ->with('valid-token')
                ->andReturn(['sub' => '1:1234567890:android:abc']);
        });

        $this->withHeader('X-Firebase-AppCheck', 'valid-token')
            ->postJson('/api/mobile/login', [])
            ->assertUnprocessable();
    }

    public function test_enforce_mode_rejects_an_invalid_app_check_token(): void
    {
        config()->set('firebase.app_check.mode', 'enforce');

        $this->mock(FirebaseAppCheckService::class, function ($mock): void {
            $mock->shouldReceive('verify')
                ->once()
                ->andThrow(new RuntimeException('invalid signature'));
        });

        $this->withHeader('X-Firebase-AppCheck', 'invalid-token')
            ->postJson('/api/mobile/login', [])
            ->assertUnauthorized()
            ->assertJsonPath('code', 'firebase_app_check_failed');
    }

    public function test_service_verifies_signature_issuer_audience_and_allowed_app_id(): void
    {
        Cache::clear();
        config()->set('firebase.app_check.project_number', '1234567890');
        config()->set('firebase.app_check.allowed_app_ids', ['1:1234567890:android:abc']);

        $key = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        $this->assertNotFalse($key);

        openssl_pkey_export($key, $privateKey);
        $details = openssl_pkey_get_details($key);
        $this->assertIsArray($details);

        Http::fake([
            'firebaseappcheck.googleapis.com/v1/jwks' => Http::response([
                'keys' => [[
                    'kty' => 'RSA',
                    'kid' => 'test-key',
                    'use' => 'sig',
                    'alg' => 'RS256',
                    'n' => JWT::urlsafeB64Encode($details['rsa']['n']),
                    'e' => JWT::urlsafeB64Encode($details['rsa']['e']),
                ]],
            ]),
        ]);

        $now = time();
        $token = JWT::encode([
            'iss' => 'https://firebaseappcheck.googleapis.com/1234567890',
            'aud' => ['projects/1234567890'],
            'sub' => '1:1234567890:android:abc',
            'iat' => $now,
            'exp' => $now + 3600,
        ], $privateKey, 'RS256', 'test-key', ['typ' => 'JWT']);

        $claims = app(FirebaseAppCheckService::class)->verify($token);

        $this->assertSame('1:1234567890:android:abc', $claims['sub']);
        Http::assertSentCount(1);
    }
}
