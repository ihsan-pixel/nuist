<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBniVaCallbackToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $callbackToken = AppSetting::getSettings()->bni_va_callback_token;

        if (! is_string($callbackToken) || $callbackToken === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Callback token is not configured.',
            ], 503);
        }

        if ($request->header('X-Callback-Token') !== $callbackToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized callback token.',
            ], 401);
        }

        return $next($request);
    }
}
