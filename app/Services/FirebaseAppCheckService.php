<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class FirebaseAppCheckService
{
    /**
     * @return array<string, mixed>
     */
    public function verify(string $token): array
    {
        $projectNumber = trim((string) config('firebase.app_check.project_number'));

        if ($projectNumber === '') {
            throw new RuntimeException('Firebase App Check project number is not configured.');
        }

        [$header] = $this->decodeTokenParts($token);

        if (($header['alg'] ?? null) !== 'RS256' || ($header['typ'] ?? null) !== 'JWT') {
            throw new RuntimeException('Firebase App Check token header is invalid.');
        }

        try {
            $claims = (array) JWT::decode($token, JWK::parseKeySet($this->jwks()));
        } catch (Throwable $exception) {
            throw new RuntimeException('Firebase App Check token signature is invalid.', 0, $exception);
        }

        if (($claims['iss'] ?? null) !== "https://firebaseappcheck.googleapis.com/{$projectNumber}") {
            throw new RuntimeException('Firebase App Check token issuer is invalid.');
        }

        $audience = $claims['aud'] ?? [];
        $audience = is_array($audience) ? $audience : [$audience];
        if (! in_array("projects/{$projectNumber}", $audience, true)) {
            throw new RuntimeException('Firebase App Check token audience is invalid.');
        }

        $appId = trim((string) ($claims['sub'] ?? ''));
        if ($appId === '') {
            throw new RuntimeException('Firebase App Check token app ID is missing.');
        }

        $allowedAppIds = config('firebase.app_check.allowed_app_ids', []);
        if ($allowedAppIds !== [] && ! in_array($appId, $allowedAppIds, true)) {
            throw new RuntimeException('Firebase App Check token app ID is not allowed.');
        }

        return $claims;
    }

    /**
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    private function decodeTokenParts(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new RuntimeException('Firebase App Check token is malformed.');
        }

        $header = json_decode(JWT::urlsafeB64Decode($parts[0]), true);
        $payload = json_decode(JWT::urlsafeB64Decode($parts[1]), true);

        if (! is_array($header) || ! is_array($payload)) {
            throw new RuntimeException('Firebase App Check token is malformed.');
        }

        return [$header, $payload];
    }

    /**
     * @return array<string, mixed>
     */
    private function jwks(): array
    {
        $ttl = max(300, (int) config('firebase.app_check.jwks_cache_seconds', 18000));

        return Cache::remember('firebase_app_check_jwks', $ttl, function (): array {
            $response = Http::acceptJson()
                ->connectTimeout(5)
                ->timeout(10)
                ->retry(2, 200)
                ->get((string) config('firebase.app_check.jwks_url'));

            if (! $response->successful() || ! is_array($response->json('keys'))) {
                throw new RuntimeException('Unable to retrieve Firebase App Check public keys.');
            }

            return $response->json();
        });
    }
}
