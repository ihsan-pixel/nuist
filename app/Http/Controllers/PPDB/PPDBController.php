<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\Madrasah;
use Illuminate\Http\Request;

class PPDBController extends Controller
{
    /**
     * Menampilkan halaman utama PPDB NUIST
     * Daftar semua madrasah/sekolah dari database
     */
    public function index(Request $request)
    {
        // Ambil semua madrasah dari database dengan relasi PPDB settings
        $query = Madrasah::with(['ppdbSettings' => function ($q) {
            $q->where('tahun', now()->year);
        }]);

        // Filter berdasarkan kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', $request->kabupaten);
        }

        // Search berdasarkan nama sekolah
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sekolah = $query->get();

        // Kelompokkan berdasarkan kabupaten dan urutkan berdasarkan SCOD dalam setiap kelompok
        $sekolahGrouped = $sekolah->groupBy('kabupaten')->map(function ($group) {
            return $group->sortBy('scod');
        });

        // Ambil daftar kabupaten unik untuk filter
        $kabupatenList = Madrasah::pluck('kabupaten')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('ppdb.index', compact('sekolahGrouped', 'kabupatenList'));
    }

    /**
     * Menampilkan halaman PPDB per sekolah
     * Informasi, jadwal, dan tombol daftar
     */
    public function showSekolah($slug)
    {
        // Cek apakah $slug adalah ID numerik atau slug string
        if (is_numeric($slug)) {
            // Jika numerik, cari berdasarkan madrasah ID
            $madrasah = Madrasah::findOrFail($slug);
            $ppdb = PPDBSetting::where('sekolah_id', $madrasah->id)
                ->where('tahun', now()->year)
                ->first();

            if (!$ppdb) {
                // Jika tidak ada PPDB setting, buat objek temporary untuk tampilan
                $ppdb = (object) [
                    'nama_sekolah' => $madrasah->name,
                    'tahun' => now()->year,
                    'status' => 'tutup',
                    'slug' => null,
                    'sekolah' => $madrasah
                ];
            }
        } else {
            // Jika string, cari berdasarkan slug PPDB setting
            $ppdb = PPDBSetting::where('slug', $slug)
                ->where('tahun', now()->year)
                ->with('sekolah')
                ->first();

            // Jika tidak ditemukan berdasarkan slug PPDB, cari berdasarkan nama madrasah
            if (!$ppdb) {
                $madrasah = Madrasah::where('name', 'like', '%' . str_replace('-', ' ', $slug) . '%')
                    ->orWhere('name', 'like', '%' . $slug . '%')
                    ->first();

                if ($madrasah) {
                    $ppdb = PPDBSetting::where('sekolah_id', $madrasah->id)
                        ->where('tahun', now()->year)
                        ->first();

                    if (!$ppdb) {
                        // Jika tidak ada PPDB setting, buat objek temporary untuk tampilan
                        $ppdb = (object) [
                            'nama_sekolah' => $madrasah->name,
                            'tahun' => now()->year,
                            'status' => 'tutup',
                            'slug' => null,
                            'sekolah' => $madrasah
                        ];
                    }
                } else {
                    abort(404, 'Sekolah tidak ditemukan');
                }
            } else {
                $madrasah = $ppdb->sekolah;
            }
        }

        $pendaftarCount = isset($ppdb->id) ? $ppdb->pendaftars()->count() : 0;

        // Ambil data kepala sekolah dari tabel users
        $kepalaSekolah = User::where('madrasah_id', $madrasah->id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();

        return view('ppdb.sekolah', compact('ppdb', 'pendaftarCount', 'madrasah', 'kepalaSekolah'));
    }
}
