<?php

namespace App\Http\Controllers\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiComponent;

class SchoolEvaluationController extends Controller
{
    public function index()
    {
        $components = AmiComponent::orderBy('sort_order')->get();

        return view('ami.evaluasi-diri.index', compact('components'));
    }
}
