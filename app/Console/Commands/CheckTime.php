<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckTime extends Command
{
    protected $signature = 'check:time';
    protected $description = 'Cek waktu server & timezone Laravel';

    public function handle()
    {
        $this->info('Laravel timezone: ' . config('app.timezone'));
        $this->info('Server time (PHP): ' . date('Y-m-d H:i:s'));
        $this->info('Carbon now: ' . Carbon::now());
    }
}
