<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landing;
use App\Models\Madrasah;
use App\Models\Yayasan;
use App\Models\PPDBSetting;

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

        return view('landing.sekolah-detail', compact('madrasah', 'yayasan', 'ppdbSetting', 'kepalaSekolah'));
    }
}
