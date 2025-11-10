<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index()
    {
        return view('presensi.index');
    }

    public function create()
    {
        return view('presensi.create');
    }

    public function store(Request $request)
    {
        // Handle presensi store logic
        return redirect()->route('presensi.index');
    }

    public function laporan()
    {
        return view('presensi.laporan');
    }
}
