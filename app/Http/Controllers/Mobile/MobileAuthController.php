<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileAuthController extends Controller
{
    /**
     * Handle mobile form login. Only allow role 'tenaga_pendidik'.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Email atau password salah'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Only allow tenaga_pendidik role
        if (isset($user->role) && $user->role !== 'tenaga_pendidik') {
            // logout and invalidate session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Akun tidak memiliki akses mobile (hanya Tenaga Pendidik).'])
                ->withInput($request->only('email'));
        }

        // Successful login for tenaga_pendidik -> redirect to mobile dashboard
        return redirect()->route('mobile.dashboard');
    }
}
