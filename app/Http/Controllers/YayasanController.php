<?php

namespace App\Http\Controllers;

use App\Models\Yayasan;
use Illuminate\Http\Request;

class YayasanController extends Controller
{
    /**
     * Tampilkan daftar yayasan
     */
    public function index()
    {
        $yayasans = Yayasan::all();
        return view('masterdata.yayasan.index', compact('yayasans'));
    }

    /**
     * Simpan yayasan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        Yayasan::create($validated);

        return redirect()->route('yayasan.index')->with('success', 'Yayasan berhasil ditambahkan.');
    }

    /**
     * Update data yayasan
     */
    public function update(Request $request, $id)
    {
        $yayasan = Yayasan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        $yayasan->update($validated);

        return redirect()->route('yayasan.index')->with('success', 'Yayasan berhasil diperbarui.');
    }

    /**
     * Hapus yayasan
     */
    public function destroy($id)
    {
        $yayasan = Yayasan::findOrFail($id);
        $yayasan->delete();

        return redirect()->route('yayasan.index')->with('success', 'Yayasan berhasil dihapus.');
    }
}
