<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MadrasahImport;

class MadrasahController extends Controller
{
    /**
     * Tampilkan daftar madrasah
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $madrasahs = Madrasah::where('id', $user->madrasah_id)->get();
        } else {
            $madrasahs = Madrasah::all();
        }
        return view('masterdata.madrasah.index', compact('madrasahs'));
    }

    /**
     * Simpan madrasah baru
     */
    public function store(Request $request)
    {
        if ($request->input('polygon_koordinat') === '') {
            $request->merge(['polygon_koordinat' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // opsional
            'polygon_koordinat' => 'nullable|json',
        ]);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('madrasah', 'public')
            : null;

        $madrasah = new Madrasah();
        $madrasah->name = $validated['name'];
        $madrasah->alamat = $validated['alamat'] ?? null;
        $madrasah->latitude = $validated['latitude'] ?? null;
        $madrasah->longitude = $validated['longitude'] ?? null;
        $madrasah->map_link = $validated['map_link'] ?? null;
        $madrasah->logo = $logoPath;
        $madrasah->polygon_koordinat = $validated['polygon_koordinat'] ?? null;
        $madrasah->save();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil ditambahkan.');
    }

    /**
     * Update data madrasah
     */
    public function update(Request $request, $id)
    {
        $madrasah = Madrasah::findOrFail($id);

        if ($request->input('polygon_koordinat') === '') {
            $request->merge(['polygon_koordinat' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // opsional
            'polygon_koordinat' => 'nullable|json',
        ]);

        // Jika ada file logo baru, hapus logo lama
        if ($request->hasFile('logo')) {
            if ($madrasah->logo && Storage::disk('public')->exists($madrasah->logo)) {
                Storage::disk('public')->delete($madrasah->logo);
            }
            $madrasah->logo = $request->file('logo')->store('madrasah', 'public');
        }

        $madrasah->name = $validated['name'];
        $madrasah->alamat = $validated['alamat'];
        $madrasah->latitude = $validated['latitude'];
        $madrasah->longitude = $validated['longitude'];
        $madrasah->map_link = $validated['map_link'];
        $madrasah->polygon_koordinat = $validated['polygon_koordinat'] ?? null;
        $madrasah->save();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil diperbarui.');
    }

    /**
     * Hapus madrasah
     */
    public function destroy($id)
    {
        $madrasah = Madrasah::findOrFail($id);

        if ($madrasah->logo && Storage::disk('public')->exists($madrasah->logo)) {
            Storage::disk('public')->delete($madrasah->logo);
        }

        $madrasah->delete();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil dihapus.');
    }

    /**
     * Import data madrasah dari Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new MadrasahImport, $request->file('file'));
            return redirect()->route('madrasah.index')->with('success', 'Data madrasah berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: '.$e->getMessage());
        }
    }
}
