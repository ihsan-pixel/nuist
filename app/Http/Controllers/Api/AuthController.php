<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationPendingNotification;
use App\Models\Madrasah;
use App\Models\PendingRegistration;
use App\Models\PushDeviceToken;
use App\Services\SiswaMobileAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerOptions()
    {
        $madrasahs = Madrasah::query()
            ->orderBy('scod')
            ->get(['id', 'name']);

        return response()->json([
            'madrasahs' => $madrasahs,
        ]);
    }

    public function register(Request $request)
    {
        $role = $request->input('role');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:pengurus,tenaga_pendidik'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($role === 'pengurus') {
            $rules['jabatan'] = ['required', 'string', 'max:255'];
        }

        if ($role === 'tenaga_pendidik') {
            $rules['asal_sekolah'] = ['required', 'exists:madrasahs,id'];
        }

        $data = $request->validate($rules);

        $pendingRegistration = PendingRegistration::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'jabatan' => $data['jabatan'] ?? null,
            'asal_sekolah' => $data['asal_sekolah'] ?? null,
            'submitted_at' => now(),
        ]);

        Mail::to($data['email'])->send(
            new RegistrationPendingNotification($pendingRegistration)
        );

        return response()->json([
            'message' => 'Registration submitted successfully. Please wait for admin approval.',
            'data' => $pendingRegistration,
        ], 201);
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __($status),
                'errors' => [
                    'email' => [__($status)],
                ],
            ], 422);
        }

        return response()->json([
            'message' => __($status),
        ]);
    }

    /**
     * Mobile login: issue a personal access token for the user.
     * Expected payload: { email, password }
     */
    public function login(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $data = $request->validate([
            'email' => 'required|string',
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

        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();

            return response()->json([
                'message' => 'Akun Anda saat ini dinonaktifkan.',
            ], 403);
        }

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

    public function registerPushToken(Request $request)
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
            'platform' => ['nullable', 'string', 'max:32'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        PushDeviceToken::query()->updateOrCreate(
            ['token' => trim((string) $validated['token'])],
            [
                'user_id' => $user->id,
                'platform' => trim((string) ($validated['platform'] ?? '')) ?: null,
                'device_name' => trim((string) ($validated['device_name'] ?? '')) ?: null,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Push token berhasil disimpan.',
        ]);
    }

    public function unregisterPushToken(Request $request)
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
        ]);

        PushDeviceToken::query()
            ->where('user_id', $user->id)
            ->where('token', trim((string) $validated['token']))
            ->delete();

        return response()->json([
            'message' => 'Push token berhasil dihapus.',
        ]);
    }
}
