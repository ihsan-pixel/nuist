<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PPDBPendaftar;
use App\Models\PPDBSetting;

class PendaftarController extends Controller
{
    public function create($slug)
    {
        $ppdb = PPDBSetting::where('slug', $slug)->firstOrFail();
        return view('ppdb.daftar', compact('ppdb'));
    }

    public function store(Request $request, $slug)
    {
        $ppdb = PPDBSetting::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'asal_sekolah' => 'nullable|string|max:255',
            'jurusan_pilihan' => 'nullable|string|max:255',
            'berkas_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Simpan berkas
        $kkPath = $request->file('berkas_kk') ? $request->file('berkas_kk')->store('ppdb/kk', 'public') : null;
        $ijazahPath = $request->file('berkas_ijazah') ? $request->file('berkas_ijazah')->store('ppdb/ijazah', 'public') : null;

        PPDBPendaftar::create([
            'ppdb_setting_id' => $ppdb->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nisn' => $validated['nisn'] ?? null,
            'asal_sekolah' => $validated['asal_sekolah'] ?? null,
            'jurusan_pilihan' => $validated['jurusan_pilihan'] ?? null,
            'berkas_kk' => $kkPath,
            'berkas_ijazah' => $ijazahPath,
        ]);

        return redirect()->route('ppdb.sekolah', $slug)->with('success', 'Pendaftaran berhasil dikirim!');
    }
}
