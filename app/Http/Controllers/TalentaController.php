<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TalentaPeserta;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaMateri;
use App\Models\TalentaLayananTeknis;
use App\Models\TugasTalentaLevel1;

class TalentaController extends Controller
{
    public function login()
    {
        // If already authenticated, redirect to talenta index
        if (Auth::check()) {
            return redirect()->route('talenta.dashboard');
        }

        return view('talenta.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('talenta.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('talenta.dashboard');
    }

    public function data()
    {
        // Fetch real data from database with eager loading
        $pesertaTalenta = TalentaPeserta::with(['user.madrasah'])
            ->latest()
            ->get();

        // Fetch pemateri talenta - eager load materi relationship
        $pemateriTalenta = TalentaPemateri::with('materis')->latest()->get();

        // Fetch fasilitator talenta - no user relationship, uses direct fields
        $fasilitatorTalenta = TalentaFasilitator::latest()->get();

        // Fetch materi talenta
        $materiTalenta = TalentaMateri::latest()->get();

        return view('talenta.data', compact('pesertaTalenta', 'pemateriTalenta', 'fasilitatorTalenta', 'materiTalenta'));
    }

    public function instrumenPenilaian()
    {
        // Fetch peserta talenta for dropdown selection
        $pesertaTalenta = TalentaPeserta::with(['user.madrasah'])
            ->latest()
            ->get();

        // Fetch fasilitator talenta
        $fasilitatorTalenta = TalentaFasilitator::with('materis')
            ->latest()
            ->get();

        // Fetch pemateri talenta
        $pemateriTalenta = TalentaPemateri::with('materis')
            ->latest()
            ->get();

        // Fetch layanan teknis talenta
        $layananTeknisTalenta = TalentaLayananTeknis::latest()->get();

        return view('talenta.instrumen-penilaian', compact('pesertaTalenta', 'fasilitatorTalenta', 'pemateriTalenta', 'layananTeknisTalenta'));
    }

    public function tugasLevel1()
    {
        // Fetch materi level I untuk validasi tanggal
        $materiLevel1 = TalentaMateri::where('level_materi', 'I')
            ->whereIn('judul_materi', ['IDEOLOGI DAN ORGANISASI', 'TATA KELOLA', 'LAYANAN', 'KEPEMIMPINAN'])
            ->get()
            ->keyBy('judul_materi');

        return view('talenta.tugas-level-1', compact('materiLevel1'));
    }

    public function simpanTugasLevel1(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'area' => 'required|string',
            'jenis_tugas' => 'required|string',
        ]);

        // Conditional validation based on jenis_tugas
        if ($validated['jenis_tugas'] === 'on_site' && $validated['area'] === 'kepemimpinan') {
            // For kepemimpinan on_site, require text fields
            $request->validate([
                'konteks' => 'required|string',
                'peran' => 'required|string',
                'nilai_kepemimpinan' => 'required|string',
                'masalah_kepemimpinan' => 'required|string',
                'pelajaran_penting' => 'required|string',
            ]);
        } else {
            // For all other cases, require file upload
            $request->validate([
                'lampiran' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Required file, max 10MB
            ]);
        }

        // Mapping area ke judul materi
        $areaMapping = [
            'ideologi_organisasi' => 'IDEOLOGI DAN ORGANISASI',
            'tata_kelola' => 'TATA KELOLA',
            'layanan_pendidikan' => 'LAYANAN',
            'kepemimpinan' => 'KEPEMIMPINAN',
        ];

        $areaTitle = $areaMapping[$validated['area']] ?? null;

        if (!$areaTitle) {
            return response()->json([
                'success' => false,
                'message' => 'Area tugas tidak valid!'
            ], 422);
        }

        // Validasi tanggal materi
        $materi = TalentaMateri::where('level_materi', 'I')
            ->where('judul_materi', 'like', '%' . $areaTitle . '%')
            ->where('tanggal_materi', '<=', now()->toDateString())
            ->first();

        if (!$materi) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak dapat dikirim karena materi untuk area ini belum terlaksana. Tanggal materi: ' . ($materi ? $materi->tanggal_materi->format('d-m-Y') : 'Belum ditentukan')
            ], 422);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/talenta', $fileName, 'public');
        }

        // Simpan data tugas
        TugasTalentaLevel1::create([
            'user_id' => Auth::id(),
            'area' => $validated['area'],
            'jenis_tugas' => $validated['jenis_tugas'],
            'data' => json_encode($request->except(['_token', 'area', 'jenis_tugas', 'lampiran'])),
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas Level 1 berhasil disimpan!'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('talenta.login');
    }
}
