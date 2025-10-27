<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DevelopmentHistory;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TrackGitCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'development:track-commits {--since=1 week ago : Track commits since this date} {--force : Force re-sync all commits} {--webhook : Run in webhook mode (less verbose)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track Git commits and create development history entries';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if git is available
        if (!$this->isGitAvailable()) {
            $this->error('Git is not available in this environment.');
            return Command::FAILURE;
        }

        // Check if we're in a git repository
        if (!$this->isGitRepository()) {
            $this->error('Current directory is not a Git repository.');
            return Command::FAILURE;
        }

        if (!$this->option('webhook')) {
            $this->info('Starting Git commit tracking...');
        }

        $since = $this->option('since');
        $commits = $this->getGitCommits($since);

        if (empty($commits)) {
            if (!$this->option('webhook')) {
                $this->info('No new commits found since ' . $since);
            }
            return Command::SUCCESS;
        }

        if (!$this->option('webhook')) {
            $this->info("Found " . count($commits) . " commits");
        }

        $tracked = 0;
        $skipped = 0;

        foreach ($commits as $commit) {
            // Check if commit already tracked (unless force flag is used)
            if (!$this->option('force') && DevelopmentHistory::where('details->commit_hash', $commit['hash'])->exists()) {
                $skipped++;
                continue;
            }

            $historyData = $this->parseCommitData($commit);

            if ($historyData) {
                if ($this->option('force')) {
                    // Delete existing entry if force flag is used
                    DevelopmentHistory::where('details->commit_hash', $commit['hash'])->delete();
                }

                DevelopmentHistory::create($historyData);
                $tracked++;
                if (!$this->option('webhook')) {
                    $this->line("Tracked: {$commit['hash']} - {$commit['message']}");
                }
            }
        }

        if (!$this->option('webhook')) {
            $this->info("Git commit tracking complete!");
            $this->info("Tracked: {$tracked} commits");
            $this->info("Skipped: {$skipped} commits (already tracked)");
        }

        // Auto-generate development history files after tracking commits
        if ($tracked > 0) {
            $this->info("Auto-generating development history files...");
            $this->call('development:generate-txt', ['--format' => 'txt']);
            $this->call('development:generate-txt', ['--format' => 'todo', '--output' => 'riwayat_pengembangan.md']);
        }

        return Command::SUCCESS;
    }

    /**
     * Check if Git is available
     */
    private function isGitAvailable()
    {
        try {
            $process = new Process(['git', '--version']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if current directory is a Git repository
     */
    private function isGitRepository()
    {
        try {
            $process = new Process(['git', 'rev-parse', '--git-dir']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Git commits since specified date
     */
    private function getGitCommits($since)
    {
        try {
            // Get commits with format: hash|date|author|message
            $process = new Process([
                'git', 'log',
                '--since="' . $since . '"',
                '--pretty=format:%H|%ad|%an|%s',
                '--date=iso',
                '--no-merges'
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = trim($process->getOutput());
            if (empty($output)) {
                return [];
            }

            $commits = [];
            $lines = explode("\n", $output);

            foreach ($lines as $line) {
                $parts = explode('|', $line, 4);
                if (count($parts) === 4) {
                    $commits[] = [
                        'hash' => $parts[0],
                        'date' => $parts[1],
                        'author' => $parts[2],
                        'message' => $parts[3]
                    ];
                }
            }

            return $commits;
        } catch (\Exception $e) {
            $this->error('Failed to get Git commits: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parse commit data to create history entry
     */
    private function parseCommitData($commit)
    {
        $message = $commit['message'];
        $date = Carbon::parse($commit['date']);

        // Determine type based on commit message
        $type = $this->determineCommitType($message);

        // Generate title from commit message
        $title = $this->generateTitleFromCommit($message);

        // Generate description
        $description = $this->generateDescriptionFromCommit($message, $commit);

        return [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'development_date' => $date,
            'details' => [
                'commit_hash' => $commit['hash'],
                'commit_author' => $commit['author'],
                'commit_message' => $message,
                'tracked_automatically' => true,
                'track_date' => now()->toDateTimeString()
            ]
        ];
    }

    /**
     * Determine commit type based on message
     */
    private function determineCommitType($message)
    {
        $message = strtolower($message);

        if (strpos($message, 'fix') !== false || strpos($message, 'bug') !== false) {
            return 'bugfix';
        } elseif (strpos($message, 'add') !== false || strpos($message, 'new') !== false || strpos($message, 'create') !== false) {
            return 'feature';
        } elseif (strpos($message, 'update') !== false || strpos($message, 'change') !== false || strpos($message, 'modify') !== false) {
            return 'update';
        } elseif (strpos($message, 'improve') !== false || strpos($message, 'enhance') !== false || strpos($message, 'optimize') !== false) {
            return 'enhancement';
        } else {
            return 'update';
        }
    }

    /**
     * Generate title from commit message
     */
    private function generateTitleFromCommit($message)
    {
        // Clean up common prefixes
        $cleanMessage = preg_replace('/^(feat|fix|docs|style|refactor|test|chore):\s*/i', '', $message);

        // Capitalize first letter
        $title = ucfirst($cleanMessage);

        // Truncate if too long
        if (strlen($title) > 100) {
            $title = substr($title, 0, 97) . '...';
        }

        return $title;
    }

    /**
     * Generate description from commit data
     */
    private function generateDescriptionFromCommit($message, $commit)
    {
        $description = "Commit: {$message}";
        $description .= "\nAuthor: {$commit['author']}";
        $description .= "\nHash: {$commit['hash']}";

        return $description;
    }
}
