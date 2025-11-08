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
        if (Carbon::parse($date)->dayOfWeek === Carbon::SUNDAY) {
            $this->info("Skipping Sunday");
            return;
        }

        // Get presensi settings
        $settings = PresensiSettings::first();
        $waktuAkhirPulang = $settings ? Carbon::parse($settings->waktu_akhir_presensi_pulang) : Carbon::parse('15:00');

        $this->info("Waktu akhir presensi pulang: {$waktuAkhirPulang->format('H:i')}");

        // Get all tenaga_pendidik users with their madrasah hari_kbm
        $tenagaPendidikUsers = User::where('role', 'tenaga_pendidik')
            ->with('madrasah:id,hari_kbm')
            ->get();

        $this->info("Found {$tenagaPendidikUsers->count()} tenaga_pendidik users");

        $absenceCount = 0;
        $updatedCount = 0;

        foreach ($tenagaPendidikUsers as $user) {
            // Determine if this date is a working day based on madrasah hari_kbm
            $hariKbm = $user->madrasah ? $user->madrasah->hari_kbm : '5'; // Default to 5 if not set
            $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday

            $isWorkingDay = false;
            if ($hariKbm == '5') {
                // 5 hari KBM: Monday to Friday (1-5)
                $isWorkingDay = in_array($dayOfWeek, [1, 2, 3, 4, 5]);
            } elseif ($hariKbm == '6') {
                // 6 hari KBM: Monday to Saturday (1-6)
                $isWorkingDay = in_array($dayOfWeek, [1, 2, 3, 4, 5, 6]);
            }

            // Skip if not a working day for this user's madrasah
            if (!$isWorkingDay) {
                $this->line("Skipping non-working day for: {$user->name} (hari_kbm: {$hariKbm})");
                continue;
            }

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
                    'keterangan' => 'Tidak presensi',
                    'status_kepegawaian_id' => $user->status_kepegawaian_id,
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
