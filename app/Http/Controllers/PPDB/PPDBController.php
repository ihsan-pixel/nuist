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
        $query = PPDBSetting::where('tahun', now()->year)
            ->with('sekolah')
            ->orderBy('jadwal_buka', 'asc');

        // Filter berdasarkan kabupaten
        if ($request->filled('kabupaten')) {
            $query->whereHas('sekolah', function ($q) use ($request) {
                $q->where('kabupaten', $request->kabupaten);
            });
        }

        // Search berdasarkan nama sekolah
        if ($request->filled('search')) {
            $query->whereHas('sekolah', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $sekolah = $query->get();

        // Ambil daftar kabupaten unik untuk filter
        $kabupatenList = PPDBSetting::where('tahun', now()->year)
            ->with('sekolah')
            ->get()
            ->pluck('sekolah.kabupaten')
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
