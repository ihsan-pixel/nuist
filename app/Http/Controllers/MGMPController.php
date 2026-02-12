<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\MgmpGroup;
use App\Models\MgmpMember;
use App\Models\MgmpReport;

class MGMPController extends Controller
{
    /**
     * Show MGMP Landing Page (Public)
     */
    public function index()
    {
        // Core MGMP metrics for the landing page
        try {
            $totalAnggota = MgmpMember::count();
        } catch (\Throwable $e) {
            // fallback to counting users with role mgmp if table/model isn't migrated yet
            $totalAnggota = User::where('role', 'mgmp')->count();
        }

        try {
            $totalKegiatan = MgmpReport::count();
        } catch (\Throwable $e) {
            $totalKegiatan = 0;
        }

        // Placeholder for materi count (if you have a materi model, replace this)
        $totalMateri = 0;

        // Recent groups to show on index (if available)
        try {
            $mgmpGroups = MgmpGroup::limit(6)->get();
        } catch (\Throwable $e) {
            $mgmpGroups = collect();
        }

        return view('mgmp.index', compact('totalAnggota', 'totalKegiatan', 'totalMateri', 'mgmpGroups'));
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
        $mgmpGroups = MgmpGroup::all();
        $canAdd = true;

        if (auth()->user()->role === 'mgmp' && $mgmpGroups->count() > 0) {
            $canAdd = false;
        }

        return view('mgmp.data-mgmp', compact('mgmpGroups', 'canAdd'));
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

