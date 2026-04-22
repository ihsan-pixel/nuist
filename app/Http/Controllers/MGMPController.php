<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StatusKepegawaian;
use App\Models\MgmpGroup;
use App\Models\MgmpMember;
use App\Models\MgmpReport;
use App\Models\MgmpAttendance;
use App\Models\AcademicaProposal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        $user = auth()->user();

        // If user has an MGMP group (role mgmp), show their group info; else show general stats
        $mgmpGroup = MgmpGroup::where('user_id', $user->id)->first();

        if ($mgmpGroup) {
            $memberCount = $mgmpGroup->members()->count();
            $totalReports = $mgmpGroup->reports()->count();
            $recentReports = $mgmpGroup->reports()->orderBy('created_at', 'desc')->limit(5)->get();

            // proposals by group members
            $groupMemberUserIds = $mgmpGroup->members()->pluck('user_id')->toArray();
            $proposalCount = AcademicaProposal::whereIn('user_id', $groupMemberUserIds)->count();
        } else {
            $memberCount = MgmpMember::count();
            $totalReports = MgmpReport::count();
            $recentReports = MgmpReport::orderBy('created_at', 'desc')->limit(5)->get();
            $proposalCount = AcademicaProposal::count();
        }

        return view('mgmp.dashboard', compact('mgmpGroup', 'memberCount', 'totalReports', 'recentReports', 'proposalCount'));
    }

    /**
     * Show MGMP Data Anggota
     */
    public function dataAnggota()
    {
        // Get current user's MGMP group
        $currentMgmpGroup = MgmpGroup::where('user_id', auth()->id())->first();

        $existingMemberIds = [];
        $tenagaPendidik = collect();
        $canAddMember = false;

        // If user is mgmp, only show members of their group (or none if they don't have group)
        if (auth()->user()->role === 'mgmp') {
            if ($currentMgmpGroup) {
                $members = MgmpMember::with('user', 'mgmpGroup')
                    ->where('mgmp_group_id', $currentMgmpGroup->id)
                    ->get();

                // prepare selection list excluding existing members
                $existingMemberIds = $members->pluck('user_id')->toArray();
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->whereNotIn('id', $existingMemberIds)
                    ->with('madrasah')
                    ->orderBy('name', 'asc')
                    ->get();

                $canAddMember = true;
            } else {
                // no group yet: show empty members and disallow adding (user must create MGMP group first)
                $members = collect();
                $canAddMember = false;
            }
        } else {
            // For admin/super_admin/pengurus, show all members and allow adding
            $members = MgmpMember::with('user', 'mgmpGroup')->get();
            $existingMemberIds = $members->pluck('user_id')->toArray();
            $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                ->whereNotIn('id', $existingMemberIds)
                ->with('madrasah')
                ->orderBy('name', 'asc')
                ->get();
            $canAddMember = true;
        }

        return view('mgmp.data-anggota', compact('members', 'tenagaPendidik', 'existingMemberIds', 'canAddMember'));
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
     * Remove an MGMP member from their group without deleting the user account.
     */
    public function destroyMember(MgmpMember $member)
    {
        $user = auth()->user();
        $allowedRoles = ['super_admin', 'admin', 'pengurus'];
        $memberName = $member->name;

        if (!in_array($user->role, $allowedRoles)) {
            $currentMgmpGroup = MgmpGroup::where('user_id', $user->id)->first();

            if (
                $user->role !== 'mgmp'
                || !$currentMgmpGroup
                || (int) $member->mgmp_group_id !== (int) $currentMgmpGroup->id
            ) {
                return redirect()
                    ->route('mgmp.data-anggota')
                    ->with('error', 'Anda tidak memiliki akses untuk mengeluarkan anggota ini.');
            }
        }

        $member->delete();

        return redirect()
            ->route('mgmp.data-anggota')
            ->with('success', $memberName . ' berhasil dikeluarkan dari anggota MGMP.');
    }

    /**
     * Show Data MGMP (management UI)
     */
    public function manage()
    {
        $user = auth()->user();

        // If current user is an MGMP user, only show their own MGMP data and allow adding only if they don't have one
        if ($user && $user->role === 'mgmp') {
            $mgmpGroups = MgmpGroup::withCount('members')
                ->where('user_id', $user->id)
                ->get();
            $canAdd = !MgmpGroup::where('user_id', $user->id)->exists();
        } else {
            // For admin/super_admin/pengurus show all and allow add
            $mgmpGroups = MgmpGroup::withCount('members')->get();
            $canAdd = true;
        }

        return view('mgmp.data-mgmp', compact('mgmpGroups', 'canAdd'));
    }

    /**
     * Show MGMP Laporan Kegiatan
     */
    public function laporan()
    {
        $user = Auth::user();
        $mgmpGroup = $this->currentMgmpGroupForUser($user);

        $query = MgmpReport::with(['mgmpGroup', 'attendances.user'])
            ->withCount('attendances')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc');

        if ($user->role === 'mgmp') {
            if (!$mgmpGroup) {
                $laporan = collect();
            } else {
                $laporan = $query->where('mgmp_group_id', $mgmpGroup->id)->get();
            }
        } else {
            $laporan = $query->get();
        }

        $totalLaporan = $laporan->count();
        $laporanBulanIni = $laporan->filter(function ($report) {
            return $report->tanggal && Carbon::parse($report->tanggal)->isSameMonth(Carbon::now('Asia/Jakarta'));
        })->count();
        $totalPeserta = $laporan->sum('attendances_count');
        $rataRataDurasi = $this->calculateAverageReportDuration($laporan);

        $mgmpGroups = in_array($user->role, ['super_admin', 'admin', 'pengurus'])
            ? MgmpGroup::orderBy('name')->get()
            : collect();

        return view('mgmp.laporan', compact(
            'user',
            'mgmpGroup',
            'mgmpGroups',
            'laporan',
            'totalLaporan',
            'laporanBulanIni',
            'totalPeserta',
            'rataRataDurasi'
        ));
    }

    public function storeLaporan(Request $request)
    {
        $user = Auth::user();
        $mgmpGroup = $this->currentMgmpGroupForUser($user);

        $request->validate([
            'mgmp_group_id' => 'nullable|integer|exists:mgmp_groups,id',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'nullable|integer|min:10|max:1000',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->waktu_selesai <= $request->waktu_mulai) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Waktu selesai harus lebih besar dari waktu mulai.');
        }

        $groupId = $mgmpGroup?->id;
        if (in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
            $groupId = $request->input('mgmp_group_id');
        }

        if (!$groupId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Grup MGMP tidak ditemukan. Pilih atau buat data MGMP terlebih dahulu.');
        }

        MgmpReport::create([
            'mgmp_group_id' => $groupId,
            'created_by' => $user->id,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meters' => $request->input('radius_meters', 100),
            'status' => 'scheduled',
            'jumlah_peserta' => 0,
        ]);

        return redirect()->route('mgmp.laporan')->with('success', 'Kegiatan MGMP berhasil dibuat.');
    }

    public function updateLaporan(Request $request, MgmpReport $report)
    {
        if (!$this->canManageMgmpReport(Auth::user(), $report)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        if ($report->status === 'cancelled') {
            return redirect()->back()->with('error', 'Kegiatan yang sudah dibatalkan tidak dapat diedit.');
        }

        $request->validate([
            'mgmp_group_id' => 'nullable|integer|exists:mgmp_groups,id',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'nullable|integer|min:10|max:1000',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->waktu_selesai <= $request->waktu_mulai) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Waktu selesai harus lebih besar dari waktu mulai.');
        }

        $groupId = $report->mgmp_group_id;
        if (in_array(Auth::user()->role, ['super_admin', 'admin', 'pengurus'])) {
            $groupId = $request->input('mgmp_group_id') ?: $groupId;
        }

        $report->update([
            'mgmp_group_id' => $groupId,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meters' => $request->input('radius_meters', 100),
        ]);

        return redirect()->route('mgmp.laporan')->with('success', 'Kegiatan MGMP berhasil diperbarui.');
    }

    public function cancelLaporan(MgmpReport $report)
    {
        if (!$this->canManageMgmpReport(Auth::user(), $report)) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan kegiatan ini.');
        }

        if ($report->status === 'cancelled') {
            return redirect()->back()->with('error', 'Kegiatan ini sudah dibatalkan.');
        }

        $report->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('mgmp.laporan')->with('success', 'Kegiatan MGMP berhasil dibatalkan.');
    }

    public function presensiKegiatan(MgmpReport $report)
    {
        $report->load(['mgmpGroup', 'attendances']);
        $user = Auth::user();
        $canAttend = $this->canAttendMgmpReport($user, $report);
        $existingAttendance = MgmpAttendance::where('mgmp_report_id', $report->id)
            ->where('user_id', $user->id)
            ->first();
        $schedule = $this->reportSchedule($report);
        $isOngoing = $this->isReportOngoing($report);

        return view('mgmp.presensi', compact(
            'report',
            'user',
            'canAttend',
            'existingAttendance',
            'schedule',
            'isOngoing'
        ));
    }

    public function storePresensiKegiatan(Request $request, MgmpReport $report)
    {
        $user = Auth::user();
        $report->load('mgmpGroup');

        if (!$this->canAttendMgmpReport($user, $report)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terdaftar sebagai anggota pada grup MGMP kegiatan ini.'
            ], 403);
        }

        if (!$this->isReportOngoing($report)) {
            return response()->json([
                'success' => false,
                'message' => $report->status === 'cancelled'
                    ? 'Presensi tidak dapat dilakukan karena kegiatan ini sudah dibatalkan.'
                    : 'Presensi hanya dapat dilakukan saat kegiatan sedang berlangsung.'
            ], 422);
        }

        if (!$report->latitude || !$report->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kegiatan belum lengkap. Hubungi pengelola MGMP.'
            ], 422);
        }

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'lokasi' => 'nullable|string|max:255',
            'accuracy' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'location_readings' => 'nullable|string',
            'selfie_data' => 'required|string|min:100',
        ]);

        if (!$this->isValidBase64Image($request->selfie_data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data foto selfie tidak valid. Silakan ambil foto ulang.'
            ], 422);
        }

        $alreadyAttended = MgmpAttendance::where('mgmp_report_id', $report->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi untuk kegiatan ini sudah tercatat.'
            ], 422);
        }

        $locationValidation = $this->validateMgmpLocationForFakeGPS($request);
        if ($locationValidation['is_fake']) {
            return response()->json([
                'success' => false,
                'message' => $locationValidation['message'],
                'analysis' => $locationValidation['analysis'],
            ], 422);
        }

        $distanceMeters = (int) round($this->calculateDistanceInMeters(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $report->latitude,
            (float) $report->longitude
        ));
        $allowedRadius = (int) ($report->radius_meters ?: 100);

        if ($distanceMeters > $allowedRadius) {
            return response()->json([
                'success' => false,
                'message' => "Lokasi Anda berjarak {$distanceMeters} meter dari titik kegiatan. Batas radius presensi adalah {$allowedRadius} meter.",
                'distance_meters' => $distanceMeters,
                'radius_meters' => $allowedRadius,
            ], 422);
        }

        $selfiePath = $this->saveMgmpSelfie($request->selfie_data, $user->id, $report->id);
        $locationReadings = null;
        if ($request->filled('location_readings')) {
            $decodedReadings = json_decode($request->location_readings, true);
            $locationReadings = json_last_error() === JSON_ERROR_NONE ? $decodedReadings : null;
        }

        $attendance = MgmpAttendance::create([
            'mgmp_report_id' => $report->id,
            'mgmp_group_id' => $report->mgmp_group_id,
            'user_id' => $user->id,
            'attended_at' => Carbon::now('Asia/Jakarta'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'distance_meters' => $distanceMeters,
            'selfie_path' => $selfiePath,
            'lokasi' => $request->lokasi,
            'accuracy' => $request->accuracy,
            'device_info' => $request->device_info,
            'location_readings' => $locationReadings,
        ]);

        $report->update([
            'jumlah_peserta' => $report->attendances()->count(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi kegiatan MGMP berhasil dicatat.',
            'attended_at' => $attendance->attended_at->format('d M Y H:i'),
            'distance_meters' => $distanceMeters,
        ]);
    }

    /**
     * Show Academica page with proposals and upload form
     */
    public function academica()
    {
        $user = auth()->user();

        // Determine which proposals to show depending on role
        if ($user && $user->role === 'mgmp') {
            // If mgmp user has a group, show proposals only from group members
            $mgmpGroup = MgmpGroup::where('user_id', $user->id)->first();
            if ($mgmpGroup) {
                $groupMemberUserIds = $mgmpGroup->members()->pluck('user_id')->toArray();
                $proposals = AcademicaProposal::with('user')
                    ->whereIn('user_id', $groupMemberUserIds)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // no group: show only own proposal (if any)
                $proposals = AcademicaProposal::with('user')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } elseif ($user && in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
            // privileged roles see all proposals
            $proposals = AcademicaProposal::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            // default: show only own proposals
            $proposals = AcademicaProposal::with('user')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $userHasUploaded = AcademicaProposal::where('user_id', $user->id)->exists();
        $userProposal = AcademicaProposal::where('user_id', $user->id)->first();

        return view('mgmp.academica', compact('proposals', 'userHasUploaded', 'userProposal'));
    }

    /**
     * Super Admin consolidated MGMP dashboard
     */
    public function superAdminDashboard()
    {
        // Only accessible to super_admin via route middleware
        $mgmpGroups = MgmpGroup::with('members')->get();
        $members = MgmpMember::with('user', 'mgmpGroup')->get();
        $proposals = AcademicaProposal::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.super_mgmp_dashboard', compact('mgmpGroups', 'members', 'proposals'));
    }

    /**
     * Show form to create a new user with role 'mgmp'
     */
    public function createMgmpUser()
    {
        // No madrasah selection required for MGMP accounts
        // Load existing MGMP users so the create page can display the accounts table
        $mgmpUsers = User::where('role', 'mgmp')->orderBy('created_at', 'desc')->get();
        return view('admin.create_mgmp_user', compact('mgmpUsers'));
    }

    /**
     * Store a new user with role mgmp
     */
    public function storeMgmpUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mgmp',
            'password_changed' => true,
        ]);

        // Redirect back to the create page so the accounts table (loaded there) shows the new user
        return redirect()->route('admin.create_mgmp_user')->with('success', 'Akun MGMP berhasil dibuat.');
    }

    /**
     * Handle PDF proposal upload or replacement.
     */
    public function uploadAcademica(Request $request)
    {
        $request->validate([
            'proposal' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $user = auth()->user();
        $existingProposal = AcademicaProposal::where('user_id', $user->id)->first();

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
        $oldFilePath = $existingProposal ? $documentRoot . '/uploads/' . ltrim($existingProposal->path, '/') : null;

        if ($existingProposal) {
            $existingProposal->update([
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getClientMimeType(),
            ]);

            if ($oldFilePath && file_exists($oldFilePath) && $oldFilePath !== $destinationPath . '/' . $filename) {
                @unlink($oldFilePath);
            }

            return redirect()->back()->with('success', 'Proposal berhasil diperbarui.');
        }

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

    private function currentMgmpGroupForUser(?User $user): ?MgmpGroup
    {
        if (!$user) {
            return null;
        }

        return MgmpGroup::where('user_id', $user->id)->first();
    }

    private function canAttendMgmpReport(User $user, MgmpReport $report): bool
    {
        if (!$report->mgmp_group_id) {
            return false;
        }

        if ($report->mgmpGroup && (int) $report->mgmpGroup->user_id === (int) $user->id) {
            return true;
        }

        return MgmpMember::where('mgmp_group_id', $report->mgmp_group_id)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function canManageMgmpReport(User $user, MgmpReport $report): bool
    {
        if (in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
            return true;
        }

        return $user->role === 'mgmp'
            && $report->mgmpGroup
            && (int) $report->mgmpGroup->user_id === (int) $user->id;
    }

    private function reportSchedule(MgmpReport $report): array
    {
        $timezone = 'Asia/Jakarta';
        $date = $report->tanggal ? Carbon::parse($report->tanggal)->format('Y-m-d') : null;

        if (!$date || !$report->waktu_mulai || !$report->waktu_selesai) {
            return ['start' => null, 'end' => null];
        }

        return [
            'start' => Carbon::parse($date . ' ' . $report->waktu_mulai, $timezone),
            'end' => Carbon::parse($date . ' ' . $report->waktu_selesai, $timezone),
        ];
    }

    private function isReportOngoing(MgmpReport $report): bool
    {
        if ($report->status === 'cancelled') {
            return false;
        }

        $schedule = $this->reportSchedule($report);

        if (!$schedule['start'] || !$schedule['end']) {
            return false;
        }

        return Carbon::now('Asia/Jakarta')->betweenIncluded($schedule['start'], $schedule['end']);
    }

    private function calculateAverageReportDuration($reports): string
    {
        $minutes = $reports->map(function ($report) {
            $schedule = $this->reportSchedule($report);

            if (!$schedule['start'] || !$schedule['end'] || $schedule['end']->lessThanOrEqualTo($schedule['start'])) {
                return null;
            }

            return $schedule['start']->diffInMinutes($schedule['end']);
        })->filter();

        if ($minutes->isEmpty()) {
            return '0';
        }

        return number_format($minutes->avg() / 60, 1);
    }

    private function calculateDistanceInMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusMeters = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusMeters * $c;
    }

    private function validateMgmpLocationForFakeGPS(Request $request): array
    {
        $analysis = [
            'accuracy_check' => false,
            'consistency_check' => false,
            'device_info_check' => false,
            'coordinate_check' => false,
            'suspicious_indicators' => [],
        ];
        $messages = [];

        if ($request->accuracy && (float) $request->accuracy < 3) {
            $analysis['accuracy_check'] = true;
            $analysis['suspicious_indicators'][] = 'accuracy_too_perfect';
            $messages[] = 'Akurasi GPS terlalu sempurna (Terindikasi Lokasi Palsu)';
        }

        if ($request->filled('location_readings') && !$this->isMgmpLocationReadingConsistent($request->location_readings)) {
            $analysis['consistency_check'] = true;
            $analysis['suspicious_indicators'][] = 'location_consistency';
            $messages[] = 'Peringatan, presensi anda terindikasi sebagai lokasi tidak sesuai. Silahkan geser atau pindah dari posisi sebelumnya.';
        }

        if ($request->device_info) {
            $deviceInfo = strtolower($request->device_info);
            foreach (['fake', 'mock', 'gps', 'spoof'] as $keyword) {
                if (strpos($deviceInfo, $keyword) !== false) {
                    $analysis['device_info_check'] = true;
                    $analysis['suspicious_indicators'][] = 'device_info_suspicious';
                    $messages[] = 'Informasi device menunjukkan indikasi penggunaan aplikasi lokasi palsu';
                    break;
                }
            }
        }

        if ($request->latitude && $request->longitude) {
            $latStr = (string) $request->latitude;
            $lngStr = (string) $request->longitude;
            $latParts = explode('.', $latStr);
            $lngParts = explode('.', $lngStr);
            $latDecimals = isset($latParts[1]) ? strlen($latParts[1]) : 0;
            $lngDecimals = isset($lngParts[1]) ? strlen($lngParts[1]) : 0;

            if ($latDecimals > 15 || $lngDecimals > 15) {
                $analysis['coordinate_check'] = true;
                $analysis['suspicious_indicators'][] = 'precision_too_high';
                $messages[] = 'Presisi koordinat GPS tidak wajar';
            }

            if (fmod((float) $request->latitude, 1) == 0.0 || fmod((float) $request->longitude, 1) == 0.0) {
                $analysis['coordinate_check'] = true;
                $analysis['suspicious_indicators'][] = 'round_coordinates';
                $messages[] = 'Koordinat GPS terlalu bulat (kemungkinan fake)';
            }
        }

        return [
            'is_fake' => !empty($messages),
            'message' => !empty($messages) ? implode('. ', array_unique($messages)) : '',
            'analysis' => $analysis,
        ];
    }

    private function isMgmpLocationReadingConsistent(string $locationReadings): bool
    {
        $readings = json_decode($locationReadings, true);

        if (!is_array($readings) || count($readings) < 4) {
            return true;
        }

        $firstFourReadings = array_slice($readings, 0, 4);
        $referenceLat = $firstFourReadings[0]['latitude'] ?? null;
        $referenceLng = $firstFourReadings[0]['longitude'] ?? null;

        if ($referenceLat === null || $referenceLng === null) {
            return true;
        }

        $consistentCount = 0;
        $tolerance = 0.0001;

        foreach ($firstFourReadings as $reading) {
            if (!isset($reading['latitude'], $reading['longitude'])) {
                continue;
            }

            $latDiff = abs((float) $reading['latitude'] - (float) $referenceLat);
            $lngDiff = abs((float) $reading['longitude'] - (float) $referenceLng);

            if ($latDiff <= $tolerance && $lngDiff <= $tolerance) {
                $consistentCount++;
            }
        }

        return $consistentCount < 4;
    }

    private function isValidBase64Image(string $data): bool
    {
        if (strlen($data) < 100 || !preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $data)) {
            return false;
        }

        $base64Data = preg_replace('/^data:image\/(jpeg|jpg|png);base64,/', '', $data);
        $decoded = base64_decode($base64Data, true);

        return $decoded !== false && getimagesizefromstring($decoded) !== false;
    }

    private function saveMgmpSelfie(string $selfieData, int $userId, int $reportId): string
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $selfieData));
        $imageInfo = getimagesizefromstring($imageData);

        if (!$imageInfo) {
            throw new \RuntimeException('Foto selfie tidak dapat diproses.');
        }

        $extension = image_type_to_extension($imageInfo[2], false) ?: 'jpg';
        $directory = storage_path('app/public/mgmp-attendance-selfies');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'mgmp_selfie_' . $reportId . '_' . $userId . '_' . time() . '.' . $extension;
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        if (file_put_contents($path, $imageData) === false) {
            throw new \RuntimeException('Gagal menyimpan foto selfie.');
        }

        return 'mgmp-attendance-selfies/' . $filename;
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
