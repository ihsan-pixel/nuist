<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;

class FaceController extends Controller
{
    // Thresholds - adjust as needed or move to config
    private float $FACE_DISTANCE_THRESHOLD = 0.55;
    private float $LIVENESS_THRESHOLD = 0.78;

    /**
     * Enroll face data for a user (admin or user self-enroll).
     * Expected payload: user_id, face_data (array of numbers or object with descriptors), liveness_score, liveness_challenges
     */
    public function enroll(Request $request)
    {
        $auth = Auth::user();

        $request->validate([
            'user_id' => 'required|integer',
            'face_data' => 'required',
            'liveness_score' => 'required|numeric|min:0|max:1',
            'liveness_challenges' => 'nullable|array',
        ]);

        $userId = $request->input('user_id');
        $livenessScore = (float) $request->input('liveness_score');
        $livenessChallenges = $request->input('liveness_challenges');

        // Validate liveness requirements for enrollment
        if ($livenessScore < $this->LIVENESS_THRESHOLD) {
            return response()->json([
                'success' => false,
                'message' => 'Scan wajah belum cukup stabil untuk pendaftaran. Posisikan wajah dengan jelas lalu coba lagi.'
            ], 400);
        }

        // Only allow admin/kepala or the user themselves to enroll
        if ($auth->id !== (int)$userId && !in_array($auth->role, ['admin', 'kepala madrasah', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to enroll face for this user.'], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $faceData = $this->normalizeDescriptor($request->input('face_data'));
        if ($faceData === []) {
            return response()->json([
                'success' => false,
                'message' => 'Data wajah hasil scan tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ], 422);
        }

        $normalizedChallenges = $this->normalizeChallenges($livenessChallenges);
        $replacingExistingFace = $user->hasFaceEnrollment();

        // Store encrypted face data as JSON string with additional metadata
        try {
            $enrollmentData = [
                'face_descriptor' => $faceData,
                'liveness_score' => $livenessScore,
                'liveness_challenges' => $normalizedChallenges,
                'enrolled_at' => now()->toIso8601String(),
                'enrolled_by' => $auth->id,
                'device_info' => is_string($request->input('device_info'))
                    ? Str::limit($request->input('device_info'), 1000, '')
                    : null,
                'replaced_previous_face' => $replacingExistingFace,
            ];

            $payload = json_encode($enrollmentData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
            $user->face_data = $payload;
            $user->face_id = (string) Str::uuid();
            $user->face_registered_at = now();
            $user->face_verification_required = true;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $replacingExistingFace
                    ? 'Data wajah berhasil diperbarui. Wajah terakhir kini menjadi data presensi aktif.'
                    : 'Data wajah berhasil disimpan.',
                'face_id' => $user->face_id,
                'liveness_score' => $livenessScore,
                'challenges_completed' => count($normalizedChallenges),
                'replaced_previous_face' => $replacingExistingFace,
            ]);
        } catch (\Exception $e) {
            Log::error('Face enrollment save failed', [
                'user_id' => $userId,
                'auth_user_id' => $auth->id ?? null,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data wajah gagal disimpan. Silakan ulangi scan wajah lalu coba simpan kembali.',
                'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Verify provided face descriptor against stored enrollment.
     * Expected payload: face_descriptor (array), liveness_score (float 0..1), liveness_challenges (array)
     * If user_id is provided and auth has proper rights, verify against that user; otherwise verify current auth user.
     */
    public function verify(Request $request)
    {
        $auth = Auth::user();

        $request->validate([
            'face_descriptor' => 'required',
            'liveness_score' => 'required|numeric',
            'liveness_challenges' => 'nullable',
            'user_id' => 'nullable|integer',
        ]);

        $targetUserId = $request->input('user_id') ?: $auth->id;

        // If verifying another user, require admin-like role
        if ($targetUserId !== $auth->id && !in_array($auth->role, ['admin', 'kepala madrasah', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to verify this user.'], 403);
        }

        $user = User::find($targetUserId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $provided = $this->normalizeDescriptor($request->input('face_descriptor'));
        if ($provided === []) {
            return response()->json([
                'success' => false,
                'face_verified' => false,
                'message' => 'Data descriptor wajah tidak valid. Silakan ulangi scan wajah.',
                'notes' => 'invalid_face_descriptor',
            ], 422);
        }

        $livenessScore = (float) $request->input('liveness_score');
        $challenges = $request->input('liveness_challenges', []);
        $normalizedChallenges = $this->normalizeChallenges($challenges);

        // Retrieve stored face data, support encrypted string and legacy plain payloads
        $storedDescriptors = null;
        $stored = $user->decodedFaceData();

        // Normalize stored descriptors: support new enrollment format with metadata
        if (is_array($stored) && !empty($stored)) {
            // Check if this is the new format with face_descriptor key
            if (isset($stored['face_descriptor'])) {
                $storedDescriptors = [$stored['face_descriptor']];
            }
            // If stored contains top-level 'descriptors' key (legacy)
            elseif (isset($stored['descriptors']) && is_array($stored['descriptors'])) {
                $storedDescriptors = $stored['descriptors'];
            } else {
                // assume stored is a single descriptor array (legacy)
                $storedDescriptors = [$stored];
            }
        }

        if (empty($storedDescriptors)) {
            return response()->json(['success' => false, 'message' => 'No enrolled face data for this user.'], 400);
        }

        // Compute smallest face-api Euclidean distance among stored descriptors.
        $bestDistance = null;
        foreach ($storedDescriptors as $s) {
            $storedDescriptor = $this->normalizeDescriptor($s);
            if ($storedDescriptor === []) {
                continue;
            }

            $distance = $this->euclideanDistance($storedDescriptor, $provided);
            if ($bestDistance === null || $distance < $bestDistance) {
                $bestDistance = $distance;
            }
        }

        $bestDistance ??= INF;
        $similarity = $this->distanceToSimilarity($bestDistance);
        $faceMatched = $bestDistance <= $this->FACE_DISTANCE_THRESHOLD;
        $livenessVerified = $livenessScore >= $this->LIVENESS_THRESHOLD;
        $challengeVerified = $this->verifyCompletedChallengePayload($normalizedChallenges);
        $faceVerified = $faceMatched && $livenessVerified && $challengeVerified;
        $notes = 'face_verified';
        $message = 'Wajah cocok dengan data terdaftar.';

        if (!$faceMatched) {
            $notes = 'face_similarity_below_threshold';
            $message = 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.';
        } elseif (!$livenessVerified) {
            $notes = 'liveness_below_threshold';
            $message = 'Presensi ditolak karena verifikasi wajah belum valid. Silakan ulangi scan wajah.';
        } elseif (!$challengeVerified) {
            $notes = 'challenge_payload_invalid';
            $message = 'Challenge wajah belum valid. Silakan ulangi scan wajah.';
        }

        return response()->json([
            'success' => $faceVerified,
            'face_verified' => $faceVerified,
            'message' => $message,
            'notes' => $notes,
            'similarity' => round($similarity, 4),
            'face_distance' => is_finite($bestDistance) ? round($bestDistance, 4) : null,
            'face_distance_threshold' => $this->FACE_DISTANCE_THRESHOLD,
            'liveness_score' => $livenessScore,
            'liveness_threshold' => $this->LIVENESS_THRESHOLD,
            'liveness_challenges' => $normalizedChallenges,
        ]);
    }

    private function euclideanDistance(array $a, array $b): float
    {
        if (count($a) !== 128 || count($b) !== 128) {
            return INF;
        }

        $sum = 0.0;
        for ($i = 0; $i < 128; $i++) {
            $delta = (float) $a[$i] - (float) $b[$i];
            $sum += $delta * $delta;
        }

        return sqrt($sum);
    }

    private function distanceToSimilarity(float $distance): float
    {
        return is_finite($distance) ? max(0.0, 1.0 - $distance) : 0.0;
    }

    private function normalizeDescriptor(mixed $descriptor): array
    {
        if (!is_array($descriptor)) {
            return [];
        }

        $normalized = [];

        foreach ($descriptor as $value) {
            if (!is_numeric($value)) {
                return [];
            }

            $normalized[] = (float) $value;
        }

        return count($normalized) === 128 ? $normalized : [];
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
                    'score' => is_numeric($challenge['score'] ?? null) ? round((float) $challenge['score'], 4) : null,
                    'detail' => isset($challenge['detail']) ? (string) $challenge['detail'] : null,
                    'timestamp' => $challenge['timestamp'] ?? now()->timestamp,
                ];
            })
            ->values()
            ->all();
    }

    private function verifyCompletedChallengePayload(array $challenges): bool
    {
        if ($challenges === []) {
            return true;
        }

        return $this->hasPassedChallenge($challenges, 'blink')
            && $this->hasPassedChallenge($challenges, 'face_captured')
            && $this->hasAnyPassedChallenge($challenges, ['turn_left', 'turn_right', 'look_up', 'look_down', 'mouth_open'])
            && $this->hasPassedChallenge($challenges, 'screen_replay_risk')
            && $this->hasPassedChallenge($challenges, 'risk_score');
    }

    private function hasPassedChallenge(array $challenges, string $type): bool
    {
        foreach ($challenges as $challenge) {
            if (($challenge['type'] ?? null) === $type && ($challenge['passed'] ?? false) === true) {
                return true;
            }
        }

        return false;
    }

    private function hasAnyPassedChallenge(array $challenges, array $types): bool
    {
        foreach ($types as $type) {
            if ($this->hasPassedChallenge($challenges, $type)) {
                return true;
            }
        }

        return false;
    }

}
