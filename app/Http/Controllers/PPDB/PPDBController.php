<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PPDBSetting;

class PPDBController extends Controller
{
    // Halaman utama daftar PPDB semua sekolah
    public function index()
    {
        $sekolah = PPDBSetting::where('status', 'buka')->get();
        return view('ppdb.index', compact('sekolah'));
    }

    // Halaman PPDB per sekolah
    public function showSekolah($slug)
    {
        $ppdb = PPDBSetting::where('slug', $slug)->firstOrFail();
        return view('ppdb.sekolah', compact('ppdb'));
    }
}
