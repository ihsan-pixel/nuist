<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function instrumenPenilaian()
    {
        return view('talenta.instrumen-penilaian', [
            'pesertaTalenta'        => TalentaPeserta::with(['user.madrasah'])->latest()->get(),
            'fasilitatorTalenta'    => TalentaFasilitator::with('materis')->latest()->get(),
            'pemateriTalenta'       => TalentaPemateri::with('materis')->latest()->get(),
            'layananTeknisTalenta'  => TalentaLayananTeknis::latest()->get(),
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

        // Calculate progress
        $totalTasks = $materiLevel1->count() * 3; // 3 jenis tugas per area
        $completedTasks = TugasTalentaLevel1::where('user_id', Auth::id())->count();
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // Get existing tasks for the user
        $existingTasks = TugasTalentaLevel1::where('user_id', Auth::id())
            ->get()
            ->keyBy(function($item) {
                return $item->area . '-' . $item->jenis_tugas;
            });

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

            /* ---------- SIMPAN DATABASE ---------- */
            $tugas = TugasTalentaLevel1::create([
                'user_id'      => Auth::id(),
                'area'         => $validated['area'],
                'jenis_tugas'  => $validated['jenis_tugas'],
                'data'         => collect($validated)->except(['area', 'jenis_tugas'])->toArray(),
                'file_path'    => $filePath,
                'submitted_at' => now(),
            ]);

            Log::info('TugasTalentaLevel1 created successfully', [
                'id' => $tugas->id,
                'user_id' => $tugas->user_id,
                'area' => $tugas->area,
                'jenis_tugas' => $tugas->jenis_tugas,
                'file_path' => $tugas->file_path,
            ]);

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

    /* =========================
     * PENILAIAN TUGAS
     * ========================= */
    public function penilaianTugas()
    {
        // Get the logged-in pemateri by user_id
        $pemateri = TalentaPemateri::where('user_id', Auth::id())->first();

        if (!$pemateri) {
            return redirect()->route('talenta.dashboard')->with('error', 'Anda tidak memiliki akses sebagai pemateri.');
        }

        // Get material slugs for this pemateri using the relationship
        $materiSlugs = $pemateri->materis()->pluck('slug');

        // Get tasks related to the pemateri's materials
        $tugas = TugasTalentaLevel1::with(['user.madrasah', 'nilai'])
            ->whereIn('area', $materiSlugs)
            ->latest()
            ->get();

        return view('talenta.penilaian-tugas', compact('tugas'));
    }

    /* =========================
     * SIMPAN NILAI TUGAS
     * ========================= */
    public function simpanNilaiTugas(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|integer|exists:tugas_talenta_level1s,id',
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

            // Save or update nilai for this penilai
            TugasNilai::updateOrCreate(
                [
                    'tugas_talenta_level1_id' => $request->tugas_id,
                    'penilai_id' => Auth::id(),
                ],
                [
                    'nilai' => $request->nilai,
                ]
            );

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
}
