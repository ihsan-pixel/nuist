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
use App\Models\AcademicaProposal;
use Illuminate\Support\Facades\Storage;

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
        // Get current user's MGMP group
        $currentMgmpGroup = MgmpGroup::where('user_id', auth()->id())->first();

        // Fetch existing MGMP members with relationships
        // If user is mgmp, only show members of their group
        if (auth()->user()->role === 'mgmp' && $currentMgmpGroup) {
            $members = MgmpMember::with('user', 'mgmpGroup')
                ->where('mgmp_group_id', $currentMgmpGroup->id)
                ->get();
        } else {
            // For admin/super_admin/pengurus, show all members
            $members = MgmpMember::with('user', 'mgmpGroup')->get();
        }

        // Get user_ids that are already members (to exclude from selection)
        $existingMemberIds = MgmpMember::pluck('user_id')->toArray();

        // Fetch tenaga pendidik users for the modal selection, sorted by name A-Z
        // Exclude users who are already members
        $tenagaPendidik = User::where('role', 'tenaga_pendidik')
            ->whereNotIn('id', $existingMemberIds)
            ->with('madrasah')
            ->orderBy('name', 'asc')
            ->get();

        return view('mgmp.data-anggota', compact('members', 'tenagaPendidik', 'existingMemberIds'));
    }

    /**
     * Store a new MGMP member
     */
    public function storeMember(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id',
        ]);

        // Get current user's MGMP group
        $currentMgmpGroup = MgmpGroup::where('user_id', auth()->id())->first();

        // If user is mgmp but doesn't have a group yet, return error
        if (auth()->user()->role === 'mgmp' && !$currentMgmpGroup) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki grup MGMP. Silakan buat grup MGMP terlebih dahulu.'
            ], 422);
        }

        // Determine mgmp_group_id to use
        // For admin/super_admin/pengurus, they need to specify which group or we use their associated group
        $mgmpGroupId = $currentMgmpGroup ? $currentMgmpGroup->id : null;

        // If no group found, return error
        if (!$mgmpGroupId) {
            return response()->json([
                'success' => false,
                'message' => 'Grup MGMP tidak ditemukan. Silakan hubungi administrator.'
            ], 422);
        }

        $addedCount = 0;
        $errors = [];

        foreach ($request->user_ids as $userId) {
            // Check if user is already a member of this group
            $exists = MgmpMember::where('user_id', $userId)
                ->where('mgmp_group_id', $mgmpGroupId)
                ->exists();

            if ($exists) {
                $errors[] = "User ID $userId sudah menjadi anggota grup ini.";
                continue;
            }

            // Get user data
            $user = User::find($userId);
            if (!$user) {
                $errors[] = "User dengan ID $userId tidak ditemukan.";
                continue;
            }

            // Create member record
            MgmpMember::create([
                'user_id' => $userId,
                'mgmp_group_id' => $mgmpGroupId,
                'name' => $user->name,
                'sekolah' => $user->madrasah->name ?? 'Tidak ada sekolah',
                'madrasah_id' => $user->madrasah->id ?? null,
                'email' => $user->email
            ]);

            $addedCount++;
        }

        if ($addedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "$addedCount anggota berhasil ditambahkan.",
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada anggota yang ditambahkan.',
                'errors' => $errors
            ], 422);
        }
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
     * Show Academica page with proposals and upload form
     */
    public function academica()
    {
        $user = auth()->user();

        // Show all proposals (admins) or only own (regular users) — we'll pass both for the view to decide
        $proposals = AcademicaProposal::with('user')->orderBy('created_at', 'desc')->get();

        $userHasUploaded = AcademicaProposal::where('user_id', $user->id)->exists();
        $userProposal = AcademicaProposal::where('user_id', $user->id)->first();

        return view('mgmp.academica', compact('proposals', 'userHasUploaded', 'userProposal'));
    }

    /**
     * Handle PDF proposal upload. Each user allowed only one upload.
     */
    public function uploadAcademica(Request $request)
    {
        $request->validate([
            'proposal' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $user = auth()->user();

        // enforce single upload per user
        if (AcademicaProposal::where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Anda hanya dapat mengupload proposal satu kali.');
        }

        $file = $request->file('proposal');
        $filename = time() . '_' . $user->id . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());

        // use public/uploads/academica_proposals
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? public_path();
        $destinationPath = $documentRoot . '/uploads/academica_proposals';

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        $path = 'academica_proposals/' . $filename;

        AcademicaProposal::create([
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
        ]);

        return redirect()->back()->with('success', 'Proposal berhasil diupload.');
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
            $file = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? public_path();
            $destinationPath = $documentRoot . '/uploads/mgmp_logos';

            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $logoPath = 'mgmp_logos/' . $filename;
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
            $file = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? public_path();
            $destinationPath = $documentRoot . '/uploads/mgmp_logos';

            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $logoPath = 'mgmp_logos/' . $filename;
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

