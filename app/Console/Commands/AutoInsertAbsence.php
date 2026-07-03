<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use App\Models\PresensiSettings;
use App\Models\User;
use App\Services\AttendanceObligationService;
use App\Services\ExternalTeachingPermissionService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoInsertAbsence extends Command
{
    public function __construct(private AttendanceObligationService $attendanceObligationService)
    {
        parent::__construct();
    }

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
        $externalTeachingCount = 0;

        foreach ($tenagaPendidikUsers as $user) {
            $obligationStatus = $this->attendanceObligationService->statusForDate($user, $date);
            if (!$this->attendanceObligationService->hasAttendanceObligation($user, $date)) {
                $reason = $obligationStatus === AttendanceObligationService::STATUS_NOT_REQUIRED_PICKET_PERIOD
                    ? 'di luar jadwal piket'
                    : 'bukan hari wajib presensi';
                $this->line("Skipping {$reason} for: {$user->name}");
                continue;
            }

            // Check if user already has presensi record for this date
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->where('tanggal', $date)
                ->first();

            if (!$existingPresensi) {
                if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($user, $date)) {
                    ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($user, $date);
                    $externalTeachingCount++;
                    $this->line("Inserted approved external teaching note for: {$user->name}");
                    continue;
                }

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
            } elseif (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($user, $date)
                && $existingPresensi->status === 'alpha'
                && $existingPresensi->waktu_masuk === null
                && $existingPresensi->waktu_keluar === null) {
                ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($user, $date);
                $externalTeachingCount++;
                $this->line("Updated alpha to approved external teaching note for: {$user->name}");
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

        $this->info("Successfully inserted {$absenceCount} absence records, updated {$updatedCount} records, and recorded {$externalTeachingCount} external teaching permissions");
    }
}
