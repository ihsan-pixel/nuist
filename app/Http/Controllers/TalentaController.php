<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TalentaPeserta;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaMateri;

class TalentaController extends Controller
{
    public function login()
    {
        // If already authenticated, redirect to talenta index
        if (Auth::check()) {
            return redirect()->route('talenta.dashboard');
        }

        return view('talenta.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('talenta.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('talenta.dashboard');
    }

    public function data()
    {
        // Fetch real data from database with eager loading
        $pesertaTalenta = TalentaPeserta::with(['user.madrasah'])
            ->latest()
            ->get();

        // Fetch pemateri talenta - eager load materi relationship
        $pemateriTalenta = TalentaPemateri::with('materi')->latest()->get();

        // Fetch fasilitator talenta - no user relationship, uses direct fields
        $fasilitatorTalenta = TalentaFasilitator::latest()->get();

        // Fetch materi talenta
        $materiTalenta = TalentaMateri::latest()->get();

        return view('talenta.data', compact('pesertaTalenta', 'pemateriTalenta', 'fasilitatorTalenta', 'materiTalenta'));
    }

    public function instrumenPenilaian()
    {
        // Fetch peserta talenta for dropdown selection
        $pesertaTalenta = TalentaPeserta::with(['user.madrasah'])
            ->latest()
            ->get();

        // Fetch fasilitator talenta
        $fasilitatorTalenta = TalentaFasilitator::with('materi')
            ->latest()
            ->get();

        // Fetch pemateri talenta
        $pemateriTalenta = TalentaPemateri::with('materi')
            ->latest()
            ->get();

        return view('talenta.instrumen-penilaian', compact('pesertaTalenta', 'fasilitatorTalenta', 'pemateriTalenta'));
    }

    public function tugasLevel1()
    {
        return view('talenta.tugas-level-1');
    }

    public function simpanTugasLevel1(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'jenis_tugas' => 'required|string',
            // Add more validation rules as needed
        ]);

        // TODO: Add logic to save the tugas-level-1 data to database
        // For now, we'll just return a success message
        return redirect()->route('talenta.tugas-level-1')
            ->with('success', 'Tugas Level 1 berhasil disimpan!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('talenta.login');
    }
}
