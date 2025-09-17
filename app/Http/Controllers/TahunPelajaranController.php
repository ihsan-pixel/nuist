<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TahunPelajaranImport;

class TahunPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunPelajaran = TahunPelajaran::all();
        return view('masterdata.tahun-pelajaran.index', compact('tahunPelajaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TahunPelajaran::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tahunPelajaran->name = $validated['name'];
        $tahunPelajaran->save();

        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);

        $tahunPelajaran->delete();

        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }

    /**
     * Import data from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TahunPelajaranImport, $request->file('file'));
            return redirect()->route('tahun-pelajaran.index')->with('success', 'Data tahun pelajaran berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
