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
        $ppdb = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->firstOrFail();

        $pendaftarCount = $ppdb->pendaftars()->count();

        $madrasah = $ppdb->sekolah;

        return view('ppdb.sekolah', compact('ppdb', 'pendaftarCount', 'madrasah'));
    }
}
