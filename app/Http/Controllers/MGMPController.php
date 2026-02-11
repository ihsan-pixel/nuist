<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;

class MGMPController extends Controller
{
    /**
     * Show MGMP Landing Page (Public)
     */
    public function index()
    {
        return view('mgmp.index');
    }

    /**
     * Show MGMP Dashboard
     */
    public function dashboard()
    {
        return view('mgmp.dashboard');
    }

    /**
     * Show MGMP Data Anggota
     */
    public function dataAnggota()
    {
        $user = Auth::user();

        // Get all MGMP members
        $anggota = User::where('role', 'mgmp')
            ->with(['madrasah', 'statusKepegawaian'])
            ->orderBy('name', 'asc')
            ->paginate(10);

        // Statistics
        $totalAnggota = User::where('role', 'mgmp')->count();
        $anggotaAktif = User::where('role', 'mgmp')->where('is_active', true)->count();
        $totalSekolah = User::where('role', 'mgmp')->with('madrasah')->get()->pluck('madrasah')->unique()->filter()->count();
        $totalGuru = $totalAnggota;

        // Data for forms
        $sekolah = Madrasah::orderBy('name')->get();
        $statusKepegawaian = StatusKepegawaian::orderBy('name')->get();

        return view('mgmp.data-anggota', compact(
            'user',
            'anggota',
            'totalAnggota',
            'anggotaAktif',
            'totalSekolah',
            'totalGuru',
            'sekolah',
            'statusKepegawaian'
        ));
    }

    /**
     * Show MGMP Laporan Kegiatan
     */
    public function laporan()
    {
        $user = Auth::user();

        // Get laporan kegiatan (placeholder - can be extended with database model)
        $laporan = collect(); // Empty collection for now
        $totalLaporan = 0;
        $laporanBulanIni = 0;
        $totalPeserta = 0;
        $rataRataDurasi = 0;

        return view('mgmp.laporan', compact(
            'user',
            'laporan',
            'totalLaporan',
            'laporanBulanIni',
            'totalPeserta',
            'rataRataDurasi'
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

