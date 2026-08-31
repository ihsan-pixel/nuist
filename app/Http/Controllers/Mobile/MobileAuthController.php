<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\SiswaMobileAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    /**
     * Handle mobile form login for supported mobile roles.
     */
    public function authenticate(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            try {
                $user = $siswaMobileAuthService->authenticate($credentials['email'], $credentials['password']);
            } catch (ValidationException $exception) {
                return redirect()->route('mobile.login')
                    ->withErrors($exception->errors())
                    ->withInput($request->only('email'));
            }

            if (!$user) {
            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Email atau password salah'])
                ->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        }

        $request->session()->regenerate();

        $queuedCookies = [];

        if ($request->boolean('remember')) {
            $queuedCookies[] = Cookie::forever('mobile_login_email', $credentials['email']);
            $queuedCookies[] = Cookie::forever('mobile_login_remember', '1');
        } else {
            $queuedCookies[] = Cookie::forget('mobile_login_email');
            $queuedCookies[] = Cookie::forget('mobile_login_remember');
        }

        $user = Auth::user();

        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Akun Anda saat ini dinonaktifkan.'])
                ->withInput($request->only('email'))
                ->withCookies($queuedCookies);
        }

        if (!isset($user->role) || !in_array($user->role, ['tenaga_pendidik', 'siswa', 'dps'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Akun tidak memiliki akses mobile.'])
                ->withInput($request->only('email'))
                ->withCookies($queuedCookies);
        }

        if ($user->role === 'siswa') {
            return redirect()->route('mobile.siswa.dashboard')->withCookies($queuedCookies);
        }

        if ($user->role === 'dps') {
            return redirect()->route('mobile.dps.dashboard')->withCookies($queuedCookies);
        }

        return redirect()->route('mobile.dashboard')->withCookies($queuedCookies);
    }
}
