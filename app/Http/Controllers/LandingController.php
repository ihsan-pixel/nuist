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

        return view('landing.landing', compact('landing', 'madrasahs', 'yayasan'));
    }
}
