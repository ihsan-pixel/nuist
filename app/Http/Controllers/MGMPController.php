<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MGMPController extends Controller
{
    /* =========================
     * AUTH
     * ========================= */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('mgmp.dashboard');
        }

        return view('mgmp.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('mgmp.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mgmp.login');
    }

    /* =========================
     * DASHBOARD & LAPORAN
     * ========================= */
    public function dashboard()
    {
        return view('mgmp.dashboard');
    }

    public function laporan()
    {
        return view('mgmp.laporan');
    }
}
