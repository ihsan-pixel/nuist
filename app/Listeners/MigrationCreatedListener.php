<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\DevelopmentHistory;
use Illuminate\Support\Facades\Artisan;

class MigrationCreatedListener
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
        // Note: Laravel doesn't have built-in migration creation events
        // This listener can be used for other events or manual triggering

        // For now, we'll rely on scheduled commands for automatic sync
        // This method can be called manually when migrations are created

        try {
            // Run the sync command
            Artisan::call('development:sync-history');

            // Log the automatic sync
            \Log::info('Development history automatically synced after migration creation');
        } catch (\Exception $e) {
            \Log::error('Failed to auto-sync development history: ' . $e->getMessage());
        }
    }
}
