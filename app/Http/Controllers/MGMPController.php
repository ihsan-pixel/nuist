<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MGMPController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:mgmp');
    }

    /**
     * Show MGMP Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get MGMP statistics
        $totalAnggota = User::where('role', 'mgmp')->count();
        $anggotaAktif = User::where('role', 'mgmp')->where('is_active', true)->count();

        return view('mgmp.dashboard', compact(
            'user',
            'totalAnggota',
            'anggotaAktif'
        ));
    }

    /**
     * Show MGMP Data Anggota
     */
    public function dataAnggota()
    {
        $user = Auth::user();

        // Get all MGMP members
        $anggota = User::where('role', 'mgmp')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('mgmp.data_anggota', compact(
            'user',
            'anggota'
        ));
    }

    /**
     * Show MGMP Laporan Kegiatan
     */
    public function laporan()
    {
        $user = Auth::user();

        // Get laporan kegiatan (placeholder - can be extended with database model)
        $laporan = [];

        return view('mgmp.laporan', compact(
            'user',
            'laporan'
        ));
    }

    /**
     * Logout MGMP user
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah logout dari MGMP.');
    }
}

