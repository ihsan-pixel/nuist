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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
            'turnstile_token' => ['required', 'string', 'max:2048'],
        ]);

        if (!$this->verifyTurnstile($data['turnstile_token'], $request->ip())) {
            return response()->json(['message' => 'Verifikasi CAPTCHA gagal. Silakan ulangi CAPTCHA.'], 422);
        }

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

    public function resetStudentPassword(Request $request, \App\Services\StudentDefaultPasswordService $passwords)
    {
        $data = $request->validate([
            'nisn' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'nama_ibu' => ['required', 'string', 'max:255'],
            'turnstile_token' => ['required', 'string', 'max:2048'],
        ]);

        $key = 'student-password-reset:' . hash('sha256', $request->ip() . '|' . $data['nisn']);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json(['message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.'], 429);
        }

        if (!$this->verifyTurnstile($data['turnstile_token'], $request->ip())) {
            RateLimiter::hit($key, 900);
            return response()->json(['message' => 'Verifikasi CAPTCHA gagal. Silakan ulangi CAPTCHA.'], 422);
        }

        $siswa = \App\Models\Siswa::query()
            ->where('nisn', trim($data['nisn']))
            ->where('is_active', true)
            ->first();

        if (!$siswa || !$siswa->tanggal_lahir?->isSameDay($data['tanggal_lahir'])) {
            RateLimiter::hit($key, 900);
            return response()->json(['message' => 'Data verifikasi tidak sesuai.'], 422);
        }

        if (blank($siswa->nama_ibu)) {
            return response()->json([
                'message' => 'Data verifikasi belum lengkap. Hubungi admin sekolah untuk pembaruan data atau reset password.',
                'code' => 'student_verification_incomplete',
            ], 422);
        }

        $sameMother = hash_equals(
            $this->normalizeVerificationText((string) $siswa->nama_ibu),
            $this->normalizeVerificationText($data['nama_ibu'])
        );

        if (!$sameMother) {
            RateLimiter::hit($key, 900);
            return response()->json(['message' => 'Data verifikasi tidak sesuai.'], 422);
        }

        if (!$passwords->resetToDefault($siswa)) {
            return response()->json(['message' => 'Password belum dapat direset. Hubungi admin sekolah.'], 422);
        }

        $linkKey = 'S' . str_pad(strtoupper(base_convert((string) $siswa->id, 10, 36)), 5, '0', STR_PAD_LEFT);
        \App\Models\User::query()->where('nuist_id', $linkKey)->first()?->tokens()->delete();
        RateLimiter::clear($key);

        return response()->json(['message' => 'Password berhasil direset. Silakan login dengan password default berdasarkan tanggal lahir Anda.']);
    }

    private function verifyTurnstile(string $token, ?string $remoteIp): bool
    {
        $secret = config('services.turnstile.secret_key');
        if (blank($secret)) return false;

        try {
            return Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret, 'response' => $token, 'remoteip' => $remoteIp,
            ])->json('success') === true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function normalizeVerificationText(string $value): string
    {
        return Str::of($value)->lower()->ascii()->replaceMatches('/[^a-z0-9]+/', ' ')->trim()->toString();
    }

    /**
     * Mobile login: issue a personal access token for the selected role.
     * Expected payload: { identifier, password, login_as }
     */
    public function login(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $data = $request->validate([
            'identifier' => 'required_without:email|string',
            // Temporary fallback keeps released versions of the app working.
            'email' => 'required_without:identifier|string',
            'password' => 'required|string',
            'login_as' => 'nullable|in:siswa,tenaga_pendidik',
        ]);

        $identifier = trim((string) ($data['identifier'] ?? $data['email']));
        $loginAs = $data['login_as'] ?? null;

        if ($loginAs === 'siswa') {
            $user = $siswaMobileAuthService->authenticateByNisn($identifier, $data['password']);

            if (!$user) {
                return response()->json(['message' => 'NISN atau password siswa tidak sesuai.'], 401);
            }

            Auth::login($user);
        } else {
            // login_as tenaga_pendidik is intentionally email-only. The old
            // request format remains compatible when login_as is omitted.
            $credentials = [
                'email' => $identifier,
                'password' => $data['password'],
            ];

            if (!Auth::attempt($credentials)) {
                if ($loginAs === 'tenaga_pendidik') {
                    return response()->json(['message' => 'Email atau password tenaga pendidik tidak sesuai.'], 401);
                }

                try {
                    $user = $siswaMobileAuthService->authenticate($identifier, $data['password']);
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

        if ($loginAs && $user->role !== $loginAs) {
            Auth::logout();

            return response()->json([
                'message' => 'Akun tidak sesuai dengan jenis login yang dipilih.',
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
