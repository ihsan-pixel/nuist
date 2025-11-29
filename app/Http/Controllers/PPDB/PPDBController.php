<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\Madrasah;
use App\Models\User;
use Illuminate\Support\Str;
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
        // Jika slug numerik → cari madrasah berdasarkan ID
        if (is_numeric($slug)) {
            $madrasah = Madrasah::findOrFail($slug);
        } else {
            // Cari PPDBSetting berdasarkan slug valid
            $ppdb = PPDBSetting::where('slug', $slug)
                ->where('tahun', now()->year)
                ->with('sekolah')
                ->first();

            if ($ppdb) {
                $madrasah = $ppdb->sekolah;
            } else {
                // Jika slug tidak cocok, buat pencarian slug dari name
                $madrasah = Madrasah::whereRaw(
                        'LOWER(REPLACE(name, " ", "-")) = ?',
                        [strtolower($slug)]
                    )
                    ->orWhere('name', 'like', '%' . str_replace('-', ' ', $slug) . '%')
                    ->firstOrFail();
            }
        }

        // Ambil/tambahkan setting PPDB tahun berjalan
        $ppdb = PPDBSetting::firstOrCreate(
            [
                'sekolah_id' => $madrasah->id,
                'tahun' => now()->year
            ],
            [
                'nama_sekolah' => $madrasah->name,
                'slug' => Str::slug($madrasah->name . '-' . $madrasah->id . '-' . now()->year)
            ]
        );

        $pendaftarCount = $ppdb->pendaftars()->count();

        // Ambil kepala sekolah dari users
        $kepalaSekolah = User::where('madrasah_id', $madrasah->id)
            ->where('ketugasan', 'like', '%kepala%')
            ->first();

        return view('ppdb.sekolah', compact('ppdb', 'pendaftarCount', 'madrasah', 'kepalaSekolah'));
    }
}
