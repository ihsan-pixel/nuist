<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhirTahunKepalaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanAkhirTahunKepalaSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporans = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->orderBy('tahun_pelaporan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile.laporan-akhir-tahun.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Check if user already has a report for current year
        $currentYear = Carbon::now()->year;
        $existingReport = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->where('tahun_pelaporan', $currentYear)
            ->first();

        if ($existingReport) {
            return redirect()->route('mobile.laporan-akhir-tahun.edit', $existingReport->id)
                ->with('info', 'Anda sudah memiliki laporan untuk tahun ' . $currentYear . '. Silakan edit laporan tersebut.');
        }

        // Pre-fill data from user and madrasah
        $data = [
            'nama_kepala_sekolah' => $user->name,
            'nip' => $user->nip,
            'nuptk' => $user->nuptk,
            'nama_madrasah' => $user->madrasah->name ?? '',
            'alamat_madrasah' => $user->madrasah->alamat ?? '',
            'tahun_pelaporan' => $currentYear,
        ];

        return view('mobile.laporan-akhir-tahun.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $request->validate([
            'tahun_pelaporan' => 'required|integer|min:2020|max:' . (Carbon::now()->year + 1),
            'nama_kepala_sekolah' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'nuptk' => 'nullable|string|max:255',
            'nama_madrasah' => 'required|string|max:255',
            'alamat_madrasah' => 'required|string',
            'jumlah_guru' => 'required|integer|min:0',
            'jumlah_siswa' => 'required|integer|min:0',
            'jumlah_kelas' => 'required|integer|min:0',
            'prestasi_madrasah' => 'required|string',
            'kendala_utama' => 'required|string',
            'program_kerja_tahun_depan' => 'required|string',
            'anggaran_digunakan' => 'nullable|numeric|min:0',
            'saran_dan_masukan' => 'nullable|string',
            'tanggal_laporan' => 'required|date',
            'pernyataan_setuju' => 'required|accepted',
        ]);

        // Check if report already exists for this year
        $existingReport = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->where('tahun_pelaporan', $request->tahun_pelaporan)
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tahun_pelaporan' => 'Laporan untuk tahun ini sudah ada.']);
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::create([
            'user_id' => $user->id,
            'tahun_pelaporan' => $request->tahun_pelaporan,
            'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'nama_madrasah' => $request->nama_madrasah,
            'alamat_madrasah' => $request->alamat_madrasah,
            'jumlah_guru' => $request->jumlah_guru,
            'jumlah_siswa' => $request->jumlah_siswa,
            'jumlah_kelas' => $request->jumlah_kelas,
            'prestasi_madrasah' => $request->prestasi_madrasah,
            'kendala_utama' => $request->kendala_utama,
            'program_kerja_tahun_depan' => $request->program_kerja_tahun_depan,
            'anggaran_digunakan' => $request->anggaran_digunakan,
            'saran_dan_masukan' => $request->saran_dan_masukan,
            'tanggal_laporan' => $request->tanggal_laporan,
            'status' => 'submitted',
        ]);

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        return view('mobile.laporan-akhir-tahun.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        return view('mobile.laporan-akhir-tahun.edit', compact('laporan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        $request->validate([
            'nama_kepala_sekolah' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'nuptk' => 'nullable|string|max:255',
            'nama_madrasah' => 'required|string|max:255',
            'alamat_madrasah' => 'required|string',
            'jumlah_guru' => 'required|integer|min:0',
            'jumlah_siswa' => 'required|integer|min:0',
            'jumlah_kelas' => 'required|integer|min:0',
            'prestasi_madrasah' => 'required|string',
            'kendala_utama' => 'required|string',
            'program_kerja_tahun_depan' => 'required|string',
            'anggaran_digunakan' => 'nullable|numeric|min:0',
            'saran_dan_masukan' => 'nullable|string',
            'tanggal_laporan' => 'required|date',
            'pernyataan_setuju' => 'required|accepted',
        ]);

        $laporan->update([
            'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'nama_madrasah' => $request->nama_madrasah,
            'alamat_madrasah' => $request->alamat_madrasah,
            'jumlah_guru' => $request->jumlah_guru,
            'jumlah_siswa' => $request->jumlah_siswa,
            'jumlah_kelas' => $request->jumlah_kelas,
            'prestasi_madrasah' => $request->prestasi_madrasah,
            'kendala_utama' => $request->kendala_utama,
            'program_kerja_tahun_depan' => $request->program_kerja_tahun_depan,
            'anggaran_digunakan' => $request->anggaran_digunakan,
            'saran_dan_masukan' => $request->saran_dan_masukan,
            'tanggal_laporan' => $request->tanggal_laporan,
        ]);

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        // Only allow deletion of draft reports
        if ($laporan->status !== 'draft') {
            return redirect()->back()
                ->withErrors(['error' => 'Hanya laporan dengan status draft yang dapat dihapus.']);
        }

        $laporan->delete();

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil dihapus.');
    }
}
