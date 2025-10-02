<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevelopmentHistory;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DevelopmentHistoryController extends Controller
{
    /**
     * Display development history timeline
     */
    public function index(Request $request)
    {
        // Check if user is super_admin or pengurus
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        $query = DevelopmentHistory::query();

        // Filter by type if provided
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('development_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('development_date', '<=', $request->date_to);
        }

        // Get filtered results
        $histories = $query->orderBy('development_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        // Get statistics
        $stats = $this->getStatistics();

        // Get available types for filter
        $types = [
            'migration' => 'Database Migration',
            'feature' => 'New Feature',
            'update' => 'Update',
            'bugfix' => 'Bug Fix',
            'enhancement' => 'Enhancement'
        ];

        return view('development-history.index', compact('histories', 'stats', 'types'));
    }

    /**
     * Get development statistics
     */
    private function getStatistics()
    {
        return [
            'total' => DevelopmentHistory::count(),
            'migrations' => DevelopmentHistory::where('type', 'migration')->count(),
            'features' => DevelopmentHistory::where('type', 'feature')->count(),
            'updates' => DevelopmentHistory::where('type', 'update')->count(),
            'bugfixes' => DevelopmentHistory::where('type', 'bugfix')->count(),
            'enhancements' => DevelopmentHistory::where('type', 'enhancement')->count(),
            'latest_date' => DevelopmentHistory::max('development_date'),
            'first_date' => DevelopmentHistory::min('development_date')
        ];
    }

    /**
     * Sync migration files with development history
     * This method can be called to automatically populate history from migration files
     */
    public function syncMigrations()
    {
        // Check if user is super_admin
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        $migrationPath = database_path('migrations');
        $migrationFiles = File::files($migrationPath);
        $synced = 0;

        foreach ($migrationFiles as $file) {
            $filename = $file->getFilename();

            // Skip if already exists
            if (DevelopmentHistory::where('migration_file', $filename)->exists()) {
                continue;
            }

            // Parse migration filename
            $migrationData = $this->parseMigrationFile($filename);

            if ($migrationData) {
                DevelopmentHistory::create($migrationData);
                $synced++;
            }
        }

        return redirect()->route('development-history.index')
                        ->with('success', "Successfully synced {$synced} migration files to development history.");
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
                'time' => $time
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
