
<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;

class AdminLPController extends Controller
{
    public function index()
    {
        $totalSekolah = PPDBSetting::count();
        $totalPendaftar = PPDBPendaftar::count();
        $totalBuka = PPDBSetting::where('status', 'buka')->count();

        $data = [
            'totalSekolah' => $totalSekolah,
            'totalPendaftar' => $totalPendaftar,
            'totalBuka' => $totalBuka,
            'listSekolah' => PPDBSetting::withCount('pendaftar')->get(),
        ];

        return view('ppdb.dashboard.lp', $data);
    }
}
