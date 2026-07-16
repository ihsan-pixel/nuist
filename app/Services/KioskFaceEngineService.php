<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class KioskFaceEngineService
{
    public function driver(): string
    {
        return $this->usesPython() ? 'python' : 'browser';
    }

    public function usesPython(): bool
    {
        return strtolower((string) config('kiosk_face.driver', 'browser')) === 'python';
    }

    public function displayLabel(): string
    {
        return $this->usesPython()
            ? 'Python FastAPI / InsightFace'
            : 'Browser Face API';
    }

    public function enroll(User $teacher, array $payload, array $context = []): array
    {
        $frames = $this->normalizeFrames($payload);
        if ($frames === []) {
            return $this->failure(
                'Frame wajah belum tersedia. Izinkan kamera lalu ulangi registrasi wajah.',
                'frame_payload_missing'
            );
        }

        $response = $this->postJson('/api/v1/enroll', [
            'teacher_id' => $teacher->id,
            'teacher_name' => $teacher->name,
            'frames' => $frames,
            'device_info' => $payload['device_info'] ?? null,
            'context' => $this->normalizeContext($context),
        ]);

        if (!($response['success'] ?? false)) {
            return $response;
        }

        $embedding = $this->normalizeVector($response['face_embedding'] ?? $response['embedding'] ?? null);
        if ($embedding === []) {
            return $this->failure(
                'Engine wajah Python tidak mengembalikan embedding yang valid untuk pendaftaran.',
                'engine_embedding_missing'
            );
        }

        return [
            'success' => true,
            'message' => (string) ($response['message'] ?? 'Data wajah berhasil diproses oleh engine Python.'),
            'face_embedding' => $embedding,
            'face_embedding_dimension' => count($embedding),
            'liveness_score' => $this->normalizeFloat($response['liveness_score'] ?? null),
            'liveness_challenges' => $this->normalizeChallenges($response['liveness_challenges'] ?? $response['challenges'] ?? []),
            'captured_image' => $this->normalizeDataUrl($response['captured_image'] ?? $response['best_frame'] ?? null)
                ?? $frames[0],
            'quality_score' => $this->normalizeFloat($response['quality_score'] ?? null),
            'metadata' => is_array($response['metadata'] ?? null) ? $response['metadata'] : [],
            'notes' => (string) ($response['notes'] ?? 'face_enrolled_python'),
        ];
    }

    public function identify(iterable $users, array $payload, array $context = []): array
    {
        $frames = $this->normalizeFrames($payload);
        if ($frames === []) {
            return $this->failure(
                'Frame scan wajah belum tersedia. Pastikan kamera aktif lalu ulangi presensi.',
                'frame_payload_missing'
            );
        }

        $candidates = $this->buildCandidates($users);
        if ($candidates === []) {
            return $this->failure(
                'Belum ada data wajah guru yang siap dicocokkan untuk engine Python.',
                'no_enrolled_teachers'
            );
        }

        $response = $this->postJson('/api/v1/identify', [
            'frames' => $frames,
            'device_info' => $payload['device_info'] ?? null,
            'candidates' => $candidates,
            'context' => $this->normalizeContext($context),
        ]);

        if (!($response['success'] ?? false)) {
            return $response;
        }

        $userId = isset($response['user_id']) && is_numeric($response['user_id'])
            ? (int) $response['user_id']
            : null;

        if (!$userId) {
            return $this->failure(
                'Engine wajah Python tidak mengembalikan identitas guru yang valid.',
                'engine_user_missing'
            );
        }

        $embedding = $this->normalizeVector($response['face_embedding'] ?? $response['embedding'] ?? null);

        return [
            'success' => true,
            'message' => (string) ($response['message'] ?? 'Identitas guru berhasil dikenali oleh engine Python.'),
            'user_id' => $userId,
            'face_id_used' => isset($response['face_id_used']) ? (string) $response['face_id_used'] : null,
            'similarity' => $this->normalizeFloat($response['similarity'] ?? null),
            'face_distance' => $this->normalizeFloat($response['face_distance'] ?? null),
            'liveness_score' => $this->normalizeFloat($response['liveness_score'] ?? null),
            'liveness_challenges' => $this->normalizeChallenges($response['liveness_challenges'] ?? $response['challenges'] ?? []),
            'captured_image' => $this->normalizeDataUrl($response['captured_image'] ?? $response['best_frame'] ?? null)
                ?? $frames[0],
            'face_embedding' => $embedding,
            'notes' => (string) ($response['notes'] ?? 'face_identified_python'),
            'metadata' => is_array($response['metadata'] ?? null) ? $response['metadata'] : [],
        ];
    }

    private function postJson(string $path, array $payload): array
    {
        try {
            $response = $this->request()->post($path, $payload);

            if (!$response->successful()) {
                $json = $response->json();

                return $this->failure(
                    is_array($json) ? (string) ($json['message'] ?? 'Engine wajah Python menolak permintaan.') : 'Engine wajah Python menolak permintaan.',
                    is_array($json) ? (string) ($json['notes'] ?? 'engine_request_failed') : 'engine_request_failed',
                    $response->status()
                );
            }

            $json = $response->json();
            if (!is_array($json)) {
                return $this->failure(
                    'Respons engine wajah Python tidak dapat dibaca.',
                    'engine_invalid_response',
                    502
                );
            }

            return $json;
        } catch (\Throwable $exception) {
            return $this->failure(
                'Engine wajah Python tidak dapat dihubungi dari Laravel.',
                'engine_unreachable',
                503
            );
        }
    }

    private function request(): PendingRequest
    {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->baseUrl((string) config('kiosk_face.python_service.base_url'))
            ->timeout((int) config('kiosk_face.python_service.timeout', 20))
            ->connectTimeout((int) config('kiosk_face.python_service.connect_timeout', 5));

        $apiKey = trim((string) config('kiosk_face.python_service.api_key'));
        if ($apiKey !== '') {
            $request = $request->withHeaders([
                'X-Kiosk-Service-Key' => $apiKey,
            ]);
        }

        return $request;
    }

    private function buildCandidates(iterable $users): array
    {
        $candidates = [];

        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $payload = $this->buildCandidate($user);
            if ($payload !== null) {
                $candidates[] = $payload;
            }
        }

        return $candidates;
    }

    private function buildCandidate(User $user): ?array
    {
        $stored = $user->decodedFaceData();
        if (!is_array($stored) || $stored === []) {
            return null;
        }

        $vectors = [];

        $embedding = $this->normalizeVector($stored['face_embedding'] ?? null);
        if ($embedding !== []) {
            $vectors[] = [
                'type' => 'face_embedding',
                'dimension' => count($embedding),
                'values' => $embedding,
            ];
        }

        $descriptor = $this->normalizeVector($stored['face_descriptor'] ?? null);
        if ($descriptor !== []) {
            $vectors[] = [
                'type' => 'face_descriptor',
                'dimension' => count($descriptor),
                'values' => $descriptor,
            ];
        }

        if ($vectors === [] && isset($stored['descriptors']) && is_array($stored['descriptors'])) {
            foreach ($stored['descriptors'] as $index => $item) {
                $normalized = $this->normalizeVector($item);
                if ($normalized === []) {
                    continue;
                }

                $vectors[] = [
                    'type' => 'face_descriptor',
                    'dimension' => count($normalized),
                    'values' => $normalized,
                    'index' => $index,
                ];
            }
        }

        if ($vectors === []) {
            return null;
        }

        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'face_id' => $user->face_id,
            'vectors' => $vectors,
        ];
    }

    private function normalizeFrames(array $payload): array
    {
        $frames = [];

        if (isset($payload['selfie_data'])) {
            $frame = $this->normalizeDataUrl($payload['selfie_data']);
            if ($frame) {
                $frames[] = $frame;
            }
        }

        if (isset($payload['selfie_frames']) && is_array($payload['selfie_frames'])) {
            foreach ($payload['selfie_frames'] as $frame) {
                $normalized = $this->normalizeDataUrl($frame);
                if ($normalized) {
                    $frames[] = $normalized;
                }
            }
        }

        $maxFrames = max(1, (int) config('kiosk_face.capture.max_frames', 8));

        return collect($frames)
            ->filter(fn ($frame) => is_string($frame) && str_starts_with($frame, 'data:image/'))
            ->unique()
            ->take($maxFrames)
            ->values()
            ->all();
    }

    private function normalizeContext(array $context): array
    {
        return collect($context)
            ->map(function ($value) {
                if (is_scalar($value) || $value === null) {
                    return $value;
                }

                if (is_array($value)) {
                    return $value;
                }

                return (string) $value;
            })
            ->all();
    }

    private function normalizeDataUrl(mixed $value): ?string
    {
        return is_string($value) && str_starts_with($value, 'data:image/')
            ? $value
            : null;
    }

    private function normalizeVector(mixed $vector): array
    {
        if (!is_array($vector)) {
            return [];
        }

        $normalized = [];
        foreach ($vector as $value) {
            if (!is_numeric($value)) {
                return [];
            }

            $normalized[] = (float) $value;
        }

        return $normalized;
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
                    'score' => $this->normalizeFloat($challenge['score'] ?? null),
                    'detail' => isset($challenge['detail']) ? (string) $challenge['detail'] : null,
                    'timestamp' => is_numeric($challenge['timestamp'] ?? null)
                        ? (int) $challenge['timestamp']
                        : now()->timestamp,
                ];
            })
            ->values()
            ->all();
    }

    private function normalizeFloat(mixed $value): ?float
    {
        return is_numeric($value) ? round((float) $value, 4) : null;
    }

    private function failure(string $message, string $notes, int $status = 422): array
    {
        return [
            'success' => false,
            'message' => $message,
            'notes' => $notes,
            'status' => $status,
        ];
    }
}
