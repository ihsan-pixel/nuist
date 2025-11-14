<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use Illuminate\Http\Request;

class PPDBController extends Controller
{
    /**
     * Menampilkan halaman utama PPDB NUIST
     * Daftar madrasah/sekolah yang membuka PPDB
     */
    public function index(Request $request)
    {
        // Ambil semua madrasah yang memiliki PPDB setting untuk tahun ini
        $query = Madrasah::whereHas('ppdbSettings', function ($q) {
            $q->where('tahun', now()->year);
        })->with(['ppdbSettings' => function ($q) {
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

        $sekolah = $query->orderBy('name')->get();

        // Ambil daftar kabupaten unik untuk filter
        $kabupatenList = Madrasah::whereHas('ppdbSettings', function ($q) {
            $q->where('tahun', now()->year);
        })->pluck('kabupaten')
        ->filter()
        ->unique()
        ->sort()
        ->values();

        return view('ppdb.index', compact('sekolah', 'kabupatenList'));
    }

    /**
     * Menampilkan halaman PPDB per sekolah
     * Informasi, jadwal, dan tombol daftar
     */
    public function showSekolah($slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->firstOrFail();

        $pendaftarCount = $ppdbSetting->pendaftars()->count();

        return view('ppdb.sekolah', compact('ppdbSetting', 'pendaftarCount'));
    }
}
