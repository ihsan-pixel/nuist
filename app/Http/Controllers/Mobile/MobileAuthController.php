<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\SiswaMobileAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    /**
     * Handle mobile form login for supported mobile roles.
     */
    public function authenticate(Request $request, SiswaMobileAuthService $siswaMobileAuthService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
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

        $user = Auth::user();

        if (!isset($user->role) || !in_array($user->role, ['tenaga_pendidik', 'siswa'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Akun tidak memiliki akses mobile.'])
                ->withInput($request->only('email'));
        }

        if ($user->role === 'siswa') {
            return redirect()->route('mobile.siswa.dashboard');
        }

        return redirect()->route('mobile.dashboard');
    }
}
