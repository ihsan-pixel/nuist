<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class BniVirtualAccountService
{
    public function createOrReuseForBill(SppSiswaBill $bill, ?int $createdBy = null): SppSiswaTransaction
    {
        $bill->loadMissing(['siswa', 'madrasah', 'setting', 'transactions']);

        $this->ensureBniVaIsConfigured();

        $remainingAmount = (int) round($bill->outstanding_amount);

        if ($remainingAmount <= 0) {
            throw new RuntimeException('Tagihan ini sudah lunas dan tidak memerlukan Virtual Account baru.');
        }

        $existingTransaction = SppSiswaTransaction::query()
            ->where('bill_id', $bill->id)
            ->where('payment_channel', 'bni_va')
            ->where('status_verifikasi', 'menunggu')
            ->whereNotNull('va_number')
            ->where(function ($query) {
                $query->whereNull('va_expired_at')
                    ->orWhere('va_expired_at', '>', now());
            })
            ->latest('id')
            ->first();

        if ($existingTransaction) {
            return $existingTransaction;
        }

        $payload = $this->buildCreateVaPayload($bill, $remainingAmount);
        $response = $this->requestVirtualAccount($payload, $bill);

        return DB::transaction(function () use ($bill, $createdBy, $remainingAmount, $response) {
            return SppSiswaTransaction::create([
                'bill_id' => $bill->id,
                'siswa_id' => $bill->siswa_id,
                'madrasah_id' => $bill->madrasah_id,
                'nomor_transaksi' => $this->generateTransactionNumber($bill),
                'external_order_id' => $response['external_order_id'],
                'external_transaction_id' => $response['external_transaction_id'] ?? null,
                'tanggal_bayar' => today(),
                'nominal_bayar' => $remainingAmount,
                'metode_pembayaran' => 'virtual_account',
                'payment_channel' => 'bni_va',
                'va_number' => $response['va_number'],
                'va_expired_at' => $response['va_expired_at'] ?? null,
                'status_verifikasi' => 'menunggu',
                'keterangan' => 'BNI Virtual Account dibuat dan menunggu pembayaran.',
                'payment_payload' => $response['payload'] ?? null,
                'created_by' => $createdBy,
            ]);
        });
    }

    public function handleCallback(array $payload): ?SppSiswaTransaction
    {
        $normalized = $this->normalizeCallbackPayload($payload);

        $transaction = SppSiswaTransaction::query()
            ->where('external_order_id', $normalized['external_order_id'])
            ->first();

        if (!$transaction) {
            return null;
        }

        $status = $this->mapCallbackStatus($normalized['status']);

        DB::transaction(function () use ($transaction, $normalized, $status) {
            $transaction->update([
                'external_transaction_id' => $normalized['external_transaction_id'] ?? $transaction->external_transaction_id,
                'status_verifikasi' => $status,
                'tanggal_bayar' => $normalized['paid_at'] ? Carbon::parse($normalized['paid_at'])->toDateString() : $transaction->tanggal_bayar,
                'payment_payload' => $normalized['payload'],
                'keterangan' => $status === 'diverifikasi'
                    ? 'Pembayaran Virtual Account BNI berhasil diverifikasi.'
                    : ($status === 'ditolak' ? 'Pembayaran Virtual Account BNI gagal / ditolak.' : $transaction->keterangan),
            ]);

            $this->syncBillStatus($transaction->bill);
        });

        return $transaction->fresh(['bill', 'siswa']);
    }

    public function buildCreateVaPayload(SppSiswaBill $bill, int $grossAmount): array
    {
        $settings = AppSetting::getSettings();
        $expiredHours = max(1, (int) ($bill->setting?->va_expired_hours ?? 24));

        return [
            'merchant_id' => $settings->bni_va_merchant_id,
            'order_id' => $this->generateOrderId($bill),
            'student' => [
                'id' => $bill->siswa->id,
                'nis' => $bill->siswa->nis,
                'name' => $bill->siswa->nama_lengkap,
                'email' => $bill->siswa->email,
                'phone' => $bill->siswa->no_hp,
            ],
            'bill' => [
                'id' => $bill->id,
                'nomor_tagihan' => $bill->nomor_tagihan,
                'jenis_tagihan' => $bill->jenis_tagihan ?? 'SPP',
                'periode' => $bill->periode,
                'gross_amount' => $grossAmount,
                'expired_at' => now()->addHours($expiredHours)->toIso8601String(),
            ],
            'school' => [
                'id' => $bill->madrasah?->id,
                'name' => $bill->madrasah?->name,
            ],
        ];
    }

    protected function requestVirtualAccount(array $payload, SppSiswaBill $bill): array
    {
        $settings = AppSetting::getSettings();

        if ($settings->bni_va_mock_mode) {
            return $this->mockVirtualAccountResponse($payload, $bill);
        }

        throw new RuntimeException('Implementasi API BNI Virtual Account belum diisi. Lengkapi method requestVirtualAccount() sesuai spesifikasi kerja sama BNI.');
    }

    protected function mockVirtualAccountResponse(array $payload, SppSiswaBill $bill): array
    {
        $settings = AppSetting::getSettings();
        $prefix = preg_replace('/\D+/', '', (string) ($settings->bni_va_prefix ?: '9888'));
        $studentPart = str_pad((string) $bill->siswa_id, 6, '0', STR_PAD_LEFT);
        $billPart = str_pad((string) $bill->id, 6, '0', STR_PAD_LEFT);
        $vaNumber = substr($prefix . $studentPart . $billPart, 0, 20);

        return [
            'external_order_id' => $payload['order_id'],
            'external_transaction_id' => 'MOCK-' . Str::upper(Str::random(10)),
            'va_number' => $vaNumber,
            'va_expired_at' => Carbon::parse($payload['bill']['expired_at']),
            'payload' => [
                'mode' => 'mock',
                'provider' => 'bni_va',
                'request' => $payload,
                'response' => [
                    'va_number' => $vaNumber,
                    'status' => 'PENDING',
                ],
            ],
        ];
    }

    private function normalizeCallbackPayload(array $payload): array
    {
        $externalOrderId = $payload['external_order_id']
            ?? $payload['order_id']
            ?? null;

        if (!$externalOrderId) {
            throw new RuntimeException('Payload callback BNI VA tidak memiliki external_order_id / order_id.');
        }

        return [
            'external_order_id' => $externalOrderId,
            'external_transaction_id' => $payload['external_transaction_id'] ?? $payload['transaction_id'] ?? null,
            'status' => strtolower((string) ($payload['status'] ?? $payload['payment_status'] ?? 'pending')),
            'paid_at' => $payload['paid_at'] ?? $payload['payment_time'] ?? null,
            'payload' => $payload,
        ];
    }

    private function mapCallbackStatus(string $status): string
    {
        return match ($status) {
            'paid', 'success', 'settlement', 'completed' => 'diverifikasi',
            'failed', 'cancelled', 'expired', 'rejected' => 'ditolak',
            default => 'menunggu',
        };
    }

    private function syncBillStatus(?SppSiswaBill $bill): void
    {
        if (!$bill) {
            return;
        }

        $verifiedAmount = (float) $bill->transactions()
            ->where('status_verifikasi', 'diverifikasi')
            ->sum('nominal_bayar');

        $status = 'belum_lunas';

        if ($verifiedAmount >= (float) $bill->total_tagihan) {
            $status = 'lunas';
        } elseif ($verifiedAmount > 0) {
            $status = 'sebagian';
        }

        $bill->update(['status' => $status]);
    }

    private function ensureBniVaIsConfigured(): void
    {
        $settings = AppSetting::getSettings();

        if (!$settings->bni_va_enabled) {
            throw new RuntimeException('BNI Virtual Account belum diaktifkan di pengaturan aplikasi.');
        }
    }

    private function generateOrderId(SppSiswaBill $bill): string
    {
        return sprintf('BNIVA-TGH-%d-%d-%s', $bill->madrasah_id, $bill->id, now()->format('YmdHis'));
    }

    private function generateTransactionNumber(SppSiswaBill $bill): string
    {
        return sprintf(
            'TRX-BNIVA-%s-%04d',
            now()->format('Ymd'),
            SppSiswaTransaction::query()->whereDate('created_at', today())->count() + 1 + $bill->id
        );
    }
}
