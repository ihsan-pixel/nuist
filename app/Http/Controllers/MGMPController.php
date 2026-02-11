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
        // Assumption: members are users with role 'tenaga_pendidik' or 'mgmp'
        // If your project stores MGMP members differently, adjust the query accordingly.
        $members = User::whereIn('role', ['tenaga_pendidik', 'mgmp'])->get();

        return view('mgmp.data-anggota', compact('members'));
    }

    /**
     * Show Data MGMP (management UI)
     */
    public function manage()
    {
        // Placeholder collection for MGMP groups. Replace with real model when available.
        $mgmpGroups = collect();

        return view('mgmp.data-mgmp', compact('mgmpGroups'));
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

        // Provide members list for the modal attendee selector
        $members = User::whereIn('role', ['tenaga_pendidik', 'mgmp'])->get();

        return view('mgmp.laporan', compact(
            'user',
            'laporan',
            'totalLaporan',
            'laporanBulanIni',
            'totalPeserta',
            'rataRataDurasi',
            'members'
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

