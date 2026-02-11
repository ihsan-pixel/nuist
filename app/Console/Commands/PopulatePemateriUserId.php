<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TalentaPemateri;
use App\Models\User;

class PopulatePemateriUserId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-pemateri-user-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate user_id for existing talenta_pemateri records based on matching names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate user_id for talenta_pemateri records...');

        $pemateris = TalentaPemateri::whereNull('user_id')->get();
        $updatedCount = 0;

        foreach ($pemateris as $pemateri) {
            // Find user with matching name and role 'pemateri'
            $user = User::where('name', $pemateri->nama)
                       ->where('role', 'pemateri')
                       ->first();

            if ($user) {
                $pemateri->update(['user_id' => $user->id]);
                $updatedCount++;
                $this->line("Updated pemateri '{$pemateri->nama}' with user_id {$user->id}");
            } else {
                $this->warn("No matching user found for pemateri '{$pemateri->nama}'");
            }
        }

        $this->info("Completed! Updated {$updatedCount} records.");
    }
}
