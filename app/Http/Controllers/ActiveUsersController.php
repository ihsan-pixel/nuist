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
        // Check if user is super_admin or pengurus
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        // Get active users (last_seen within 5 minutes)
        $activeUsersByRole = User::where('last_seen', '>=', now()->subMinutes(0))
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

    public function apiIndex()
    {
        // Check if user is super_admin or pengurus
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        // Get active users (last_seen within 5 minutes)
        $activeUsersByRole = User::where('last_seen', '>=', now()->subMinutes(5))
            ->with(['madrasah', 'statusKepegawaian'])
            ->get()
            ->groupBy('role')
            ->map(function ($users, $role) {
                return $users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'nuist_id' => $user->nuist_id,
                        'avatar' => $user->avatar ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg'),
                        'madrasah' => $user->madrasah ? $user->madrasah->nama : null,
                        'last_seen' => $user->last_seen ? $user->last_seen->diffForHumans() : 'N/A',
                        'role' => $user->role,
                    ];
                });
            });

        // Get role labels
        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'tenaga_pendidik' => 'Tenaga Pendidik',
        ];

        return response()->json([
            'activeUsersByRole' => $activeUsersByRole,
            'roleLabels' => $roleLabels,
            'totalActive' => $activeUsersByRole->flatten(1)->count(),
        ]);
    }
}
