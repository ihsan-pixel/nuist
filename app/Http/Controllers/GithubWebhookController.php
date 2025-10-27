<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommitLog;

class GithubWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $commits = $request->input('commits', []);
        foreach ($commits as $commit) {
            CommitLog::create([
                'hash' => $commit['id'],
                'message' => $commit['message'],
                'author' => $commit['author']['name'],
                'timestamp' => $commit['timestamp'],
            ]);
        }
        return response()->json(['status' => 'ok']);
    }
}
