<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\TalentaPeserta;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaMateri;
use App\Models\TalentaLayananTeknis;
use App\Models\TalentaKelompok;
use App\Models\TugasTalentaLevel1;
use App\Models\TugasNilai;
use App\Models\User;
use App\Models\TalentaPenilaianTrainer;
use App\Models\TalentaPenilaianFasilitator;
use App\Models\TalentaPenilaianTeknis;
use App\Models\TalentaPenilaianPeserta;

class TalentaController extends Controller
{
    /* =========================
     * AUTH
     * ========================= */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('talenta.dashboard');
        }

        return view('talenta.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('talenta.dashboard');
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

        return redirect()->route('talenta.login');
    }

    /* =========================
     * DASHBOARD & DATA
     * ========================= */
    public function dashboard()
    {
        return view('talenta.dashboard');
    }

    public function data()
    {
        return view('talenta.data', [
            'pesertaTalenta'     => TalentaPeserta::with(['user.madrasah'])->first()->get(),
            'pemateriTalenta'    => TalentaPemateri::with('materis')->orderByRaw("FIELD(id, 27, 28, 25, 33, 26, 32, 34, 30, 29, 31)")->get(),
            'fasilitatorTalenta' => TalentaFasilitator::orderByRaw("FIELD(id, 36, 31, 27, 28, 34, 32, 35, 29, 30, 33, 37)")->get(),
            'materiTalenta'      => TalentaMateri::first()->get(),
            'kelompokTalenta'    => \App\Models\TalentaKelompok::with('users')->first()->get(),
        ]);
    }

    public function instrumenPenilaian(Request $request)
    {
        // Get materi list for selection
        $materiTalenta = TalentaMateri::where('status', TalentaMateri::STATUS_PUBLISHED)
            ->orderBy('level_materi')
            ->orderBy('judul_materi')
            ->get();

        // Get selected materi from request
        $selectedMateriId = $request->input('materi_id');

        // Always load the full peserta list. The UI will fetch per-materi penilaian
        // via AJAX and populate the rating inputs; this allows showing empty rows
        // when no penilaian exists yet for the selected materi.
        $pesertaList = TalentaPeserta::with(['user.madrasah'])->latest()->get();

        return view('talenta.instrumen-penilaian', [
            'pesertaTalenta'        => $pesertaList,
            'fasilitatorTalenta'    => TalentaFasilitator::orderByRaw("FIELD(id, 36, 31, 27, 28, 34, 32, 35, 29, 30, 33, 37)")->get(),
            'pemateriTalenta'       => TalentaPemateri::with('materis')->orderByRaw("FIELD(id, 27, 28, 25, 33, 26, 32, 34, 30, 29, 31)")->get(),
            'layananTeknisTalenta'  => TalentaLayananTeknis::latest()->get(),
            'materiTalenta'         => $materiTalenta,
            'selectedMateriId'      => $selectedMateriId,
        ]);
    }

    /* =========================
     * TUGAS LEVEL 1
     * ========================= */
    public function tugasLevel1()
    {
        $materiLevel1 = TalentaMateri::where('level_materi', TalentaMateri::LEVEL_1)
            ->where('status', TalentaMateri::STATUS_PUBLISHED)
            ->get();

        $iconMapping = [
            'IDEOLOGI'      => 'bx-heart',
            'TATA KELOLA'   => 'bx-cog',
            'LAYANAN'       => 'bx-book',
            'KEPEMIMPINAN'  => 'bx-crown',
            'ORGANISASI'    => 'bx-group',
            'PENDIDIKAN'    => 'bx-graduation',
        ];

        $areaConfig = [];

        foreach ($materiLevel1 as $materi) {
            $icon = 'bx-book';

            foreach ($iconMapping as $keyword => $bxIcon) {
                if (stripos($materi->judul_materi, $keyword) !== false) {
                    $icon = $bxIcon;
                    break;
                }
            }

            $areaConfig[] = [
                'slug' => $materi->slug,
                'icon' => $icon,
                'name' => $materi->judul_materi,
            ];
        }

        // Find kelompok (if any) early so we can count shared kelompok tasks toward progress
        $myKelompok = null;
        try {
            $myKelompok = TalentaKelompok::whereHas('users', function($q) {
                $q->where('users.id', Auth::id());
            })->first();
        } catch (\Exception $e) {
            // don't break the page if kelompok lookup fails; log and continue
            Log::warning('Failed to lookup kelompok: ' . $e->getMessage());
            $myKelompok = null;
        }

        // Calculate progress
        $totalTasks = $materiLevel1->count() * 3; // 3 jenis tugas per area

        // Personal tasks (exclude 'kelompok' because those are shared)
        $personalCount = TugasTalentaLevel1::where('user_id', Auth::id())
            ->where('jenis_tugas', '!=', 'kelompok')
            ->count();

        // Count kelompok tasks (distinct per area) if user is in a kelompok
        $groupCount = 0;
        if ($myKelompok) {
            $groupCount = TugasTalentaLevel1::where('kelompok_id', $myKelompok->id)
                ->where('jenis_tugas', 'kelompok')
                ->distinct()
                ->count('area');
        }

        $completedTasks = $personalCount + $groupCount;
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // Get existing tasks for the user (personal records)
        $existingTasks = TugasTalentaLevel1::where('user_id', Auth::id())
            ->get()
            ->keyBy(function($item) {
                return $item->area . '-' . $item->jenis_tugas;
            });

        // Merge kelompok-level tugas so members can see shared upload
        try {
            if ($myKelompok) {
                $groupTasks = TugasTalentaLevel1::where('kelompok_id', $myKelompok->id)
                    ->get()
                    ->keyBy(function($item) {
                        return $item->area . '-' . $item->jenis_tugas;
                    });

                foreach ($groupTasks as $key => $task) {
                    // only override kelompok-type tasks (shared)
                    if (strpos($key, '-kelompok') !== false) {
                        $existingTasks[$key] = $task;
                    }
                }
            }
        } catch (\Exception $e) {
            // don't break the page if kelompok merge fails; log and continue
            Log::warning('Failed to merge kelompok tasks: ' . $e->getMessage());
        }

        return view('talenta.tugas-level-1', compact('materiLevel1', 'areaConfig', 'progressPercentage', 'completedTasks', 'totalTasks', 'existingTasks'));
    }

    /* =========================
     * SIMPAN TUGAS LEVEL 1
     * ========================= */
    public function simpanTugasLevel1(Request $request)
    {
        // Check authentication for AJAX requests
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi telah berakhir. Silakan login kembali.',
            ], 401);
        }

        Log::info('simpanTugasLevel1 called', [
            'all_data' => $request->all(),
            'files' => $request->file(),
            'has_lampiran' => $request->hasFile('lampiran'),
            'headers' => $request->headers->all()
        ]);

        try {
            /* ---------- VALIDASI DASAR ---------- */
            $validated = $request->validate([
                'area'        => 'required|string',
                'jenis_tugas' => 'required|in:on_site,terstruktur,kelompok',
            ]);

            Log::info('Basic validation passed', ['validated' => $validated]);

            /* ---------- VALIDASI KHUSUS ---------- */
            if ($validated['jenis_tugas'] === 'on_site') {
                $request->validate([
                    'lampiran' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                ]);
            }

            Log::info('Special validation passed');

            /* ---------- VALIDASI MATERI ---------- */
            $materi = TalentaMateri::where('level_materi', TalentaMateri::LEVEL_1)
                ->where('slug', $validated['area'])
                ->first();

            if (!$materi) {
                Log::warning('Materi not found', ['slug' => $validated['area']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan untuk area: ' . $validated['area'],
                ], 422);
            }

            Log::info('Materi found', ['materi_id' => $materi->id, 'judul' => $materi->judul_materi, 'slug' => $materi->slug]);

            /* ---------- UPLOAD FILE ---------- */
            $filePath = null;

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = Str::uuid()->toString() . '.' . $file->extension();

                try {
                    // Save to uploads/talenta directory using document root
                    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? base_path('public');
                    $uploadDir = $documentRoot . '/uploads/talenta';

                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            throw new \Exception('Failed to create upload directory: ' . $uploadDir);
                        }
                    }

                    if (!is_writable($uploadDir)) {
                        throw new \Exception('Upload directory is not writable: ' . $uploadDir);
                    }

                    // Get file size before moving
                    $fileSize = $file->getSize();

                    $file->move($uploadDir, $fileName);
                    $filePath = 'uploads/talenta/' . $fileName;

                    Log::info('File uploaded successfully', [
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'full_path' => $uploadDir . '/' . $fileName,
                        'size' => $fileSize,
                        'document_root' => $documentRoot
                    ]);
                } catch (\Exception $e) {
                    Log::error('File upload failed', [
                        'error' => $e->getMessage(),
                        'file_name' => $fileName,
                        'upload_dir' => $uploadDir ?? 'not set'
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload file: ' . $e->getMessage(),
                    ], 500);
                }
            }

            /* ---------- SIMPAN DATABASE (safe replace & archive old files) ---------- */
            $kelompokId = null;
            if ($validated['jenis_tugas'] === 'kelompok') {
                // find kelompok for current user
                $kelompok = TalentaKelompok::whereHas('users', function($q) {
                    $q->where('users.id', Auth::id());
                })->first();

                if (!$kelompok) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum tergabung dalam kelompok. Tidak dapat mengupload tugas kelompok.',
                    ], 422);
                }

                $kelompokId = $kelompok->id;

                $existing = TugasTalentaLevel1::where('kelompok_id', $kelompokId)
                    ->where('area', $validated['area'])
                    ->where('jenis_tugas', $validated['jenis_tugas'])
                    ->latest()
                    ->first();
            } else {
                $existing = TugasTalentaLevel1::where('user_id', Auth::id())
                    ->where('area', $validated['area'])
                    ->where('jenis_tugas', $validated['jenis_tugas'])
                    ->latest()
                    ->first();
            }

            if ($existing) {
                // archive old file if present
                if ($existing->file_path) {
                    $oldFull = public_path($existing->file_path);
                    if (file_exists($oldFull)) {
                        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? base_path('public');
                        $uploadDir = $documentRoot . '/uploads/talenta';
                        $archiveDir = $uploadDir . '/archive/' . date('Ymd');
                        if (!file_exists($archiveDir)) {
                            if (!mkdir($archiveDir, 0755, true) && !is_dir($archiveDir)) {
                                Log::warning('Failed to create archive dir', ['dir' => $archiveDir]);
                            }
                        }
                        $oldBase = basename($existing->file_path);
                        $archName = time() . '_' . $oldBase;
                        @rename($oldFull, $archiveDir . '/' . $archName);
                    }
                }

                $existing->data = collect($validated)->except(['area', 'jenis_tugas'])->toArray();
                $existing->file_path = $filePath;
                $existing->submitted_at = now();
                // ensure kelompok_id is set for kelompok-type tugas
                if ($kelompokId) {
                    $existing->kelompok_id = $kelompokId;
                }
                $existing->save();
                $tugas = $existing;
                Log::info('TugasTalentaLevel1 updated with new upload (old file archived)', [
                    'id' => $tugas->id,
                    'user_id' => $tugas->user_id,
                    'area' => $tugas->area,
                    'jenis_tugas' => $tugas->jenis_tugas,
                    'file_path' => $tugas->file_path,
                ]);

            } else {
                $createData = [
                    'user_id'      => Auth::id(),
                    'area'         => $validated['area'],
                    'jenis_tugas'  => $validated['jenis_tugas'],
                    'data'         => collect($validated)->except(['area', 'jenis_tugas'])->toArray(),
                    'file_path'    => $filePath,
                    'submitted_at' => now(),
                ];

                if ($kelompokId) {
                    $createData['kelompok_id'] = $kelompokId;
                }

                $tugas = TugasTalentaLevel1::create($createData);

                Log::info('TugasTalentaLevel1 created successfully', [
                    'id' => $tugas->id,
                    'user_id' => $tugas->user_id,
                    'area' => $tugas->area,
                    'jenis_tugas' => $tugas->jenis_tugas,
                    'file_path' => $tugas->file_path,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tugas Level 1 berhasil disimpan',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', collect($e->errors())->flatten()->toArray()),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error in simpanTugasLevel1', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Reset / delete uploaded file for a given area and jenis_tugas for the current user.
     * Accepts POST from regular form (redirect back) or AJAX (returns JSON).
     */
    public function resetTugasLevel1(Request $request)
    {
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Sesi telah berakhir. Silakan login kembali.'], 401)
                : redirect()->route('talenta.login');
        }

        $validated = $request->validate([
            'area' => 'required|string',
            'jenis_tugas' => 'required|in:on_site,terstruktur,kelompok',
        ]);

        try {
            if ($validated['jenis_tugas'] === 'kelompok') {
                $kelompok = TalentaKelompok::whereHas('users', function($q) {
                    $q->where('users.id', Auth::id());
                })->first();

                if (!$kelompok) {
                    $message = 'Anda belum tergabung dalam kelompok.';
                    return $request->wantsJson()
                        ? response()->json(['success' => false, 'message' => $message], 422)
                        : redirect()->back()->with('error', $message);
                }

                $tasks = TugasTalentaLevel1::where('kelompok_id', $kelompok->id)
                    ->where('area', $validated['area'])
                    ->where('jenis_tugas', $validated['jenis_tugas'])
                    ->get();
            } else {
                $tasks = TugasTalentaLevel1::where('user_id', Auth::id())
                    ->where('area', $validated['area'])
                    ->where('jenis_tugas', $validated['jenis_tugas'])
                    ->get();
            }

            if ($tasks->isEmpty()) {
                $message = 'Tidak ditemukan file yang tersimpan untuk tugas ini.';
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => $message], 404)
                    : redirect()->back()->with('error', $message);
            }

            foreach ($tasks as $task) {
                if ($task->file_path) {
                    $fullPath = public_path($task->file_path);
                    if (file_exists($fullPath)) {
                        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? base_path('public');
                        $uploadDir = $documentRoot . '/uploads/talenta';
                        $archiveDir = $uploadDir . '/archive/' . date('Ymd');
                        if (!file_exists($archiveDir)) {
                            if (!mkdir($archiveDir, 0755, true) && !is_dir($archiveDir)) {
                                Log::warning('Failed to create archive dir', ['dir' => $archiveDir]);
                            }
                        }
                        $base = basename($task->file_path);
                        $archName = time() . '_' . $base;
                        @rename($fullPath, $archiveDir . '/' . $archName);
                    }
                }

                // keep record but clear file reference so user can upload again
                $task->file_path = null;
                $task->submitted_at = null;
                $task->save();
            }

            $message = 'File terupload dipindahkan ke arsip. Anda dapat mengupload file baru.';
            return $request->wantsJson()
                ? response()->json(['success' => true, 'message' => $message])
                : redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error resetting tugas level 1', ['error' => $e->getMessage(), 'request' => $request->all()]);
            $message = 'Terjadi kesalahan saat menghapus file.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 500)
                : redirect()->back()->with('error', $message);
        }
    }

    /* =========================
     * PENILAIAN TUGAS
     * ========================= */
    public function penilaianTugas(Request $request)
    {
        // Try find pemateri or fasilitator record connected to the logged-in user
        $pemateri = TalentaPemateri::where('user_id', Auth::id())->first();
        $fasilitator = TalentaFasilitator::where('user_id', Auth::id())->first();

        if (!$pemateri && !$fasilitator) {
            return redirect()->route('talenta.dashboard')->with('error', 'Anda tidak memiliki akses sebagai pemateri atau fasilitator.');
        }

        // Build list of materi models the user is allowed to assess
        if ($pemateri) {
            $materiModels = $pemateri->materis()->get();
        } else {
            $materiModels = $fasilitator->materis()->get();
        }

        // If a specific materi_id is provided, make sure the current user actually teaches it
        $selectedMateriId = $request->input('materi_id');
        if ($selectedMateriId) {
            $allowedIds = $materiModels->pluck('id')->map(function($v){ return (int) $v; })->toArray();
            if (!in_array((int) $selectedMateriId, $allowedIds, true)) {
                return redirect()->route('talenta.dashboard')->with('error', 'Anda tidak memiliki akses untuk materi yang dipilih.');
            }
            $materiModels = $materiModels->where('id', (int) $selectedMateriId);
        }

        // Map to slugs which correspond to the `area` column on tugas table
        $materiSlugs = $materiModels->pluck('slug')->toArray();

        // Special case: user ID 2472 may access ALL uploaded tugas regardless of materi
        if (Auth::id() === 2472) {
            $tugas = TugasTalentaLevel1::with(['user.madrasah', 'nilai'])
                ->latest()
                ->get();
        } else {
            // Get tasks related to the user's materi(s)
            $tugas = TugasTalentaLevel1::with(['user.madrasah', 'nilai'])
                ->whereIn('area', $materiSlugs)
                ->latest()
                ->get();
        }

        // Pass materi list to the view so UI can present selection if needed
        return view('talenta.penilaian-tugas', [
            'tugas' => $tugas,
            'materis' => $materiModels,
            'selected_materi_id' => $selectedMateriId,
        ]);
    }

    /* =========================
     * SIMPAN NILAI TUGAS
     * ========================= */
    public function simpanNilaiTugas(Request $request)
    {
        // Validate tugas_id against the actual DB table (plural).
        $request->validate([
            'tugas_id' => 'required|integer|exists:tugas_talenta_level1,id',
            'nilai' => 'required|integer|min:0|max:100',
        ]);

        try {
            $tugas = TugasTalentaLevel1::findOrFail($request->tugas_id);

            // Check if the logged-in pemateri has access to this task
            $pemateri = TalentaPemateri::where('user_id', Auth::id())->first();
            if (!$pemateri) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses sebagai pemateri.',
                ], 403);
            }

            $materiSlugs = $pemateri->materis()->pluck('slug');
            if (!$materiSlugs->contains($tugas->area)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menilai tugas ini.',
                ], 403);
            }

            // If this tugas belongs to a kelompok, apply the same nilai to all tugas
            // records for that kelompok (same area & jenis_tugas). Otherwise, update only this tugas.
            $targets = collect([$tugas]);
            if (!empty($tugas->kelompok_id)) {
                $targets = TugasTalentaLevel1::where('kelompok_id', $tugas->kelompok_id)
                    ->where('area', $tugas->area)
                    ->where('jenis_tugas', $tugas->jenis_tugas)
                    ->get();
            }

            foreach ($targets as $target) {
                TugasNilai::updateOrCreate(
                    [
                        'tugas_talenta_level1_id' => $target->id,
                        'penilai_id' => Auth::id(),
                    ],
                    [
                        'nilai' => $request->nilai,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving nilai tugas', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nilai.',
            ], 500);
        }
    }

    /* =========================
     * PENILAIAN INSTRUMEN - SAVE & GET
     * ========================= */

    // Simpan Penilaian Trainer (Pemateri)
    public function simpanPenilaianTrainer(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $ratings = $request->input('ratings', []);

            foreach ($ratings as $pemateriId => $data) {
                TalentaPenilaianTrainer::updateOrCreate(
                    [
                        'talenta_pemateri_id' => $pemateriId,
                        'user_id' => Auth::id(),
                    ],
                    [
                        'kualitas_materi' => $data['kualitas_materi'] ?? null,
                        'penyampaian' => $data['penyampaian'] ?? null,
                        'integrasi_kasus' => $data['integrasi_kasus'] ?? null,
                        'penjelasan' => $data['penjelasan'] ?? null,
                        'umpan_balik' => $data['umpan_balik'] ?? null,
                        'waktu' => $data['waktu'] ?? null,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Penilaian trainer berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving trainer penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan penilaian.'], 500);
        }
    }

    // Get Penilaian Trainer
    public function getPenilaianTrainer()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $penilaian = TalentaPenilaianTrainer::where('user_id', Auth::id())
                ->get()
                ->keyBy('talenta_pemateri_id')
                ->toArray();

            return response()->json(['success' => true, 'data' => $penilaian]);
        } catch (\Exception $e) {
            Log::error('Error getting trainer penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat penilaian.'], 500);
        }
    }

    // Simpan Penilaian Fasilitator
    public function simpanPenilaianFasilitator(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $ratings = $request->input('ratings', []);

            foreach ($ratings as $fasilitatorId => $data) {
                TalentaPenilaianFasilitator::updateOrCreate(
                    [
                        'talenta_fasilitator_id' => $fasilitatorId,
                        'user_id' => Auth::id(),
                    ],
                    [
                        'fasilitasi' => $data['fasilitasi'] ?? null,
                        'pendampingan' => $data['pendampingan'] ?? null,
                        'respons' => $data['respons'] ?? null,
                        'koordinasi' => $data['koordinasi'] ?? null,
                        'monitoring' => $data['monitoring'] ?? null,
                        'waktu' => $data['waktu'] ?? null,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Penilaian fasilitator berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving fasilitator penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan penilaian.'], 500);
        }
    }

    // Get Penilaian Fasilitator
    public function getPenilaianFasilitator()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $penilaian = TalentaPenilaianFasilitator::where('user_id', Auth::id())
                ->get()
                ->keyBy('talenta_fasilitator_id')
                ->toArray();

            return response()->json(['success' => true, 'data' => $penilaian]);
        } catch (\Exception $e) {
            Log::error('Error getting fasilitator penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat penilaian.'], 500);
        }
    }

    // Simpan Penilaian Teknis
    public function simpanPenilaianTeknis(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $ratings = $request->input('ratings', []);

            foreach ($ratings as $teknisId => $data) {
                TalentaPenilaianTeknis::updateOrCreate(
                    [
                        'talenta_layanan_teknis_id' => $teknisId,
                        'user_id' => Auth::id(),
                    ],
                    [
                        'kehadiran' => $data['kehadiran'] ?? null,
                        'partisipasi' => $data['partisipasi'] ?? null,
                        'disiplin' => $data['disiplin'] ?? null,
                        'kualitas_tugas' => $data['kualitas_tugas'] ?? null,
                        'pemahaman_materi' => $data['pemahaman_materi'] ?? null,
                        'implementasi_praktik' => $data['implementasi_praktik'] ?? null,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Penilaian teknis berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving teknis penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan penilaian.'], 500);
        }
    }

    // Get Penilaian Teknis
    public function getPenilaianTeknis()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $penilaian = TalentaPenilaianTeknis::where('user_id', Auth::id())
                ->get()
                ->keyBy('talenta_layanan_teknis_id')
                ->toArray();

            return response()->json(['success' => true, 'data' => $penilaian]);
        } catch (\Exception $e) {
            Log::error('Error getting teknis penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat penilaian.'], 500);
        }
    }

    // Simpan Penilaian Peserta
    public function simpanPenilaianPeserta(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $ratings = $request->input('ratings', []);
            // materi_id is now stored per-penilaian peserta
            $materiId = $request->input('materi_id');

            foreach ($ratings as $pesertaId => $data) {
                $match = [
                    'talenta_peserta_id' => $pesertaId,
                    'user_id' => Auth::id(),
                    'materi_id' => $materiId,
                ];

                $values = [
                    'kehadiran' => $data['kehadiran'] ?? null,
                    'partisipasi' => $data['partisipasi'] ?? null,
                    'disiplin' => $data['disiplin'] ?? null,
                    'tugas' => $data['tugas'] ?? null,
                    'pemahaman' => $data['pemahaman'] ?? null,
                    'praktik' => $data['praktik'] ?? null,
                    'sikap' => $data['sikap'] ?? null,
                    'materi_id' => $materiId,
                ];

                TalentaPenilaianPeserta::updateOrCreate($match, $values);
            }

            return response()->json([
                'success' => true,
                'message' => 'Penilaian peserta berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving peserta penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan penilaian.'], 500);
        }
    }

    // Get Penilaian Peserta
    public function getPenilaianPeserta()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 401);
        }

        try {
            $query = TalentaPenilaianPeserta::where('user_id', Auth::id());

            // optional materi filter
            $materiId = request()->query('materi_id');
            if ($materiId) {
                $query->where('materi_id', $materiId);
            }

            $penilaian = $query->get()->keyBy('talenta_peserta_id')->toArray();

            return response()->json(['success' => true, 'data' => $penilaian]);
        } catch (\Exception $e) {
            Log::error('Error getting peserta penilaian', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat penilaian.'], 500);
        }
    }
}
