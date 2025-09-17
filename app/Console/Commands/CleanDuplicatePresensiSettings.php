<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDuplicatePresensiSettings extends Command
{
    protected $signature = 'presensi:clean-duplicates';

    protected $description = 'Remove duplicate records from presensi_settings table, keeping only the first record';

    public function handle()
    {
        $this->info('Starting to clean duplicate presensi_settings records...');

        $idsToKeep = DB::table('presensi_settings')->orderBy('id')->limit(1)->pluck('id')->toArray();

        $deleted = DB::table('presensi_settings')
            ->whereNotIn('id', $idsToKeep)
            ->delete();

        $this->info("Deleted {$deleted} duplicate records.");

        return 0;
    }
}
