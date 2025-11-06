<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ActiveUsers extends Component
{
    public $activeUsersByRole;
    public $roleLabels;
    public $totalActive;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        // Check if user is super_admin or pengurus
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        // Get active users (last_seen within 5 minutes)
        $this->activeUsersByRole = User::where('last_seen', '>=', now()->subMinutes(5))
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
        $this->roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'tenaga_pendidik' => 'Tenaga Pendidik',
        ];

        $this->totalActive = $this->activeUsersByRole->flatten(1)->count();
    }

    public function render()
    {
        return view('livewire.active-users');
    }
}
