<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DevelopmentHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class GenerateDevelopmentHistoryTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'development:generate-txt {--output=riwayat_pengembangan.txt : Output filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate development history as text file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating development history text file...');

        try {
            // Try to get data from database first (for deployed/production environment)
            $histories = DevelopmentHistory::orderBy('development_date', 'desc')
                                          ->orderBy('created_at', 'desc')
                                          ->get();

            if ($histories->isEmpty()) {
                $this->warn('No development history found in database, generating from static data...');
                $histories = $this->getStaticHistoryData();
            }
        } catch (\Exception $e) {
            // If database connection fails (local development), use static data
            $this->warn('Database connection failed, generating from static data...');
            $histories = $this->getStaticHistoryData();
        }

        if ($histories->isEmpty()) {
            $this->warn('No development history data available.');
            return Command::FAILURE;
        }

        $content = $this->generateContent($histories);
        $filename = $this->option('output');

        // Write to file in project root
        File::put($filename, $content);

        $this->info("Development history exported to: {$filename}");
        $this->info("Total entries: " . $histories->count());

        return Command::SUCCESS;
    }

    /**
     * Generate the text content
     */
    private function generateContent($histories)
    {
        $firstDate = $histories->last()->development_date;
        $lastDate = $histories->first()->development_date;

        $content = $this->generateHeader($firstDate, $lastDate);
        $content .= $this->generateSummary($histories);
        $content .= $this->generateDetailedHistory($histories);
        $content .= $this->generateStatistics($histories);
        $content .= $this->generateFooter();

        return $content;
    }

    /**
     * Generate header section
     */
    private function generateHeader($firstDate, $lastDate)
    {
        $header = "================================================================================\n";
        $header .= "                    RIWAYAT PENGEMBANGAN BLACKBOX AI\n";
        $header .= "                    " . $lastDate->format('d F Y') . " - " . $firstDate->format('d F Y') . "\n";
        $header .= "================================================================================\n\n";

        $header .= "PERIODE: " . $lastDate->format('d F Y') . " - " . $firstDate->format('d F Y') . "\n";
        $header .= "PROYEK: Sistem Manajemen Madrasah/Presensi (Laravel Application)\n";
        $header .= "LOKASI: " . base_path() . "\n\n";

        return $header;
    }

    /**
     * Generate summary section
     */
    private function generateSummary($histories)
    {
        $summary = "================================================================================\n";
        $summary .= "RINGKASAN UMUM\n";
        $summary .= "================================================================================\n\n";

        $totalDays = $histories->first()->development_date->diffInDays($histories->last()->development_date) + 1;
        $avgPerDay = round($histories->count() / $totalDays, 2);

        $summary .= "Total pengembangan: {$histories->count()} entries dalam {$totalDays} hari\n";
        $summary .= "Rata-rata: {$avgPerDay} entries per hari\n\n";

        // Group by type
        $types = $histories->groupBy('type');
        $summary .= "Breakdown berdasarkan tipe:\n";
        foreach ($types as $type => $entries) {
            $summary .= "- " . ucfirst($type) . ": {$entries->count()} entries\n";
        }

        $summary .= "\n================================================================================\n";
        $summary .= "RIWAYAT PENGEMBANGAN LENGKAP\n";
        $summary .= "================================================================================\n\n";

        return $summary;
    }

    /**
     * Generate detailed history grouped by date
     */
    private function generateDetailedHistory($histories)
    {
        $groupedByDate = $histories->groupBy(function($item) {
            return $item->development_date->format('Y-m-d');
        });

        $content = "";

        foreach ($groupedByDate as $date => $dayHistories) {
            $dateObj = Carbon::parse($date);
            $content .= "TANGGAL " . $dateObj->format('d F Y') . "\n";
            $content .= str_repeat("-", 50) . "\n";

            foreach ($dayHistories as $history) {
                $content .= $this->formatHistoryEntry($history);
            }

            $content .= "\n";
        }

        return $content;
    }

    /**
     * Format single history entry
     */
    private function formatHistoryEntry($history)
    {
        $entry = "• {$history->title}\n";
        $entry .= "  Tipe: " . ucfirst($history->type) . "\n";
        $entry .= "  Deskripsi: {$history->description}\n";

        if ($history->migration_file) {
            $entry .= "  File: {$history->migration_file}\n";
        }

        if ($history->version) {
            $entry .= "  Versi: {$history->version}\n";
        }

        if ($history->details && is_array($history->details)) {
            if (isset($history->details['commit_hash'])) {
                $entry .= "  Commit: {$history->details['commit_hash']}\n";
                $entry .= "  Author: {$history->details['commit_author']}\n";
            }
        }

        $entry .= "\n";

        return $entry;
    }

    /**
     * Generate statistics section
     */
    private function generateStatistics($histories)
    {
        $stats = "================================================================================\n";
        $stats .= "STATISTIK PENGEMBANGAN\n";
        $stats .= "================================================================================\n\n";

        $stats .= "TOTAL ENTRIES: {$histories->count()}\n\n";

        $typeStats = $histories->groupBy('type');
        $stats .= "BERDASARKAN TIPE:\n";
        foreach ($typeStats as $type => $entries) {
            $percentage = round(($entries->count() / $histories->count()) * 100, 1);
            $stats .= "- " . ucfirst($type) . ": {$entries->count()} ({$percentage}%)\n";
        }

        $stats .= "\nBERDASARKAN TAHUN:\n";
        $yearStats = $histories->groupBy(function($item) {
            return $item->development_date->year;
        });
        foreach ($yearStats as $year => $entries) {
            $stats .= "- {$year}: {$entries->count()} entries\n";
        }

        $stats .= "\nTANGGAL PERTAMA: " . $histories->last()->development_date->format('d F Y') . "\n";
        $stats .= "TANGGAL TERAKHIR: " . $histories->first()->development_date->format('d F Y') . "\n";
        $stats .= "STATUS APLIKASI: " . (env('APP_ENV') === 'production' ? 'Production/Deployed' : 'Development') . "\n";

        return $stats;
    }

    /**
     * Generate footer
     */
    private function generateFooter()
    {
        $footer = "\n================================================================================\n";
        $footer .= "END OF DEVELOPMENT HISTORY - Generated on " . now()->format('d F Y H:i:s') . "\n";
        $footer .= "================================================================================\n";

        return $footer;
    }

    /**
     * Get static history data when database is not available
     */
    private function getStaticHistoryData()
    {
        $histories = collect([
            // Laravel Foundation (2014-2019)
            (object)[
                'title' => 'Instalasi Framework Laravel',
                'description' => 'Instalasi awal framework Laravel dengan sistem autentikasi dasar, manajemen user, dan reset password',
                'type' => 'migration',
                'development_date' => Carbon::create(2014, 10, 12),
                'migration_file' => '2014_10_12_000000_create_users_table.php',
                'version' => null,
                'details' => ['phase' => 'foundation', 'category' => 'authentication']
            ],
            (object)[
                'title' => 'Sistem Reset Password',
                'description' => 'Implementasi sistem reset password untuk keamanan akun pengguna',
                'type' => 'feature',
                'development_date' => Carbon::create(2014, 10, 12),
                'migration_file' => '2014_10_12_100000_create_password_resets_table.php',
                'version' => null,
                'details' => ['phase' => 'foundation', 'category' => 'security']
            ],
            (object)[
                'title' => 'Sistem Queue dan Job Management',
                'description' => 'Implementasi sistem antrian untuk menangani tugas-tugas background dan failed jobs',
                'type' => 'feature',
                'development_date' => Carbon::create(2019, 8, 19),
                'migration_file' => '2019_08_19_000000_create_failed_jobs_table.php',
                'version' => null,
                'details' => ['phase' => 'foundation', 'category' => 'performance']
            ],
            (object)[
                'title' => 'API Authentication dengan Sanctum',
                'description' => 'Implementasi Laravel Sanctum untuk autentikasi API dan personal access tokens',
                'type' => 'feature',
                'development_date' => Carbon::create(2019, 12, 14),
                'migration_file' => '2019_12_14_000001_create_personal_access_tokens_table.php',
                'version' => null,
                'details' => ['phase' => 'foundation', 'category' => 'api']
            ],
            // Customer Module (2023)
            (object)[
                'title' => 'Modul Manajemen Customer',
                'description' => 'Pengembangan sistem manajemen customer untuk kebutuhan bisnis',
                'type' => 'feature',
                'development_date' => Carbon::create(2023, 7, 10),
                'migration_file' => '2023_07_10_112535_create_customers_table.php',
                'version' => null,
                'details' => ['phase' => 'customer_module', 'category' => 'business_logic']
            ],
            // Major Development (September 2025)
            (object)[
                'title' => 'Sistem Administrasi dan Role Management',
                'description' => 'Implementasi sistem administrasi lengkap dengan manajemen role dan permissions',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_004646_create_admins_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'administration']
            ],
            (object)[
                'title' => 'Manajemen Madrasah/Sekolah',
                'description' => 'Pengembangan sistem manajemen madrasah dengan fitur lengkap',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_070221_create_madrasahs_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'school_management']
            ],
            (object)[
                'title' => 'Sistem Manajemen Tenaga Pendidik',
                'description' => 'Implementasi sistem manajemen tenaga pendidik dengan data lengkap',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_074634_create_tenaga_pendidiks_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'teacher_management']
            ],
            (object)[
                'title' => 'Sistem Presensi Digital',
                'description' => 'Pengembangan sistem presensi dengan tracking lokasi dan waktu',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 8),
                'migration_file' => '2025_09_08_000001_create_presensis_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'attendance_system']
            ],
            (object)[
                'title' => 'Integrasi Lokasi dan Geo-fencing',
                'description' => 'Implementasi sistem lokasi dengan koordinat GPS dan polygon area',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 8),
                'migration_file' => '2025_09_08_120000_add_latitude_longitude_to_madrasahs_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'location_integration']
            ],
            (object)[
                'title' => 'Sistem Manajemen Hari Libur',
                'description' => 'Implementasi sistem manajemen hari libur untuk kalkulasi presensi yang akurat',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 14),
                'migration_file' => '2025_09_14_000000_create_holidays_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'calendar_management']
            ],
            (object)[
                'title' => 'Sistem Izin dan Approval',
                'description' => 'Pengembangan sistem permohonan izin dengan workflow approval',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 18),
                'migration_file' => '2025_09_18_031928_add_izin_fields_to_presensis_table.php',
                'version' => null,
                'details' => ['phase' => 'major_development', 'category' => 'approval_workflow']
            ],
            // October 2025 Updates
            (object)[
                'title' => 'Penambahan Role Pengurus',
                'description' => 'Menambahkan role pengurus ke dalam sistem manajemen pengguna',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 10, 1),
                'migration_file' => '2025_10_01_060238_add_pengurus_to_users_role_enum.php',
                'version' => null,
                'details' => ['phase' => 'october_updates', 'category' => 'role_management']
            ],
            (object)[
                'title' => 'Optimasi Sistem Presensi',
                'description' => 'Reorganisasi sistem presensi berdasarkan status kepegawaian dengan konfigurasi per role',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 10, 3),
                'migration_file' => '2025_10_03_165952_add_status_kepegawaian_id_to_presensi_settings_table.php',
                'version' => null,
                'details' => ['phase' => 'october_updates', 'category' => 'attendance_optimization']
            ],
            (object)[
                'title' => 'Field Jabatan dan Beban Kerja',
                'description' => 'Penambahan field jabatan dan tracking beban kerja tambahan untuk tenaga pendidik',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 10, 4),
                'migration_file' => '2025_10_04_103843_add_pemenuhan_beban_kerja_lain_to_users_table.php',
                'version' => null,
                'details' => ['phase' => 'october_updates', 'category' => 'workload_tracking']
            ]
        ]);

        return $histories;
    }
}
