<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMidtransCallbackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $skipSignature = app()->environment('local') || (bool) env('MIDTRANS_SKIP_SIGNATURE', false);
        if ($skipSignature) {
            return $next($request);
        }

        $settings = AppSetting::findOrFail(1);
        $serverKey = trim((string) $settings->midtrans_server_key);
        if ($serverKey === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Midtrans server key is not configured',
            ], 503);
        }

        $signature = hash('sha512',
            (string) $request->input('order_id', '') .
            (string) $request->input('status_code', '') .
            (string) $request->input('gross_amount', '') .
            $serverKey
        );

        if ((string) $request->input('signature_key', '') !== $signature) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 400);
        }

        return $next($request);
    }
}
