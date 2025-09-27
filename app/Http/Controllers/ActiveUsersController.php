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

        // Get user IDs that have presensi records
        $activeUserIds = Presensi::distinct('user_id')->pluck('user_id');

        // Get active users grouped by role
        $activeUsersByRole = User::whereIn('id', $activeUserIds)
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
