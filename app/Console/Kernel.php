<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\AutoInsertAbsence::class,
        \App\Console\Commands\SyncDevelopmentHistory::class,
        \App\Console\Commands\TrackGitCommits::class,
        \App\Console\Commands\DetectTalentaPenilaianConstraints::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Auto insert absence records daily at 23:00
        $schedule->command('presensi:auto-insert-absence')
            ->dailyAt('23:00')
            ->withoutOverlapping();

        // Sync development history daily at 01:00
        $schedule->command('development:sync-history')
            ->dailyAt('01:00')
            ->withoutOverlapping();

        // Track Git commits daily at 01:30
        $schedule->command('development:track-commits')
            ->dailyAt('01:30')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
