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
     * Handle mobile form login for every active role.
     */
    public function authenticate(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            try {
                $user = $siswaMobileAuthService->authenticate($credentials['email'], $credentials['password']);
            } catch (ValidationException $exception) {
                return redirect()->route('mobile.login')
                    ->withErrors($exception->errors())
                    ->withInput($request->only('email'));
            }

            if (! $user) {
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

        if (isset($user->is_active) && ! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Akun Anda saat ini dinonaktifkan.'])
                ->withInput($request->only('email'))
                ->withCookies($queuedCookies);
        }

        $role = preg_replace('/\s+/', '_', trim(strtolower((string) $user->role))) ?? '';

        if ($role === 'pengurus_bpppmnu') {
            return redirect()->route('mobile.bpppmnu.presensi')->withCookies($queuedCookies);
        }

        if ($role === 'siswa') {
            return redirect()->route('mobile.siswa.dashboard')->withCookies($queuedCookies);
        }

        if ($role === 'dps') {
            return redirect()->route('mobile.dps.dashboard')->withCookies($queuedCookies);
        }

        if ($role === 'tenaga_pendidik') {
            return redirect()->route('mobile.dashboard')->withCookies($queuedCookies);
        }

        if ($role === 'admin_spp') {
            return redirect()->route('spp-siswa.dashboard')->withCookies($queuedCookies);
        }

        if ($role === 'mgmp') {
            return redirect()->route('mgmp.dashboard')->withCookies($queuedCookies);
        }

        if (in_array($role, ['pemateri', 'fasilitator'], true)) {
            return redirect()->route('talenta.dashboard')->withCookies($queuedCookies);
        }

        return redirect()->intended('/dashboard')->withCookies($queuedCookies);
    }
}
