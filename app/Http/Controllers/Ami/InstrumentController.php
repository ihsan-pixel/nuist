<?php

namespace App\Http\Controllers\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiComponent;
use App\Models\AmiInstrument;

class InstrumentController extends Controller
{
    public function index()
    {
        $instrument = AmiInstrument::with('period')->latest()->first();
        $components = AmiComponent::orderBy('sort_order')->get();

        return view('ami.instruments.index', compact('instrument', 'components'));
    }
}
