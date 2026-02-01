<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;

class PresensiKehadiranController extends \App\Http\Controllers\Controller
{
    // Presensi Kehadiran
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get presensi data
        $presensis = Presensi::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.presensi-kehadiran', compact('presensis'));
    }
}
