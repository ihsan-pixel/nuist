<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PendaftarController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function create($slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->where('status', 'buka')
            ->with('sekolah', 'jalurs')
            ->firstOrFail();

        // Cek apakah pendaftaran masih buka
        if (now()->isAfter($ppdbSetting->jadwal_tutup)) {
            return redirect()->route('ppdb.sekolah', $slug)
                ->with('error', 'Pendaftaran telah ditutup');
        }

        if (now()->isBefore($ppdbSetting->jadwal_buka)) {
            return redirect()->route('ppdb.sekolah', $slug)
                ->with('info', 'Pendaftaran belum dibuka');
        }

        $jalurs = $ppdbSetting->jalurs()->orderBy('urutan')->get();

        return view('ppdb.daftar', compact('ppdbSetting', 'jalurs'));
    }

    /**
     * Menyimpan data pendaftar baru
     */
    public function store(Request $request, $slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->where('status', 'buka')
            ->firstOrFail();

        // Cek waktu pendaftaran
        if (now()->isAfter($ppdbSetting->jadwal_tutup)) {
            return redirect()->route('ppdb.sekolah', $slug)
                ->with('error', 'Pendaftaran telah ditutup');
        }

        // Validasi input
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nisn' => 'required|string|max:20|unique:ppdb_pendaftars,nisn',
            'asal_sekolah' => 'required|string|max:100',
            'jurusan_pilihan' => 'required|string|max:50',
            'berkas_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ppdb_jalur_id' => 'required|exists:ppdb_jalurs,id',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nisn.required' => 'NISN harus diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'asal_sekolah.required' => 'Asal sekolah harus diisi',
            'jurusan_pilihan.required' => 'Pilihan jurusan harus diisi',
            'berkas_kk.required' => 'Berkas Kartu Keluarga harus diunggah',
            'berkas_ijazah.required' => 'Berkas Ijazah harus diunggah',
        ]);

        try {
            // Upload file berkas
            $berkasKK = $request->file('berkas_kk')->store('ppdb/berkas_kk', 'public');
            $berkasIjazah = $request->file('berkas_ijazah')->store('ppdb/berkas_ijazah', 'public');

            // Buat nomor pendaftaran unik
            $nomorPendaftaran = $this->generateNomorPendaftaran($ppdbSetting);

            // Simpan data pendaftar
            $pendaftar = PPDBPendaftar::create([
                'ppdb_setting_id' => $ppdbSetting->id,
                'ppdb_jalur_id' => $validated['ppdb_jalur_id'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'nisn' => $validated['nisn'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'jurusan_pilihan' => $validated['jurusan_pilihan'],
                'berkas_kk' => $berkasKK,
                'berkas_ijazah' => $berkasIjazah,
                'status' => 'pending',
                'nomor_pendaftaran' => $nomorPendaftaran,
            ]);

            return redirect()->route('ppdb.sekolah', $slug)
                ->with('success', "✅ Pendaftaran berhasil! Nomor pendaftaran Anda: <strong>{$pendaftar->nomor_pendaftaran}</strong>");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate nomor pendaftaran unik
     * Format: SMKM-2025-0001
     */
    private function generateNomorPendaftaran($ppdbSetting)
    {
        $year = now()->year;
        // Ambil 4 huruf pertama dari slug, uppercase
        $schoolCode = strtoupper(substr($ppdbSetting->slug, 0, 4));

        // Hitung jumlah pendaftar tahun ini untuk sekolah ini
        $count = PPDBPendaftar::where('ppdb_setting_id', $ppdbSetting->id)
            ->whereYear('created_at', $year)
            ->count() + 1;

        return "{$schoolCode}-{$year}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
