<?php

namespace App\Http\Controllers;

use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StatusKepegawaianImport;

class StatusKepegawaianController extends Controller
{
    /**
     * Tampilkan daftar status kepegawaian
     */
    public function index()
    {
        $statusKepegawaian = StatusKepegawaian::all();
        return view('masterdata.status-kepegawaian.index', compact('statusKepegawaian'));
    }

    /**
     * Simpan status kepegawaian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        StatusKepegawaian::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil ditambahkan.');
    }

    /**
     * Update data status kepegawaian
     */
    public function update(Request $request, $id)
    {
        $statusKepegawaian = StatusKepegawaian::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $statusKepegawaian->name = $validated['name'];
        $statusKepegawaian->save();

        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil diperbarui.');
    }

    /**
     * Hapus status kepegawaian
     */
    public function destroy($id)
    {
        $statusKepegawaian = StatusKepegawaian::findOrFail($id);

        $statusKepegawaian->delete();

        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil dihapus.');
    }

    /**
     * Import data status kepegawaian dari Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new StatusKepegawaianImport, $request->file('file'));
            return redirect()->route('status-kepegawaian.index')->with('success', 'Data status kepegawaian berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
