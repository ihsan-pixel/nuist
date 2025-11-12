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
    public function index()
    {
        $sekolah = PPDBSetting::where('tahun', now()->year)
            ->with('sekolah')
            ->orderBy('jadwal_buka', 'asc')
            ->get();

        return view('ppdb.index', compact('sekolah'));
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
