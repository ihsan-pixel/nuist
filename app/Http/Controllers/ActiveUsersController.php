<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presensi;

class ActiveUsersController extends Controller
{
    /**
     * Display active users grouped by role
     */
    public function index()
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // Get active users (last_seen within 5 minutes)
        $activeUsersByRole = User::where('last_seen', '>=', now()->subMinutes(5))
            ->with(['madrasah', 'statusKepegawaian'])
            ->get()
            ->groupBy('role');

        // Get role labels
        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'tenaga_pendidik' => 'Tenaga Pendidik',
        ];

        return view('active-users.index', compact('activeUsersByRole', 'roleLabels'));
    }
}
