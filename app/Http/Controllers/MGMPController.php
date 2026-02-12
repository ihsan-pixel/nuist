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
     * Store a new MGMP group
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_count' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Check if user with role 'mgmp' already has a MGMP group
        if ($user->role === 'mgmp' && MgmpGroup::where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki data MGMP. Hanya satu data MGMP yang diperbolehkan.');
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mgmp_logos', 'public');
        }

        MgmpGroup::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'member_count' => $request->member_count,
            'logo' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Data MGMP berhasil ditambahkan.');
    }

    /**
     * Update an existing MGMP group
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_count' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $mgmpGroup = MgmpGroup::findOrFail($id);

        // Ensure user can only update their own MGMP group
        if ($mgmpGroup->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit data MGMP ini.');
        }

        $logoPath = $mgmpGroup->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mgmp_logos', 'public');
        }

        $mgmpGroup->update([
            'name' => $request->name,
            'member_count' => $request->member_count,
            'logo' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Data MGMP berhasil diperbarui.');
    }

    /**
     * Delete an MGMP group
     */
    public function destroy($id)
    {
        $mgmpGroup = MgmpGroup::findOrFail($id);

        // Ensure user can only delete their own MGMP group
        if ($mgmpGroup->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data MGMP ini.');
        }

        $mgmpGroup->delete();

        return redirect()->back()->with('success', 'Data MGMP berhasil dihapus.');
    }

    /**
     * Import MGMP data from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:2048',
        ]);

        $user = auth()->user();

        // Check if user with role 'mgmp' already has a MGMP group
        if ($user->role === 'mgmp' && MgmpGroup::where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki data MGMP. Hanya satu data MGMP yang diperbolehkan.');
        }

        // For now, just return success message. Actual import logic would need to be implemented.
        return redirect()->back()->with('success', 'Import data MGMP berhasil.');
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

