<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\TalentaPeserta;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaMateri;
use App\Models\TalentaLayananTeknis;
use App\Models\TugasTalentaLevel1;

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
            'pesertaTalenta'     => TalentaPeserta::with(['user.madrasah'])->latest()->get(),
            'pemateriTalenta'    => TalentaPemateri::with('materis')->latest()->get(),
            'fasilitatorTalenta' => TalentaFasilitator::latest()->get(),
            'materiTalenta'      => TalentaMateri::latest()->get(),
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

        return view('talenta.tugas-level-1', compact('materiLevel1', 'areaConfig'));
    }

    /* =========================
     * SIMPAN TUGAS LEVEL 1
     * ========================= */
    public function simpanTugasLevel1(Request $request)
    {
        \Log::info('simpanTugasLevel1 called', [
            'all_data' => $request->all(),
            'files' => $request->file(),
            'has_lampiran' => $request->hasFile('lampiran')
        ]);

        /* ---------- VALIDASI DASAR ---------- */
        $validated = $request->validate([
            'area'        => 'required|string',
            'jenis_tugas' => 'required|in:on_site,terstruktur,kelompok',
        ]);

        /* ---------- VALIDASI KHUSUS ---------- */
        if ($validated['jenis_tugas'] === 'on_site' && $validated['area'] === 'kepemimpinan') {
            $validated += $request->validate([
                'konteks'              => 'required|string',
                'peran'                => 'required|string',
                'nilai_kepemimpinan'   => 'required|string',
                'masalah_kepemimpinan' => 'required|string',
                'pelajaran_penting'    => 'required|string',
            ]);
        } else {
            $request->validate([
                'lampiran' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            ]);
        }

        /* ---------- VALIDASI MATERI ---------- */
        $materi = TalentaMateri::where('level_materi', TalentaMateri::LEVEL_1)
            ->where('slug', $validated['area'])
            ->first();

        if (!$materi) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
            ], 422);
        }

        /* ---------- UPLOAD FILE ---------- */
        $filePath = null;

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = Str::uuid() . '.' . $file->extension();
            $filePath = $file->storeAs('uploads/talenta', $fileName, 'public');
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

        \Log::info('TugasTalentaLevel1 created successfully', [
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
    }
}
