<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Models\Presensi;
use App\Models\PresensiSettings;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoInsertAbsence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:auto-insert-absence {--date= : Date to process (Y-m-d format, defaults to yesterday)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically insert or update absence records for tenaga_pendidik users who did not check out by the deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::now('Asia/Jakarta')->subDay()->toDateString();

        $this->info("Processing absences for date: {$date}");

        // Skip if it's a holiday
        if (Holiday::isHoliday($date)) {
            $holiday = Holiday::getHoliday($date);
            $this->info("Skipping holiday: {$holiday->name}");
            return;
        }

        // Skip if it's Sunday (day 0 in Carbon)
        if ($date->dayOfWeek === Carbon::SUNDAY) {
            $this->info("Skipping Sunday");
            return;
        }

        // Get presensi settings
        $settings = PresensiSettings::first();
        $waktuAkhirPulang = $settings ? Carbon::parse($settings->waktu_akhir_presensi_pulang) : Carbon::parse('15:00');

        $this->info("Waktu akhir presensi pulang: {$waktuAkhirPulang->format('H:i')}");

        // Get all tenaga_pendidik users
        $tenagaPendidikUsers = User::where('role', 'tenaga_pendidik')->get();

        $this->info("Found {$tenagaPendidikUsers->count()} tenaga_pendidik users");

        $absenceCount = 0;
        $updatedCount = 0;

        foreach ($tenagaPendidikUsers as $user) {
            // Check if user already has presensi record for this date
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->where('tanggal', $date)
                ->first();

            if (!$existingPresensi) {
                // Insert absence record
                Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $date,
                    'status' => 'alpha',
                    'keterangan' => 'Tidak hadir',
                ]);

                $absenceCount++;
                $this->line("Inserted absence for: {$user->name}");
            } elseif ($existingPresensi->waktu_keluar === null) {
                // User checked in but not checked out, mark as alpha
                $existingPresensi->update([
                    'status' => 'alpha',
                    'keterangan' => 'Tidak melakukan presensi pulang',
                ]);

                $updatedCount++;
                $this->line("Updated absence for: {$user->name} (checked in but not out)");
            }
        }

        $this->info("Successfully inserted {$absenceCount} absence records and updated {$updatedCount} records");
    }
}
