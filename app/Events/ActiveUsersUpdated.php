<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ActiveUsersUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activeUsersByRole;
    public $roleLabels;
    public $totalActive;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
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

        $this->activeUsersByRole = $activeUsersByRole;
        $this->roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'tenaga_pendidik' => 'Tenaga Pendidik',
        ];
        $this->totalActive = $activeUsersByRole->flatten(1)->count();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('active-users'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'active-users.updated';
    }
}
