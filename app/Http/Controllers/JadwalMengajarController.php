<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalMengajarController extends Controller
{
    /**
     * Display jadwal mengajar index page
     */
    public function index(Request $request)
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // For now, return a basic view
        // TODO: Implement actual jadwal mengajar functionality
        return view('jadwal-mengajar.index');
    }
}
