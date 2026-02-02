<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\User;
use App\Models\PPDBSetting;
use App\Models\StatusKepegawaian;

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
     * Get jumlah guru total from all schools
     */
    private function getTotalGuru()
    {
        return User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->count();
    }

    /**
     * Get jumlah siswa total from all schools
     */
    private function getTotalSiswa()
    {
        $latestData = DataSekolah::select('madrasah_id', 'jumlah_siswa')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('data_sekolah')
                    ->groupBy('madrasah_id');
            });

        return $latestData->sum('jumlah_siswa');
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
     * Get jumlah sarana from ppdb_settings table (fasilitas column - count objects with name)
     */
    private function getJumlahSarana($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->fasilitas && is_array($ppdbSetting->fasilitas)) {
            $fasilitasWithName = array_filter($ppdbSetting->fasilitas, function($item) {
                return is_array($item) && isset($item['name']) && !empty($item['name']);
            });
            return count($fasilitasWithName);
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
            $fasilitasList = array_filter($ppdbSetting->fasilitas, function($item) {
                return is_array($item) && isset($item['name']) && !empty($item['name']);
            });
            return array_values($fasilitasList);
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
     * Get tahun_berdiri from ppdb_settings
     */
    private function getTahunBerdiri($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->tahun_berdiri ? $ppdbSetting->tahun_berdiri : null;
    }

    /**
     * Get akreditasi from ppdb_settings
     */
    private function getAkreditasi($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->akreditasi ? $ppdbSetting->akreditasi : null;
    }

    /**
     * Get telepon from ppdb_settings
     */
    private function getTelepon($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->telepon ? $ppdbSetting->telepon : null;
    }

    /**
     * Get email from ppdb_settings
     */
    private function getEmail($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->email ? $ppdbSetting->email : null;
    }

    /**
     * Get website from ppdb_settings
     */
    private function getWebsite($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->website ? $ppdbSetting->website : null;
    }

    /**
     * Check if user is kepala sekolah based on ketugasan column
     */
    private function isKepalaSekolah($user)
    {
        if (!$user->ketugasan) {
            return false;
        }

        $ketugasanLower = strtolower(trim($user->ketugasan));

        // Check for various patterns of kepala madrasah/sekolah
        return $ketugasanLower === 'kepala madrasah' ||
               $ketugasanLower === 'kepala sekolah' ||
               strpos($ketugasanLower, 'kepala') !== false;
    }

    /**
     * Get list of tenaga pendidik with their status kepegawaian
     * Kepala sekolah will be sorted to the top based on ketugasan column
     */
    private function getTenagaPendidik($madrasahId)
    {
        $tenagaPendidik = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->with('statusKepegawaian')
            ->get();

        // Sort: kepala sekolah first (based on ketugasan column), then by name
        $tenagaPendidik = $tenagaPendidik->sortByDesc(function($user) {
            return $this->isKepalaSekolah($user) ? 1 : 0;
        });

        return $tenagaPendidik->values();
    }

    /**
     * Menampilkan daftar sekolah (madrasah)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $search = $request->get('search', '');

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

        $totalSekolah = Madrasah::count();
        $sekolahAktif = $totalSekolah;
        $totalGuru = $this->getTotalGuru();
        $totalSiswa = $this->getTotalSiswa();

        $jumlahSiswaData = [];
        foreach ($madrasahs as $madrasah) {
            $jumlahSiswaData[$madrasah->id] = $this->getJumlahSiswa($madrasah->id);
        }

        return view('mobile.pengurus.sekolah', compact(
            'madrasahs', 'search', 'totalSekolah', 'sekolahAktif', 'totalGuru', 'totalSiswa', 'jumlahSiswaData'
        ));
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

        $dataSekolah = DataSekolah::where('madrasah_id', $id)
            ->orderBy('tahun', 'desc')
            ->first();

        $jumlahGuru = $this->getJumlahGuru($id);
        $jumlahSiswa = $this->getJumlahSiswa($id);
        $jumlahJurusan = $this->getJumlahJurusan($id);
        $jumlahSarana = $this->getJumlahSarana($id);
        $fasilitasList = $this->getFasilitas($id);
        $ppdbSetting = $this->getPpdbSetting($id);
        $tahunBerdiri = $this->getTahunBerdiri($id);
        $akreditasi = $this->getAkreditasi($id);
        $telepon = $this->getTelepon($id);
        $email = $this->getEmail($id);
        $website = $this->getWebsite($id);
        $tenagaPendidik = $this->getTenagaPendidik($id);

        return view('mobile.pengurus.sekolah-detail', compact(
            'madrasah', 'dataSekolah', 'jumlahGuru', 'jumlahSiswa',
            'jumlahJurusan', 'jumlahSarana', 'fasilitasList', 'ppdbSetting',
            'tahunBerdiri', 'akreditasi', 'telepon', 'email', 'website',
            'tenagaPendidik'
        ));
    }
}

