<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MGMPController extends Controller
{
    public function index()
    {
        return view('mgmp.index');
    }
}
