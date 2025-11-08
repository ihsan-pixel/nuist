<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\User;

class FaceController extends Controller
{
    // Thresholds - adjust as needed or move to config
    private float $SIMILARITY_THRESHOLD = 0.8;
    private float $LIVENESS_THRESHOLD = 0.7;

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
            'liveness_challenges' => 'required|array|min:3',
        ]);

        $userId = $request->input('user_id');
        $livenessScore = (float) $request->input('liveness_score');
        $livenessChallenges = $request->input('liveness_challenges');

        // Validate liveness requirements for enrollment
        if ($livenessScore < $this->LIVENESS_THRESHOLD) {
            return response()->json([
                'success' => false,
                'message' => 'Liveness score tidak mencukupi untuk pendaftaran wajah. Pastikan semua tantangan verifikasi diselesaikan dengan benar.'
            ], 400);
        }

        if (count($livenessChallenges) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 3 tantangan liveness harus diselesaikan untuk pendaftaran wajah.'
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

        $faceData = $request->input('face_data');

        // Store encrypted face data as JSON string with additional metadata
        try {
            $enrollmentData = [
                'face_descriptor' => $faceData,
                'liveness_score' => $livenessScore,
                'liveness_challenges' => $livenessChallenges,
                'enrolled_at' => now()->toISOString(),
                'enrolled_by' => $auth->id,
                'device_info' => $request->input('device_info'),
            ];

            $payload = json_encode($enrollmentData);
            $user->face_data = Crypt::encryptString($payload);
            $user->face_id = (string) Str::uuid();
            $user->face_registered_at = now();
            $user->face_verification_required = true;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Face enrolled successfully.',
                'face_id' => $user->face_id,
                'liveness_score' => $livenessScore,
                'challenges_completed' => count($livenessChallenges)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save face data.', 'error' => $e->getMessage()], 500);
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

        $provided = $request->input('face_descriptor');
        $livenessScore = (float) $request->input('liveness_score');
        $challenges = $request->input('liveness_challenges', []);

        // Retrieve stored face data, support both encrypted string and plain array
        $storedDescriptors = null;
        try {
            if (is_string($user->face_data) && !empty($user->face_data)) {
                // try decrypt
                $decrypted = Crypt::decryptString($user->face_data);
                $stored = json_decode($decrypted, true);
            } else {
                $stored = $user->face_data;
            }
        } catch (\Exception $e) {
            // Not encrypted or decryption failed, fallback to raw
            $stored = $user->face_data;
        }

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

        // Compute best similarity among stored descriptors
        $best = 0.0;
        foreach ($storedDescriptors as $s) {
            if (!is_array($s) || !is_array($provided)) continue;
            $sim = $this->cosineSimilarity($s, $provided);
            if ($sim > $best) $best = $sim;
        }

        $faceVerified = ($best >= $this->SIMILARITY_THRESHOLD) && ($livenessScore >= $this->LIVENESS_THRESHOLD);

        return response()->json([
            'success' => true,
            'face_verified' => $faceVerified,
            'similarity' => round($best, 4),
            'similarity_threshold' => $this->SIMILARITY_THRESHOLD,
            'liveness_score' => $livenessScore,
            'liveness_threshold' => $this->LIVENESS_THRESHOLD,
            'liveness_challenges' => $challenges,
        ]);
    }

    /**
     * Cosine similarity between two numeric arrays
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        $len = min(count($a), count($b));
        if ($len === 0) return 0.0;

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        for ($i = 0; $i < $len; $i++) {
            $ai = (float) $a[$i];
            $bi = (float) $b[$i];
            $dot += $ai * $bi;
            $normA += $ai * $ai;
            $normB += $bi * $bi;
        }

        if ($normA <= 0 || $normB <= 0) return 0.0;

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
