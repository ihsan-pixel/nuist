<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class PenggunaAktifController extends \App\Http\Controllers\Controller
{
    // Pengguna Aktif
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get active users - placeholder for now
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('last_login_at', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.pengguna-aktif', compact('activeUsers'));
    }
}
