<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\User;
use App\Models\PPDBSetting;

class SekolahController extends \App\Http\Controllers\Controller
{
    /**
     * Get jumlah siswa from data_sekolah based on latest year
     */
    private function getJumlahSiswa($madrasahId)
    {
        $dataSekolah = DataSekolah::where('madrasah_id', $madrasahId)
            ->orderBy('tahun', 'desc')
            ->first();

        return $dataSekolah ? $dataSekolah->jumlah_siswa : 0;
    }

    /**
     * Get jumlah guru from users table (users with madrasah_id and role tenaga_pendidik)
     */
    private function getJumlahGuru($madrasahId)
    {
        return User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->count();
    }

    /**
     * Get jumlah jurusan from ppdb_settings table
     */
    private function getJumlahJurusan($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->jurusan && is_array($ppdbSetting->jurusan)) {
            return count($ppdbSetting->jurusan);
        }

        return 0;
    }

    /**
     * Get jumlah sarana from ppdb_settings table (fasilitas column)
     */
    private function getJumlahSarana($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->fasilitas && is_array($ppdbSetting->fasilitas)) {
            return count($ppdbSetting->fasilitas);
        }

        return 0;
    }

    /**
     * Get fasilitas list from ppdb_settings table
     */
    private function getFasilitas($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->fasilitas && is_array($ppdbSetting->fasilitas)) {
            return $ppdbSetting->fasilitas;
        }

        return [];
    }

    /**
     * Get ppdb_setting for current year
     */
    private function getPpdbSetting($madrasahId)
    {
        return PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();
    }

    /**
     * Menampilkan daftar sekolah (madrasah)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user is pengurus
        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get search query
        $search = $request->get('search', '');

        // Get madrasah data with search functionality
        $query = Madrasah::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        $madrasahs = $query->orderBy('scod', 'asc')
                          ->paginate(10);

        // Get statistics
        $totalSekolah = Madrasah::count();
        $sekolahAktif = Madrasah::count();

        // Get jumlah siswa from data_sekolah for each madrasah
        $jumlahSiswaData = [];
        foreach ($madrasahs as $madrasah) {
            $jumlahSiswaData[$madrasah->id] = $this->getJumlahSiswa($madrasah->id);
        }

        return view('mobile.pengurus.sekolah', compact('madrasahs', 'search', 'totalSekolah', 'sekolahAktif', 'jumlahSiswaData'));
    }

    /**
     * Menampilkan detail sekolah
     */
    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $madrasah = Madrasah::findOrFail($id);

        // Get data_sekolah data for siswa
        $dataSekolah = DataSekolah::where('madrasah_id', $id)
            ->orderBy('tahun', 'desc')
            ->first();

        // Get jumlah guru from users table
        $jumlahGuru = $this->getJumlahGuru($id);

        // Get jumlah siswa from data_sekolah
        $jumlahSiswa = $this->getJumlahSiswa($id);

        // Get jumlah jurusan from ppdb_settings
        $jumlahJurusan = $this->getJumlahJurusan($id);

        // Get jumlah sarana from ppdb_settings (fasilitas)
        $jumlahSarana = $this->getJumlahSarana($id);

        // Get fasilitas list from ppdb_settings
        $fasilitasList = $this->getFasilitas($id);

        // Get ppdb_setting for additional data
        $ppdbSetting = $this->getPpdbSetting($id);

        return view('mobile.pengurus.sekolah-detail', compact(
            'madrasah', 'dataSekolah', 'jumlahGuru', 'jumlahSiswa',
            'jumlahJurusan', 'jumlahSarana', 'fasilitasList', 'ppdbSetting'
        ));
    }
}

