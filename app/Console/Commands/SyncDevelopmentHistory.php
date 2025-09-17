<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DevelopmentHistory;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SyncDevelopmentHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'development:sync-history {--force : Force sync all migrations regardless of existing entries}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically sync database migrations with development history';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting development history synchronization...');

        $migrationPath = database_path('migrations');
        $migrationFiles = File::files($migrationPath);
        $synced = 0;
        $skipped = 0;

        $this->info("Found " . count($migrationFiles) . " migration files");

        foreach ($migrationFiles as $file) {
            $filename = $file->getFilename();

            // Skip if already exists (unless force flag is used)
            if (!$this->option('force') && DevelopmentHistory::where('migration_file', $filename)->exists()) {
                $skipped++;
                continue;
            }

            // Parse migration filename
            $migrationData = $this->parseMigrationFile($filename);

            if ($migrationData) {
                if ($this->option('force')) {
                    // Delete existing entry if force flag is used
                    DevelopmentHistory::where('migration_file', $filename)->delete();
                }

                DevelopmentHistory::create($migrationData);
                $synced++;
                $this->line("Synced: {$filename}");
            } else {
                $this->warn("Could not parse: {$filename}");
            }
        }

        $this->info("Synchronization complete!");
        $this->info("Synced: {$synced} files");
        $this->info("Skipped: {$skipped} files (already exist)");

        return Command::SUCCESS;
    }

    /**
     * Parse migration file to extract information
     */
    private function parseMigrationFile($filename)
    {
        // Extract date from filename (format: YYYY_MM_DD_HHMMSS_description.php)
        if (!preg_match('/^(\d{4})_(\d{2})_(\d{2})_(\d{6})_(.+)\.php$/', $filename, $matches)) {
            return null;
        }

        $year = $matches[1];
        $month = $matches[2];
        $day = $matches[3];
        $time = $matches[4];
        $description = $matches[5];

        // Create date
        $date = Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-{$day}");

        // Parse description to get title and type
        $title = $this->generateTitleFromDescription($description);
        $type = $this->determineMigrationType($description);
        $detailedDescription = $this->generateDetailedDescription($description, $filename);

        return [
            'title' => $title,
            'description' => $detailedDescription,
            'type' => $type,
            'development_date' => $date,
            'migration_file' => $filename,
            'details' => [
                'original_description' => $description,
                'time' => $time,
                'synced_automatically' => true,
                'sync_date' => now()->toDateTimeString()
            ]
        ];
    }

    /**
     * Generate human-readable title from migration description
     */
    private function generateTitleFromDescription($description)
    {
        // Convert snake_case to Title Case
        $title = str_replace('_', ' ', $description);
        $title = ucwords($title);

        // Handle common patterns
        $patterns = [
            '/Create (.+) Table/' => 'Membuat Tabel $1',
            '/Add (.+) To (.+) Table/' => 'Menambah $1 ke Tabel $2',
            '/Update (.+) Table/' => 'Update Tabel $1',
            '/Drop (.+) From (.+) Table/' => 'Menghapus $1 dari Tabel $2',
            '/Make (.+) Nullable In (.+) Table/' => 'Membuat $1 Nullable di Tabel $2',
            '/Change (.+) To (.+) In (.+) Table/' => 'Mengubah $1 menjadi $2 di Tabel $3'
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $title, $matches)) {
                $result = $replacement;
                for ($i = 1; $i < count($matches); $i++) {
                    $result = str_replace('$' . $i, ucwords(str_replace('_', ' ', $matches[$i])), $result);
                }
                return $result;
            }
        }

        return $title;
    }

    /**
     * Determine migration type based on description
     */
    private function determineMigrationType($description)
    {
        if (strpos($description, 'create_') === 0) {
            return 'migration';
        } elseif (strpos($description, 'add_') === 0) {
            return 'enhancement';
        } elseif (strpos($description, 'update_') === 0 || strpos($description, 'change_') === 0) {
            return 'update';
        } elseif (strpos($description, 'drop_') === 0 || strpos($description, 'remove_') === 0) {
            return 'update';
        } else {
            return 'migration';
        }
    }

    /**
     * Generate detailed description
     */
    private function generateDetailedDescription($description, $filename)
    {
        $baseDescription = $this->generateTitleFromDescription($description);

        // Add context based on table names
        $context = '';
        if (strpos($description, 'users') !== false) {
            $context = ' - Sistem manajemen pengguna';
        } elseif (strpos($description, 'admin') !== false) {
            $context = ' - Sistem administrasi';
        } elseif (strpos($description, 'madrasah') !== false) {
            $context = ' - Sistem manajemen madrasah/sekolah';
        } elseif (strpos($description, 'tenaga_pendidik') !== false) {
            $context = ' - Sistem manajemen tenaga pendidik';
        } elseif (strpos($description, 'presensi') !== false) {
            $context = ' - Sistem presensi/absensi';
        } elseif (strpos($description, 'status_kepegawaian') !== false) {
            $context = ' - Sistem status kepegawaian';
        } elseif (strpos($description, 'tahun_pelajaran') !== false) {
            $context = ' - Sistem tahun pelajaran';
        } elseif (strpos($description, 'holiday') !== false) {
            $context = ' - Sistem manajemen hari libur';
        }

        return $baseDescription . $context;
    }
}
