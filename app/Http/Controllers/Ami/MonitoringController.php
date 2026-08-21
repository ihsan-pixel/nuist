<?php

namespace App\Http\Controllers\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiPeriodSchool;

class MonitoringController extends Controller
{
    public function index()
    {
        $items = AmiPeriodSchool::with(['period', 'school'])->latest()->get();

        return view('ami.monitoring.index', compact('items'));
    }
}
