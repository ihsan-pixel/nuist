<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\SppSiswaBill;
use App\Services\BniVirtualAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SppSiswaPaymentController extends Controller
{
    public function generateBniVa(SppSiswaBill $bill, BniVirtualAccountService $service): RedirectResponse
    {
        $this->ensureMadrasahAccess($bill);

        try {
            $transaction = $service->createOrReuseForBill($bill, auth()->id());

            return back()->with('success', 'Virtual Account BNI siap digunakan. Nomor VA: ' . $transaction->va_number);
        } catch (\Throwable $throwable) {
            return back()->withErrors([
                'bni_va' => $throwable->getMessage(),
            ]);
        }
    }

    public function callback(Request $request, BniVirtualAccountService $service): JsonResponse
    {
        try {
            $callbackToken = AppSetting::getSettings()->bni_va_callback_token;
            if ($callbackToken && $request->header('X-Callback-Token') !== $callbackToken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized callback token.',
                ], 401);
            }

            $transaction = $service->handleCallback($request->all());

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Callback BNI VA diproses.',
                'transaction_id' => $transaction->id,
            ]);
        } catch (\Throwable $throwable) {
            Log::error('BNI VA callback gagal', [
                'message' => $throwable->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
            ], 500);
        }
    }

    private function ensureMadrasahAccess(SppSiswaBill $bill): void
    {
        $user = auth()->user();

        abort_if($user->role === 'admin' && (int) $user->madrasah_id !== (int) $bill->madrasah_id, 403);
    }
}
