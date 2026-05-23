<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGithubWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.github.webhook_secret', env('GITHUB_WEBHOOK_SECRET'));

        if (! is_string($secret) || $secret === '') {
            return response()->json(['error' => 'Webhook secret is not configured'], 503);
        }

        $signature = $request->header('X-Hub-Signature-256');
        if (! is_string($signature) || $signature === '') {
            return response()->json(['error' => 'Missing signature'], 403);
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
        if (! hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        return $next($request);
    }
}
