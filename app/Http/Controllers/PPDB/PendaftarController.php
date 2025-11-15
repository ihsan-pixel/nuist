<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use App\Models\PPDBJalur;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PendaftarController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function create($slug)
    {
        // Coba cari berdasarkan slug PPDB setting
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah', 'jalurs')
            ->first();

        // Jika tidak ditemukan berdasarkan slug, coba cari berdasarkan ID madrasah
        if (!$ppdbSetting && is_numeric($slug)) {
            $madrasah = Madrasah::find($slug);
            if ($madrasah) {
                $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
                    ->where('tahun', now()->year)
                    ->with('sekolah', 'jalurs')
                    ->first();
            }
        }

        // Jika masih tidak ditemukan, buat objek temporary untuk tampilan
        if (!$ppdbSetting) {
            $madrasah = is_numeric($slug) ? Madrasah::find($slug) : null;
            if (!$madrasah) {
                abort(404, 'Sekolah tidak ditemukan');
            }

            $ppdbSetting = (object) [
                'id' => null,
                'slug' => $slug,
                'nama_sekolah' => $madrasah->name,
                'tahun' => now()->year,
                'status' => 'tutup',
                'sekolah' => $madrasah,
                'jalurs' => collect()
            ];
        }

        // Jika PPDB setting ada tapi status tidak buka, tetap tampilkan form
        // tapi dengan peringatan
        if (isset($ppdbSetting->jalurs) && method_exists($ppdbSetting->jalurs, 'orderBy')) {
            $jalurs = $ppdbSetting->jalurs()->orderBy('urutan')->get();
        } else {
            $jalurs = PPDBJalur::orderBy('urutan')->get();
        }

        return view('ppdb.daftar', compact('ppdbSetting', 'jalurs'));
    }

    /**
     * Menyimpan data pendaftar baru
     */
    public function store(Request $request, $slug)
    {
        // Coba cari PPDB setting berdasarkan slug
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->first();

        // Jika tidak ditemukan, coba cari berdasarkan ID madrasah
        if (!$ppdbSetting && is_numeric($slug)) {
            $madrasah = Madrasah::find($slug);
            if ($madrasah) {
                $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
                    ->where('tahun', now()->year)
                    ->first();
            }
        }

        // Jika PPDB setting tidak ditemukan atau tidak aktif, redirect dengan error
        if (!$ppdbSetting || $ppdbSetting->status !== 'buka') {
            return redirect()->back()
                ->with('error', 'Pendaftaran belum dibuka atau telah ditutup untuk sekolah ini.')
                ->withInput();
        }

        // Cek waktu pendaftaran
        if (now()->isAfter($ppdbSetting->jadwal_tutup)) {
            return redirect()->back()
                ->with('error', 'Pendaftaran telah ditutup')
                ->withInput();
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
     * Check NISN availability via AJAX
     */
    public function checkNISN($nisn)
    {
        $exists = PPDBPendaftar::where('nisn', $nisn)->exists();

        return response()->json(['exists' => $exists]);
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
