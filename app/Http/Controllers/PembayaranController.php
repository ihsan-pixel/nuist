<?php

namespace App\Http\Controllers;

use App\Models\UppmSetting;
use App\Models\UppmSchoolData;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\Payment;
use App\Models\Tagihan as TagihanModel;
use App\Models\Yayasan;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;
use Midtrans\Config;

class PembayaranController extends Controller
{
    private function initMidtrans()
    {
        $appSetting = AppSetting::findOrFail(1);

        if (!$appSetting->midtrans_server_key) {
            throw new \Exception('Midtrans server key not configured');
        }

        // Set config Midtrans
        Config::$serverKey = trim($appSetting->midtrans_server_key);
        Config::$isProduction = (bool) $appSetting->midtrans_is_production;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }

        Log::info('Midtrans config initialized', [
            'server_key_set' => !empty(Config::$serverKey),
            'is_production' => Config::$isProduction,
        ]);
    }

    // Pembayaran Methods
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $tagihans = TagihanModel::with('madrasah')->get();
        $data = [];

        foreach ($tagihans as $tagihan) {
            $data[] = (object) [
                'id' => $tagihan->id,
                'madrasah' => $tagihan->madrasah,
                'tahun_anggaran' => $tagihan->tahun_anggaran,
                'total_nominal' => $tagihan->nominal,
                'status_pembayaran' => $tagihan->status,
                'nominal_dibayar' => $tagihan->status === 'lunas' ? $tagihan->nominal : 0,
                'jatuh_tempo' => $tagihan->jatuh_tempo,
                'tanggal_pembayaran' => $tagihan->tanggal_pembayaran,
                'nomor_invoice' => $tagihan->nomor_invoice,
                'jenis_tagihan' => $tagihan->jenis_tagihan,
            ];
        }

        // Calculate total nominal lunas from database (all years)
        $totalLunasNominal = TagihanModel::where('status', 'lunas')->sum('nominal');

        // Calculate total tagihan nominal for belum_lunas and pending (all years)
        $totalTagihanNominal = TagihanModel::whereIn('status', ['belum_lunas', 'pending'])->sum('nominal');

        return view('pembayaran.index', compact('data', 'tahun', 'totalLunasNominal', 'totalTagihanNominal'));
    }

    public function detail(Request $request, $madrasah_id)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasah = Madrasah::findOrFail($madrasah_id);
        $dataSekolah = DataSekolah::where('madrasah_id', $madrasah_id)->where('tahun', $tahun)->first();
        $yayasan = Yayasan::find(1);

        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        $schoolData = (object) [
            'jumlah_siswa' => $dataSekolah->jumlah_siswa ?? 0,
            'jumlah_pns_sertifikasi' => $dataSekolah->jumlah_pns_sertifikasi ?? 0,
            'jumlah_pns_non_sertifikasi' => $dataSekolah->jumlah_pns_non_sertifikasi ?? 0,
            'jumlah_gty_sertifikasi' => $dataSekolah->jumlah_gty_sertifikasi ?? 0,
            'jumlah_gty_sertifikasi_inpassing' => $dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0,
            'jumlah_gty_non_sertifikasi' => $dataSekolah->jumlah_gty_non_sertifikasi ?? 0,
            'jumlah_gtt' => $dataSekolah->jumlah_gtt ?? 0,
            'jumlah_pty' => $dataSekolah->jumlah_pty ?? 0,
            'jumlah_ptt' => $dataSekolah->jumlah_ptt ?? 0,
        ];

        $nominalBulanan = $this->hitungNominalBulanan($schoolData, $setting);
        $totalTahunan = $nominalBulanan * 12;

        $rincian = [
            'siswa' => ($schoolData->jumlah_siswa * $setting->nominal_siswa) * 12,
            'pns_sertifikasi' => ($schoolData->jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi) * 12,
            'pns_non_sertifikasi' => ($schoolData->jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi) * 12,
            'gty_sertifikasi' => ($schoolData->jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi) * 12,
            'gty_sertifikasi_inpassing' => ($schoolData->jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing) * 12,
            'gty_non_sertifikasi' => ($schoolData->jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi) * 12,
            'gtt' => ($schoolData->jumlah_gtt * $setting->nominal_gtt) * 12,
            'pty' => ($schoolData->jumlah_pty * $setting->nominal_pty) * 12,
            'ptt' => ($schoolData->jumlah_ptt * $setting->nominal_ptt) * 12,
        ];

        // Check if tagihan exists for this madrasah and tahun
        $tagihan = TagihanModel::where('madrasah_id', $madrasah->id)
            ->where('tahun_anggaran', $tahun)
            ->first();

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan belum dibuat untuk madrasah ' . $madrasah->name . ' pada tahun ' . $tahun . '. Silakan buat tagihan terlebih dahulu.');
        }

        // Generate unique invoice number if not exists
        if (!$tagihan->nomor_invoice) {
            $uniqueCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            $nomorInvoice = 'INV-' . $madrasah->scod . '-' . $tahun . '-' . $uniqueCode;

            $tagihan->update(['nomor_invoice' => $nomorInvoice]);
        } else {
            $nomorInvoice = $tagihan->nomor_invoice;
        }

        return view('pembayaran.detail', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'tahun', 'rincian', 'yayasan', 'nomorInvoice', 'tagihan'));
    }

    public function pembayaranCash(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Update status pembayaran di tabel tagihans
        $tagihan = TagihanModel::findOrFail($request->tagihan_id);

        $tagihan->update([
            'status' => 'lunas',
            // 'nominal_dibayar' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal_pembayaran' => now(),
            'jenis_pembayaran' => 'cash',
        ]);

        // Simpan pembayaran cash ke database payments
        $payment = Payment::create([
            'madrasah_id' => $request->madrasah_id,
            'tahun_anggaran' => $request->tahun,
            'nominal' => $request->nominal,
            'metode_pembayaran' => 'cash',
            'status' => 'success',
            'keterangan' => $request->keterangan,
            'tagihan_id' => $tagihan->id,
            'paid_at' => now(),
        ]);

        // Send email confirmation with PDF invoice
        $this->sendPaymentConfirmationEmail($payment, $tagihan);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran cash berhasil dicatat'
        ]);
    }

    public function pembayaranMidtrans(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
        ]);

        $madrasah = Madrasah::findOrFail($request->madrasah_id);
        $tagihan = TagihanModel::findOrFail($request->tagihan_id);

        $amount = (int) $tagihan->nominal;

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal tagihan harus lebih besar dari 0.'
            ], 400);
        }

        // Buat order_id berdasarkan tagihan_id - simplified format
        $baseOrderId = 'UPPM-' . $request->tahun . '-' . $tagihan->id;

        // Check if payment already exists and is pending
        $existingPayment = Payment::where('tagihan_id', $tagihan->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            // If payment exists and is pending, create new order_id for retry
            $orderId = $baseOrderId . '-' . time();
            Log::info('Retrying payment for existing pending payment, creating new order_id', [
                'existing_payment_id' => $existingPayment->id,
                'new_order_id' => $orderId,
                'tagihan_id' => $tagihan->id
            ]);
        } else {
            $orderId = $baseOrderId;
        }

        // Inisialisasi Midtrans
        $this->initMidtrans();

        $transactionData = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $madrasah->name,
                'email' => 'admin@example.com',
                'phone' => '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'UPPM-' . $request->tahun,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Iuran Pengembangan Pendidikan Madrasah (UPPM) ' . $request->tahun,
                ]
            ],
            'notification_url' => url('/midtrans/callback'),
        ];

        try {
            $serverKey = AppSetting::findOrFail(1)->midtrans_server_key;
            $url = config('app.env') === 'production'
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $headers = [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transactionData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);
            $response = json_decode($result, true);
            curl_close($ch);

            if (!isset($response['token'])) {
                Log::error('Midtrans transaction creation failed', [
                    'response' => $response,
                    'transaction_data' => $transactionData,
                    'http_code' => $httpCode ?? null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $response['error_messages'][0] ?? 'Gagal membuat transaksi Midtrans'
                ], 500);
            }

            Log::info('Midtrans transaction created successfully', [
                'token' => $response['token'],
                'redirect_url' => $response['redirect_url'] ?? null,
                'order_id' => $orderId
            ]);

            // Buat payment record jika belum ada, atau update jika sudah ada
            $payment = Payment::firstOrCreate(
                ['order_id' => $orderId],
                [
                    'madrasah_id' => $madrasah->id,
                    'tahun_anggaran' => $request->tahun,
                    'nominal' => $amount,
                    'metode_pembayaran' => 'midtrans',
                    'status' => 'pending',
                    'keterangan' => 'Pembayaran via Midtrans',
                    'tagihan_id' => $tagihan->id,
                ]
            );

            return response()->json([
                'success' => true,
                'snap_token' => $response['token'],
                'order_id' => $orderId,
                'payment_id' => $payment->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkTagihan(Request $request)
    {
        $madrasahId = $request->get('madrasah_id');
        $tahun = $request->get('tahun');

        $tagihan = TagihanModel::where('madrasah_id', $madrasahId)
            ->where('tahun_anggaran', $tahun)
            ->first();

        return response()->json([
            'exists' => $tagihan ? true : false
        ]);
    }

    /**
     * Handle Midtrans Payment Notification Callback
     * URL: POST /midtrans/callback
     */

    public function midtransCallback(Request $request)
    {
        // Log callback attempt immediately
        Log::info('MIDTRANS CALLBACK ATTEMPT', [
            'timestamp' => now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'has_content' => $request->hasContent(),
            'content_length' => $request->header('Content-Length')
        ]);

        try {
            $this->initMidtrans();
            $notification = $request->all();

            Log::info('MIDTRANS CALLBACK HIT', [
                'notification' => $notification,
                'headers' => $request->headers->all(),
                'ip' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);

            // Skip signature untuk sandbox atau production jika diperlukan
            $skipSignature = app()->environment('local') || env('MIDTRANS_SKIP_SIGNATURE', true);

            Log::info('Signature validation check', [
                'skip_signature' => $skipSignature,
                'environment' => app()->environment(),
                'midtrans_skip_env' => env('MIDTRANS_SKIP_SIGNATURE', 'not_set')
            ]);

            if (!$skipSignature) {
                $signature = hash('sha512',
                    ($notification['order_id'] ?? '') .
                    ($notification['status_code'] ?? '') .
                    ($notification['gross_amount'] ?? '') .
                    Config::$serverKey
                );

                Log::info('Signature validation details', [
                    'received_signature' => $notification['signature_key'] ?? 'not_provided',
                    'calculated_signature' => $signature,
                    'order_id' => $notification['order_id'] ?? '',
                    'status_code' => $notification['status_code'] ?? '',
                    'gross_amount' => $notification['gross_amount'] ?? '',
                    'server_key_length' => strlen(Config::$serverKey)
                ]);

                if (($notification['signature_key'] ?? '') !== $signature) {
                    Log::warning('Invalid signature in callback', [
                        'received_signature' => $notification['signature_key'] ?? '',
                        'calculated_signature' => $signature,
                        'order_id' => $notification['order_id'] ?? ''
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
                }
            }

        // Cari payment berdasarkan order_id
        $payment = Payment::where('order_id', $notification['order_id'])->first();

            Log::info('Midtrans Callback Received', [
                'payload' => $notification,
                'payment_found' => $payment ? true : false,
                'payment_status' => $payment->status ?? null,
                'payment_type_in_notification' => $notification['payment_type'] ?? 'not_present',
                'transaction_status' => $notification['transaction_status'] ?? 'not_present'
            ]);

        if (!$payment) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        if ($payment->status === 'success') {
            return response()->json(['status' => 'already processed']);
        }

            $statusMapping = [
                'capture' => 'success',
                'settlement' => 'success',
                'pending' => 'pending',
                'deny' => 'failed',
                'cancel' => 'failed',
                'expire' => 'failed',
                'failure' => 'failed'
            ];

            $newStatus = $statusMapping[$notification['transaction_status']] ?? 'failed';

            $updateData = [
                'status' => $newStatus,
                'transaction_id' => $notification['transaction_id'] ?? null,
                'payment_type' => $notification['payment_type'] ?? null,
                'pdf_url' => $notification['pdf_url'] ?? null,
                'paid_at' => in_array($notification['transaction_status'], ['capture', 'settlement']) ? now() : null,
            ];

            // Set metode_transaksi for midtrans payments when payment_type is available
            if ($payment->metode_pembayaran === 'midtrans' && isset($notification['payment_type'])) {
                $updateData['metode_transaksi'] = $notification['payment_type'];
            }

            $payment->update($updateData);

            Log::info('Payment updated', [
                'payment_id' => $payment->id,
                'new_status' => $newStatus,
                'transaction_status' => $notification['transaction_status']
            ]);

            if ($newStatus === 'success' && $payment->tagihan_id) {
                $tagihan = TagihanModel::find($payment->tagihan_id);
                if ($tagihan) {
                    $tagihan->update([
                        'status' => 'lunas',
                        'tanggal_pembayaran' => now(),
                    ]);

                    Log::info('Tagihan updated to lunas', [
                        'tagihan_id' => $payment->tagihan_id,
                        'payment_id' => $payment->id
                    ]);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notification' => $notification ?? null,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentResult(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Result diterima. Status pembayaran diproses otomatis.'
        ]);
    }

    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        // Check if payment is already successful
        if ($payment->status === 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already processed'
            ]);
        }

        // For sandbox testing, we'll simulate success
        // In production, you might want to check with Midtrans API
        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);

        if ($payment->tagihan_id) {
            $tagihan = TagihanModel::find($payment->tagihan_id);
            if ($tagihan) {
                $tagihan->update([
                    'status' => 'lunas',
                    'tanggal_pembayaran' => now(),
                    'jenis_pembayaran' => 'online',
                    'metode_pembayaran' => $notification['payment_type'] ?? null,
                ]);

                // Send email confirmation with PDF invoice
                $this->sendPaymentConfirmationEmail($payment, $tagihan);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }







    private function sendPaymentConfirmationEmail($payment, $tagihan, $madrasah = null)
    {
        try {
            if (!$madrasah) {
                $madrasah = Madrasah::find($tagihan->madrasah_id);
            }

            // Find admin user for this madrasah
            $adminUser = \App\Models\User::where('madrasah_id', $tagihan->madrasah_id)
                ->where('role', 'admin')
                ->first();

            if (!$adminUser || !$adminUser->email) {
                Log::warning('Cannot send payment confirmation email: Admin user email not found', [
                    'tagihan_id' => $tagihan->id,
                    'madrasah_id' => $tagihan->madrasah_id,
                    'madrasah_name' => $madrasah ? $madrasah->name : 'Unknown',
                    'admin_user_found' => $adminUser ? true : false
                ]);
                return;
            }

            Mail::to($adminUser->email)->send(new PaymentConfirmation($payment, $tagihan, $madrasah));

            Log::info('Payment confirmation email sent successfully', [
                'tagihan_id' => $tagihan->id,
                'admin_email' => $adminUser->email,
                'admin_name' => $adminUser->name,
                'madrasah_name' => $madrasah ? $madrasah->name : 'Unknown',
                'order_id' => $payment->order_id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'tagihan_id' => $tagihan->id,
                'madrasah_name' => $madrasah ? $madrasah->name : 'Unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function hitungNominalBulanan($schoolData, $setting)
    {
        $nominal = 0;

        $nominal += $schoolData->jumlah_siswa * $setting->nominal_siswa;
        $nominal += $schoolData->jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi;
        $nominal += $schoolData->jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi;
        $nominal += $schoolData->jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi;
        $nominal += $schoolData->jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing;
        $nominal += $schoolData->jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi;
        $nominal += $schoolData->jumlah_gtt * $setting->nominal_gtt;
        $nominal += $schoolData->jumlah_pty * $setting->nominal_pty;
        $nominal += $schoolData->jumlah_ptt * $setting->nominal_ptt;

        return $nominal;
    }
}
