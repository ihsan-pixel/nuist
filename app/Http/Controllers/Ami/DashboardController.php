<?php

namespace App\Http\Controllers\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiAssignment;
use App\Models\AmiPeriod;
use App\Models\AmiPeriodSchool;
use App\Models\Madrasah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $periods = AmiPeriod::latest('year')->get();

        $stats = [
            'periods' => $periods->count(),
            'schools' => Madrasah::count(),
            'assignments' => AmiAssignment::count(),
            'tracked_schools' => AmiPeriodSchool::count(),
        ];

        return view('ami.dashboard', [
            'user' => $user,
            'role' => (string) $user->role,
            'periods' => $periods,
            'stats' => $stats,
        ]);
    }
}
