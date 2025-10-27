<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommitLog;
use App\Models\DevelopmentHistory;
use Carbon\Carbon;

class GithubWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Validate webhook signature if configured
        if (!$this->validateWebhookSignature($request)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $commits = $request->input('commits', []);
        $processed = 0;

        foreach ($commits as $commit) {
            // Create commit log
            CommitLog::create([
                'hash' => $commit['id'],
                'message' => $commit['message'],
                'author' => $commit['author']['name'],
                'timestamp' => $commit['timestamp'],
            ]);

            // Create development history entry for commit
            $this->createDevelopmentHistoryFromCommit($commit);

            $processed++;
        }

        return response()->json([
            'status' => 'ok',
            'processed' => $processed,
            'message' => "Processed {$processed} commits"
        ]);
    }

    /**
     * Validate GitHub webhook signature
     */
    private function validateWebhookSignature(Request $request)
    {
        $secret = env('GITHUB_WEBHOOK_SECRET');
        if (!$secret) {
            // If no secret configured, skip validation
            return true;
        }

        $signature = $request->header('X-Hub-Signature-256');
        if (!$signature) {
            return false;
        }

        $payload = $request->getContent();
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Create development history entry from commit data
     */
    private function createDevelopmentHistoryFromCommit($commit)
    {
        $message = $commit['message'];
        $date = Carbon::parse($commit['timestamp']);

        // Skip if already exists
        if (DevelopmentHistory::where('details->commit_hash', $commit['id'])->exists()) {
            return;
        }

        // Determine type based on commit message
        $type = $this->determineCommitType($message);

        // Generate title from commit message
        $title = $this->generateTitleFromCommit($message);

        // Generate description
        $description = $this->generateDescriptionFromCommit($message, $commit);

        DevelopmentHistory::create([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'development_date' => $date,
            'details' => [
                'commit_hash' => $commit['id'],
                'commit_author' => $commit['author']['name'],
                'commit_message' => $message,
                'commit_url' => $commit['url'] ?? null,
                'webhook_processed' => true,
                'processed_at' => now()->toDateTimeString()
            ]
        ]);
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
        $description .= "\nAuthor: {$commit['author']['name']}";
        $description .= "\nHash: {$commit['id']}";

        if (isset($commit['url'])) {
            $description .= "\nURL: {$commit['url']}";
        }

        return $description;
    }
}
