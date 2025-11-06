<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ActiveUsersUpdated;

class BroadcastActiveUsersUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // Broadcast the active users update when a user logs out
        broadcast(new ActiveUsersUpdated())->toOthers();
    }
}
