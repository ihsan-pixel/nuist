<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landing;
use App\Models\Madrasah;
use App\Models\Yayasan;
use App\Models\PPDBSetting;
use App\Models\DataSekolah;

class LandingController extends Controller
{
    /**
     * Show the landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $landing = Landing::getLanding();
        $madrasahs = Madrasah::all();
        $yayasan = Yayasan::find(1);

        // Dynamic counts from database
        $countMadrasah = Madrasah::count();
        $countTenagaPendidik = \App\Models\User::where('role', 'tenaga_pendidik')->whereNotNull('madrasah_id')->count();
        $countAdmin = \App\Models\User::whereIn('role', ['admin', 'operator'])->count();

        return view('landing.landing', compact(
            'landing',
            'madrasahs',
            'yayasan',
            'countMadrasah',
            'countTenagaPendidik',
            'countAdmin'
        ));
    }

    /**
     * Show the sekolah page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sekolah()
    {
        // Order by kabupaten first, then by scod within each kabupaten
        $madrasahs = Madrasah::orderBy('kabupaten')->orderBy('scod')->get();

        // Group by kabupaten for display
        $groupedMadrasahs = $madrasahs->groupBy('kabupaten');

        $yayasan = Yayasan::find(1);

        return view('landing.sekolah', compact('groupedMadrasahs', 'madrasahs', 'yayasan'));
    }

    /**
     * Show the sekolah detail page.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sekolahDetail($id)
    {
        $madrasah = Madrasah::findOrFail($id);
        $yayasan = Yayasan::find(1);

        // Get active PPDB setting (current year)
        $ppdbSetting = PPDBSetting::where('sekolah_id', $id)
            ->where('tahun', date('Y'))
            ->first();

        // Fallback to any available setting if no current year setting
        if (!$ppdbSetting) {
            $ppdbSetting = PPDBSetting::where('sekolah_id', $id)->latest()->first();
        }

        // Get kepala sekolah from users table based on ketugasan
        $kepalaSekolah = \App\Models\User::where('madrasah_id', $id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();

// Get jumlah guru from users table with same madrasah_id
        $jumlahGuru = \App\Models\User::where('madrasah_id', $id)
            ->where('role', 'tenaga_pendidik')
            ->count();

// Get jumlah siswa from data_sekolah table with latest year
        $dataSekolah = DataSekolah::where('madrasah_id', $id)
            ->orderBy('tahun', 'desc')
            ->first();
        $jumlahSiswa = $dataSekolah ? $dataSekolah->jumlah_siswa : 0;

        // Get PPDB slug for button link
        $ppdbSlug = $ppdbSetting ? $ppdbSetting->slug : null;

        return view('landing.sekolah-detail', compact('madrasah', 'yayasan', 'ppdbSetting', 'kepalaSekolah', 'ppdbSlug', 'jumlahGuru', 'jumlahSiswa'));
    }
}
