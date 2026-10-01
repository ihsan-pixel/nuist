<?php

namespace App\Http\Middleware;

use App\Services\FirebaseAppCheckService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class VerifyFirebaseAppCheck
{
    public function __construct(private readonly FirebaseAppCheckService $appCheck) {}

    public function handle(Request $request, Closure $next): Response
    {
        $mode = strtolower((string) config('firebase.app_check.mode', 'monitor'));
        if ($mode === 'disabled') {
            return $next($request);
        }

        // A configuration typo must never silently disable enforcement.
        if (! in_array($mode, ['monitor', 'enforce'], true)) {
            $mode = 'enforce';
        }

        $token = trim((string) $request->header('X-Firebase-AppCheck', ''));

        try {
            if ($token === '') {
                throw new \RuntimeException('Firebase App Check token is missing.');
            }

            $claims = $this->appCheck->verify($token);
            $request->attributes->set('firebase_app_id', $claims['sub']);
        } catch (Throwable $exception) {
            Log::warning('Firebase App Check verification failed.', [
                'mode' => $mode,
                'reason' => $exception->getMessage(),
                'route' => $request->route()?->getName(),
                'path' => $request->path(),
                'user_id' => $request->user()?->getAuthIdentifier(),
            ]);

            if ($mode === 'enforce') {
                return response()->json([
                    'message' => 'Perangkat atau aplikasi tidak dapat diverifikasi.',
                    'code' => 'firebase_app_check_failed',
                ], 401);
            }
        }

        return $next($request);
    }
}
