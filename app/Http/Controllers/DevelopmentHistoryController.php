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

        // Filter by source (commits vs manual entries)
        if ($request->has('source') && $request->source !== '') {
            if ($request->source === 'commits') {
                $query->whereNotNull('details->commit_hash');
            } elseif ($request->source === 'manual') {
                $query->whereNull('details->commit_hash');
            }
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

        // Get source options
        $sources = [
            'all' => 'Semua',
            'commits' => 'Git Commits',
            'manual' => 'Manual Entry'
        ];

        return view('development-history.index', compact('histories', 'stats', 'types', 'sources'));
    }

    /**
     * Get development statistics
     */
    private function getStatistics()
    {
        $totalCommits = DevelopmentHistory::whereNotNull('details->commit_hash')->count();
        $totalManual = DevelopmentHistory::whereNull('details->commit_hash')->count();

        return [
            'total' => DevelopmentHistory::count(),
            'commits' => $totalCommits,
            'manual_entries' => $totalManual,
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
     * Run commit tracking command via AJAX for super admin
     */
    public function runCommitTracking(Request $request)
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            // Run the command
            $exitCode = \Artisan::call('development:track-commits', [
                '--since' => '1 week ago'
            ]);

            $output = \Artisan::output();

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commit tracking completed successfully',
                    'output' => $output
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Commit tracking failed',
                    'output' => $output
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error running commit tracking: ' . $e->getMessage()
            ]);
        }
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

    /**
     * Export development history to various formats
     */
    public function export(Request $request, $format)
    {
        // Check if user is super_admin or pengurus
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        $query = DevelopmentHistory::query();

        // Apply same filters as index
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->has('source') && $request->source !== '') {
            if ($request->source === 'commits') {
                $query->whereNotNull('details->commit_hash');
            } elseif ($request->source === 'manual') {
                $query->whereNull('details->commit_hash');
            }
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('development_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('development_date', '<=', $request->date_to);
        }

        $histories = $query->orderBy('development_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->get();

        $filename = 'riwayat_pengembangan_' . now()->format('Y-m-d_H-i-s');

        switch ($format) {
            case 'txt':
                return $this->exportTxt($histories, $filename . '.txt');
            case 'md':
                return $this->exportMarkdown($histories, $filename . '.md');
            case 'pdf':
                return $this->exportPdf($histories, $filename . '.pdf');
            case 'excel':
                return $this->exportExcel($histories, $filename . '.xlsx');
            default:
                abort(400, 'Unsupported export format');
        }
    }

    /**
     * Regenerate development history documentation files
     */
    public function regenerateDocumentation(Request $request)
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            // Run the documentation generation command
            $exitCode = \Artisan::call('development:generate-txt', [
                '--format' => 'txt'
            ]);

            $exitCode2 = \Artisan::call('development:generate-txt', [
                '--format' => 'todo',
                '--output' => 'riwayat_pengembangan.md'
            ]);

            if ($exitCode === 0 && $exitCode2 === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Documentation files regenerated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to regenerate documentation files'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error regenerating documentation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export to TXT format
     */
    private function exportTxt($histories, $filename)
    {
        $content = $this->generateTxtContent($histories);

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export to Markdown format
     */
    private function exportMarkdown($histories, $filename)
    {
        $content = $this->generateMarkdownContent($histories);

        return response($content)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export to PDF format
     */
    private function exportPdf($histories, $filename)
    {
        // For PDF export, we'll use a simple HTML to PDF conversion
        // In a real application, you might want to use libraries like dompdf or tcpdf
        $html = $this->generateHtmlContent($histories);

        // For now, return HTML that can be printed as PDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export to Excel format (CSV for simplicity)
     */
    private function exportExcel($histories, $filename)
    {
        $content = $this->generateCsvContent($histories);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate TXT content
     */
    private function generateTxtContent($histories)
    {
        $content = "RIWAYAT PENGEMBANGAN APLIKASI\n";
        $content .= "Generated: " . now()->format('d F Y H:i:s') . "\n";
        $content .= "Total Entries: " . $histories->count() . "\n\n";

        foreach ($histories as $history) {
            $content .= "================================\n";
            $content .= "Date: " . $history->formatted_date . "\n";
            $content .= "Type: " . ucfirst($history->type) . "\n";
            $content .= "Title: " . $history->title . "\n";
            $content .= "Description: " . $history->description . "\n";

            if ($history->version) {
                $content .= "Version: " . $history->version . "\n";
            }

            if ($history->migration_file) {
                $content .= "Migration: " . $history->migration_file . "\n";
            }

            if ($history->details && isset($history->details['commit_hash'])) {
                $content .= "Commit: " . substr($history->details['commit_hash'], 0, 7) . "\n";
                $content .= "Author: " . ($history->details['commit_author'] ?? 'Unknown') . "\n";
            }

            $content .= "\n";
        }

        return $content;
    }

    /**
     * Generate Markdown content
     */
    private function generateMarkdownContent($histories)
    {
        $content = "# Riwayat Pengembangan Aplikasi\n\n";
        $content .= "**Generated:** " . now()->format('d F Y H:i:s') . "\n";
        $content .= "**Total Entries:** " . $histories->count() . "\n\n";

        $groupedByDate = $histories->groupBy(function($item) {
            return $item->development_date->format('Y-m-d');
        });

        foreach ($groupedByDate as $date => $dayHistories) {
            $dateObj = Carbon::parse($date);
            $content .= "## " . $dateObj->format('d F Y') . "\n\n";

            foreach ($dayHistories as $history) {
                $content .= "### " . $history->title . "\n\n";
                $content .= "- **Type:** " . ucfirst($history->type) . "\n";
                $content .= "- **Description:** " . $history->description . "\n";

                if ($history->version) {
                    $content .= "- **Version:** " . $history->version . "\n";
                }

                if ($history->migration_file) {
                    $content .= "- **Migration:** `" . $history->migration_file . "`\n";
                }

                if ($history->details && isset($history->details['commit_hash'])) {
                    $content .= "- **Commit:** `" . substr($history->details['commit_hash'], 0, 7) . "`\n";
                    $content .= "- **Author:** " . ($history->details['commit_author'] ?? 'Unknown') . "\n";
                }

                $content .= "\n";
            }
        }

        return $content;
    }

    /**
     * Generate HTML content for PDF
     */
    private function generateHtmlContent($histories)
    {
        $html = "<!DOCTYPE html><html><head><title>Riwayat Pengembangan</title>";
        $html .= "<style>body{font-family:Arial,sans-serif;margin:20px;}";
        $html .= "h1{color:#333;} .entry{border:1px solid #ddd;padding:10px;margin:10px 0;}";
        $html .= ".date{color:#666;font-size:0.9em;} .type{background:#f0f0f0;padding:2px 5px;border-radius:3px;}</style>";
        $html .= "</head><body>";

        $html .= "<h1>Riwayat Pengembangan Aplikasi</h1>";
        $html .= "<p><strong>Generated:</strong> " . now()->format('d F Y H:i:s') . "</p>";
        $html .= "<p><strong>Total Entries:</strong> " . $histories->count() . "</p><hr>";

        foreach ($histories as $history) {
            $html .= "<div class='entry'>";
            $html .= "<h3>" . htmlspecialchars($history->title) . "</h3>";
            $html .= "<p class='date'>" . $history->formatted_date . " | <span class='type'>" . ucfirst($history->type) . "</span></p>";
            $html .= "<p>" . nl2br(htmlspecialchars($history->description)) . "</p>";

            if ($history->version) {
                $html .= "<p><strong>Version:</strong> " . htmlspecialchars($history->version) . "</p>";
            }

            if ($history->migration_file) {
                $html .= "<p><strong>Migration:</strong> <code>" . htmlspecialchars($history->migration_file) . "</code></p>";
            }

            if ($history->details && isset($history->details['commit_hash'])) {
                $html .= "<p><strong>Commit:</strong> <code>" . substr($history->details['commit_hash'], 0, 7) . "</code></p>";
                $html .= "<p><strong>Author:</strong> " . htmlspecialchars($history->details['commit_author'] ?? 'Unknown') . "</p>";
            }

            $html .= "</div>";
        }

        $html .= "</body></html>";
        return $html;
    }

    /**
     * Generate CSV content
     */
    private function generateCsvContent($histories)
    {
        $content = "Date,Type,Title,Description,Version,Migration,Commit,Author\n";

        foreach ($histories as $history) {
            $row = [
                $history->development_date->format('Y-m-d'),
                ucfirst($history->type),
                '"' . str_replace('"', '""', $history->title) . '"',
                '"' . str_replace('"', '""', $history->description) . '"',
                $history->version ?? '',
                $history->migration_file ?? '',
                isset($history->details['commit_hash']) ? substr($history->details['commit_hash'], 0, 7) : '',
                isset($history->details['commit_author']) ? $history->details['commit_author'] : ''
            ];

            $content .= implode(',', $row) . "\n";
        }

        return $content;
    }
}
