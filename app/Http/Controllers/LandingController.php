<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landing;
use App\Models\Madrasah;
use App\Models\Yayasan;

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
}
