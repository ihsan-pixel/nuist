<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PPDBPendaftar;
use App\Models\PPDBSetting;

class AdminSekolahController extends Controller
{
    public function index()
    {
        $ppdb = PPDBSetting::where('sekolah_id', auth()->id())->first();
        $pendaftar = PPDBPendaftar::where('ppdb_setting_id', $ppdb->id)->get();
        return view('ppdb.dashboard.sekolah', compact('ppdb', 'pendaftar'));
    }

    public function verifikasi($id)
    {
        $pendaftar = PPDBPendaftar::findOrFail($id);
        $pendaftar->update(['status' => 'verifikasi']);
        return back()->with('success', 'Data berhasil diverifikasi.');
    }

    public function seleksi($id, Request $request)
    {
        $pendaftar = PPDBPendaftar::findOrFail($id);
        $pendaftar->update(['status' => $request->status]);
        return back()->with('success', 'Status seleksi diperbarui.');
    }

    public function export()
    {
        $ppdb = PPDBSetting::where('sekolah_id', auth()->id())->first();
        $pendaftar = PPDBPendaftar::where('ppdb_setting_id', $ppdb->id)->get();

        return view('ppdb.dashboard.export', compact('ppdb', 'pendaftar'));
    }
}
