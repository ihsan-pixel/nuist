<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaPendidik;
use App\Models\Madrasah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        $madrasahId = Auth::user()->madrasah_id ?? null;

        if ($madrasahId) {
            $tenagaPendidiks = TenagaPendidik::where('madrasah_id', $madrasahId)->get();
        } else {
            $tenagaPendidiks = TenagaPendidik::all();
        }

        return view('jadwal-mengajar.index', [
            'tenagaPendidiks' => $tenagaPendidiks,
            'madrasahId' => $madrasahId,
        ]);
    }

    public function store(Request $request)
    {
        // Validate and store jadwal mengajar data
        $validated = $request->validate([
            'tenaga_pendidik_id' => 'required|exists:tenaga_pendidiks,id',
            'madrasah_id' => 'required|exists:madrasahs,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mata_pelajaran' => 'required|string',
        ]);

        // Store logic here (e.g., create JadwalMengajar model)
        // For now, just log the data
        Log::info('Jadwal Mengajar stored:', $validated);

        return redirect()->route('jadwal-mengajar.index')->with('success', 'Jadwal mengajar berhasil disimpan.');
    }

    public function import(Request $request)
    {
        // Validate import file
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        // Import logic here
        // For now, just log the file info
        Log::info('Jadwal Mengajar import file:', ['file' => $request->file('import_file')->getClientOriginalName()]);

        return redirect()->route('jadwal-mengajar.index')->with('success', 'File jadwal mengajar berhasil diimport.');
    }
}
