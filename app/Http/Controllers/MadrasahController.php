<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MadrasahImport;
use Illuminate\Support\Facades\DB;

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
        } elseif ($user->role === 'pengurus' || $user->role === 'super_admin') {
            $madrasahs = Madrasah::all();
        } else {
            abort(403, 'Unauthorized access');
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

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // Validasi ukuran file (maksimal 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran file logo terlalu besar. Maksimal 2MB.');
            }

            // Generate nama file yang unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('madrasah', $filename, 'public');

            // Debug logging
            \Log::info('Logo uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $logoPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

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
            $file = $request->file('logo');

            // Validasi ukuran file (maksimal 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran file logo terlalu besar. Maksimal 2MB.');
            }

            // Hapus logo lama jika ada
            if ($madrasah->logo && Storage::disk('public')->exists($madrasah->logo)) {
                Storage::disk('public')->delete($madrasah->logo);
                \Log::info('Old logo deleted', ['path' => $madrasah->logo]);
            }

            // Generate nama file yang unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $madrasah->logo = $file->storeAs('madrasah', $filename, 'public');

            // Debug logging
            \Log::info('Logo updated successfully', [
                'madrasah_id' => $madrasah->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $madrasah->logo,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
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

    /**
     * Tampilkan profile madrasah dengan data tenaga pendidik
     */
    public function profile()
    {
        $user = auth()->user();
        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            $madrasahs = Madrasah::withCount('tenagaPendidikUsers')->get();
        } else {
            abort(403, 'Unauthorized access');
        }
        return view('masterdata.madrasah.profile', compact('madrasahs'));
    }

    /**
     * Tampilkan detail profile madrasah lengkap
     */
    public function detail($id)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        $madrasah = Madrasah::findOrFail($id);

        // Cari kepala sekolah berdasarkan ketugasan 'kepala_madrasah'
        $kepalaSekolah = \App\Models\User::where('madrasah_id', $id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();

        // Hitung jumlah TP berdasarkan status kepegawaian
        $tpByStatus = $madrasah->tenagaPendidik->groupBy('statusKepegawaian.name')->map->count();

        return view('masterdata.madrasah.detail', compact('madrasah', 'kepalaSekolah', 'tpByStatus'));
    }
}
