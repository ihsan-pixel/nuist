<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SiswaMobileAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mobile login: issue a personal access token for the user.
     * Expected payload: { email, password }
     */
    public function login(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($data)) {
            try {
                $user = $siswaMobileAuthService->authenticate($data['email'], $data['password']);
            } catch (ValidationException $exception) {
                return response()->json([
                    'message' => $exception->errors()['email'][0] ?? 'Login gagal',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if (!$user) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            Auth::login($user);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!in_array($user->role, ['tenaga_pendidik', 'siswa'])) {
            Auth::logout();

            return response()->json([
                'message' => 'Akun tidak memiliki akses mobile',
            ], 403);
        }

        // Create a token named 'mobile' (uses HasApiTokens trait)
        $token = $user->createToken('mobile-token')->plainTextToken;

        $mobileRoute = $user->role === 'siswa'
            ? '/mobile/siswa/dashboard'
            : '/mobile/dashboard';

        return response()->json([
            'token' => $token,
            'user' => $user,
            'mobile_route' => $mobileRoute,
        ]);
    }

    /**
     * Revoke current token (logout)
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // Revoke current access token
            $user->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }
}
