<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSekolahController extends Controller
{
    /**
     * Dashboard admin sekolah
     * Menampilkan statistik pendaftar
     */
    public function index()
    {
        $user = Auth::user();
        $sekolah = $user->sekolah;

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Sekolah tidak ditemukan untuk akun Anda');
        }

        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolah->id)
            ->where('tahun', now()->year)
            ->first();

        if (!$ppdbSetting) {
            return redirect()->back()->with('error', 'PPDB tidak ditemukan untuk sekolah Anda');
        }

        $statistik = [
            'total_pendaftar' => $ppdbSetting->pendaftars()->count(),
            'pending' => $ppdbSetting->pendaftars()->where('status', 'pending')->count(),
            'verifikasi' => $ppdbSetting->pendaftars()->where('status', 'verifikasi')->count(),
            'lulus' => $ppdbSetting->pendaftars()->where('status', 'lulus')->count(),
            'tidak_lulus' => $ppdbSetting->pendaftars()->where('status', 'tidak_lulus')->count(),
        ];

        return view('ppdb.dashboard.sekolah', compact('ppdbSetting', 'statistik'));
    }

    /**
     * Halaman verifikasi data pendaftar
     */
    public function verifikasi()
    {
        $user = Auth::user();
        $sekolah = $user->sekolah;

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Sekolah tidak ditemukan');
        }

        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolah->id)
            ->where('tahun', now()->year)
            ->first();

        if (!$ppdbSetting) {
            return redirect()->back()->with('error', 'PPDB tidak ditemukan');
        }

        $pendaftars = $ppdbSetting->pendaftars()
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ppdb.dashboard.verifikasi', compact('pendaftars', 'ppdbSetting'));
    }

    /**
     * Update status verifikasi pendaftar
     */
    public function updateVerifikasi(Request $request, $id)
    {
        $pendaftar = PPDBPendaftar::findOrFail($id);
        $user = Auth::user();

        // Cek apakah user memiliki akses ke sekolah ini
        if ($pendaftar->ppdbSetting->sekolah_id !== $user->sekolah_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini');
        }

        $validated = $request->validate([
            'status' => 'required|in:verifikasi,tidak_lulus',
            'catatan' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Status verifikasi harus dipilih',
            'status.in' => 'Status verifikasi tidak valid',
        ]);

        try {
            $pendaftar->update([
                'status' => $validated['status'],
                'catatan_verifikasi' => $validated['catatan'] ?? null,
                'diverifikasi_oleh' => $user->id,
                'diverifikasi_tanggal' => now(),
            ]);

            return redirect()->back()->with('success', 'Status verifikasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Halaman seleksi dan hasil akhir
     */
    public function seleksi()
    {
        $user = Auth::user();
        $sekolah = $user->sekolah;

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Sekolah tidak ditemukan');
        }

        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolah->id)
            ->where('tahun', now()->year)
            ->first();

        if (!$ppdbSetting) {
            return redirect()->back()->with('error', 'PPDB tidak ditemukan');
        }

        $pendaftars = $ppdbSetting->pendaftars()
            ->where('status', 'verifikasi')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('ppdb.dashboard.seleksi', compact('pendaftars', 'ppdbSetting'));
    }

    /**
     * Update hasil seleksi
     */
    public function updateSeleksi(Request $request, $id)
    {
        $pendaftar = PPDBPendaftar::findOrFail($id);
        $user = Auth::user();

        if ($pendaftar->ppdbSetting->sekolah_id !== $user->sekolah_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini');
        }

        $validated = $request->validate([
            'skor_nilai' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:lulus,tidak_lulus',
            'ranking' => 'nullable|numeric|min:1',
        ], [
            'skor_nilai.numeric' => 'Skor nilai harus berupa angka',
            'skor_nilai.min' => 'Skor nilai minimal 0',
            'skor_nilai.max' => 'Skor nilai maksimal 100',
            'status.required' => 'Status seleksi harus dipilih',
        ]);

        try {
            $pendaftar->update([
                'nilai' => $validated['nilai'],
                'ranking' => $validated['ranking'] ?? null,
                'status' => $validated['status'],
                'diseleksi_oleh' => $user->id,
                'diseleksi_tanggal' => now(),
            ]);

            return redirect()->back()->with('success', 'Hasil seleksi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui hasil seleksi: ' . $e->getMessage());
        }
    }

    /**
     * Halaman export data pendaftar
     */
    public function export()
    {
        $user = Auth::user();
        $sekolah = $user->sekolah;

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Sekolah tidak ditemukan');
        }

        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolah->id)
            ->where('tahun', now()->year)
            ->first();

        if (!$ppdbSetting) {
            return redirect()->back()->with('error', 'PPDB tidak ditemukan');
        }

        $pendaftars = $ppdbSetting->pendaftars()->get();

        return view('ppdb.dashboard.export', compact('pendaftars', 'ppdbSetting'));
    }
}
