<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use Illuminate\Http\Request;

class PPDBSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun = now()->year;

        // Ambil semua PPDB settings untuk tahun ini dengan relasi sekolah
        $ppdbSettings = PPDBSetting::with('sekolah')
            ->where('tahun', $tahun)
            ->orderBy('nama_sekolah')
            ->get();

        return view('ppdb.settings.index', compact('ppdbSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ppdbSetting = PPDBSetting::with('sekolah')->findOrFail($id);

        return view('ppdb.settings.edit', compact('ppdbSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ppdbSetting = PPDBSetting::findOrFail($id);

        $request->validate([
            'status' => 'required|in:buka,tutup',
            'jadwal_buka' => 'required|date',
            'jadwal_tutup' => 'required|date|after:jadwal_buka',
            'kuota_total' => 'required|integer|min:1',
            'kuota_jurusan' => 'nullable|array',
            'kuota_jurusan.*' => 'nullable|integer|min:0',
            'periode_presensi_mulai' => 'nullable|date',
            'periode_presensi_selesai' => 'nullable|date|after:periode_presensi_mulai',
            'wajib_unggah_foto' => 'boolean',
            'wajib_unggah_ijazah' => 'boolean',
            'wajib_unggah_kk' => 'boolean',
            'syarat_tambahan' => 'nullable|string',
            'email_kontak' => 'nullable|email',
            'telepon_kontak' => 'nullable|string|max:20',
            'alamat_kontak' => 'nullable|string',
            'jadwal_pengumuman' => 'nullable|date',
            'catatan_pengumuman' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'ekstrakurikuler' => 'nullable|string',
            'biaya_pendidikan' => 'nullable|string',
            'informasi_tambahan' => 'nullable|string',
        ]);

        $data = $request->all();

        // Handle kuota_jurusan - filter out empty values
        if (isset($data['kuota_jurusan'])) {
            $data['kuota_jurusan'] = array_filter($data['kuota_jurusan'], function($value) {
                return is_numeric($value) && $value > 0;
            });
        }

        // Handle boolean fields
        $booleanFields = ['wajib_unggah_foto', 'wajib_unggah_ijazah', 'wajib_unggah_kk'];
        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field);
        }

        $ppdbSetting->update($data);

        return redirect()->route('ppdb.settings.index')->with('success', 'Pengaturan PPDB berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
