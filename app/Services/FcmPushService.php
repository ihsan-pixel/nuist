<?php

namespace App\Services;

use App\Models\PushDeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FcmPushService
{
    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        $tokens = $user->pushDeviceTokens()->pluck('token');
        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * @param iterable<User> $users
     */
    public function sendToUsers(iterable $users, string $title, string $body, array $data = []): void
    {
        $userIds = collect($users)
            ->map(fn (User $user) => $user->id)
            ->filter()
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            return;
        }

        $tokens = PushDeviceToken::query()
            ->whereIn('user_id', $userIds)
            ->pluck('token');

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * @param iterable<string> $tokens
     */
    public function sendToTokens(iterable $tokens, string $title, string $body, array $data = []): void
    {
        $credentials = $this->firebaseCredentials();
        if ($credentials === null) {
            return;
        }

        $tokenList = collect($tokens)
            ->filter(fn ($token) => is_string($token) && trim($token) !== '')
            ->map(fn ($token) => trim((string) $token))
            ->unique()
            ->values();

        if ($tokenList->isEmpty()) {
            return;
        }

        try {
            $accessToken = $this->googleAccessToken($credentials);
        } catch (Throwable $exception) {
            Log::warning('FCM access token acquisition failed.', [
                'message' => $exception->getMessage(),
            ]);
            return;
        }

        foreach ($tokenList as $deviceToken) {
            try {
                $response = Http::withToken($accessToken)
                    ->acceptJson()
                    ->post(
                        'https://fcm.googleapis.com/v1/projects/' . $credentials['project_id'] . '/messages:send',
                        [
                            'message' => [
                                'token' => $deviceToken,
                                'notification' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'data' => $this->normalizeDataPayload($data),
                                'android' => [
                                    'priority' => 'high',
                                    'notification' => [
                                        'channel_id' => 'nuist_general',
                                        'sound' => 'default',
                                    ],
                                ],
                                'apns' => [
                                    'payload' => [
                                        'aps' => [
                                            'sound' => 'default',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                if ($response->successful()) {
                    continue;
                }

                $errorStatus = (string) data_get($response->json(), 'error.status', '');
                if (in_array($errorStatus, ['UNREGISTERED', 'INVALID_ARGUMENT'], true)) {
                    PushDeviceToken::query()->where('token', $deviceToken)->delete();
                }

                Log::warning('FCM send failed.', [
                    'status' => $response->status(),
                    'error_status' => $errorStatus,
                    'response' => $response->json(),
                ]);
            } catch (Throwable $exception) {
                Log::warning('FCM send threw an exception.', [
                    'message' => $exception->getMessage(),
                ]);
            }
        }
    }

    /**
     * @return array{project_id:string, client_email:string, private_key:string}|null
     */
    private function firebaseCredentials(): ?array
    {
        $rawJson = trim((string) config('services.firebase.service_account_json'));
        $jsonPath = trim((string) config('services.firebase.service_account_path'));

        $payload = null;
        if ($rawJson !== '') {
            $payload = json_decode($rawJson, true);
        } elseif ($jsonPath !== '' && is_file($jsonPath)) {
            $contents = file_get_contents($jsonPath);
            $payload = is_string($contents) ? json_decode($contents, true) : null;
        }

        $projectId = trim((string) config('services.firebase.project_id'));
        $clientEmail = trim((string) config('services.firebase.client_email'));
        $privateKey = (string) config('services.firebase.private_key');

        if (is_array($payload)) {
            $projectId = $projectId !== '' ? $projectId : trim((string) ($payload['project_id'] ?? ''));
            $clientEmail = $clientEmail !== '' ? $clientEmail : trim((string) ($payload['client_email'] ?? ''));
            $privateKey = $privateKey !== '' ? $privateKey : (string) ($payload['private_key'] ?? '');
        }

        $privateKey = str_replace("\\n", "\n", $privateKey);

        if ($projectId === '' || $clientEmail === '' || trim($privateKey) === '') {
            return null;
        }

        return [
            'project_id' => $projectId,
            'client_email' => $clientEmail,
            'private_key' => $privateKey,
        ];
    }

    /**
     * @param array{project_id:string, client_email:string, private_key:string} $credentials
     */
    private function googleAccessToken(array $credentials): string
    {
        $cacheKey = 'fcm_access_token_' . md5($credentials['client_email'] . '|' . $credentials['project_id']);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($credentials) {
            $now = time();
            $jwtHeader = $this->base64UrlEncode(json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ], JSON_THROW_ON_ERROR));

            $jwtClaimSet = $this->base64UrlEncode(json_encode([
                'iss' => $credentials['client_email'],
                'sub' => $credentials['client_email'],
                'aud' => 'https://oauth2.googleapis.com/token',
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'iat' => $now,
                'exp' => $now + 3600,
            ], JSON_THROW_ON_ERROR));

            $unsignedJwt = $jwtHeader . '.' . $jwtClaimSet;
            $signature = '';

            $signed = openssl_sign($unsignedJwt, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');
            if (!$signed) {
                throw new \RuntimeException('Unable to sign JWT for Firebase access token.');
            }

            $jwt = $unsignedJwt . '.' . $this->base64UrlEncode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('Firebase OAuth token request failed with status ' . $response->status() . '.');
            }

            $accessToken = (string) data_get($response->json(), 'access_token', '');
            if ($accessToken === '') {
                throw new \RuntimeException('Firebase OAuth token response did not contain an access token.');
            }

            return $accessToken;
        });
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function normalizeDataPayload(array $data): array
    {
        return collect($data)
            ->reject(fn ($value) => $value === null)
            ->map(function ($value) {
                if (is_bool($value)) {
                    return $value ? '1' : '0';
                }

                if (is_scalar($value)) {
                    return (string) $value;
                }

                return json_encode($value);
            })
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->all();
    }
}
