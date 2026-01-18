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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Midtrans config will be initialized only when needed
    }

    private function initMidtrans()
    {
        $appSetting = AppSetting::findOrFail(1);

        if (!$appSetting->midtrans_server_key) {
            throw new \Exception('Midtrans server key not configured');
        }

        // Validate key matches environment
        $isSandbox = !$appSetting->midtrans_is_production;
        $isSandboxKey = str_contains($appSetting->midtrans_server_key, 'Mid-server-');

        if ($isSandbox && !$isSandboxKey) {
            Log::error('Midtrans key does not match sandbox environment');
            throw new \Exception('Midtrans key mismatch: expected sandbox key for sandbox environment');
        }

        if (!$isSandbox && $isSandboxKey) {
            Log::error('Midtrans key does not match production environment');
            throw new \Exception('Midtrans key mismatch: expected production key for production environment');
        }

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
            'environment' => Config::$isProduction ? 'PRODUCTION' : 'SANDBOX'
        ]);
    }

    // Pembayaran Methods
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $tagihans = TagihanModel::where('tahun_anggaran', $tahun)->with('madrasah')->get();
        $data = [];

        foreach ($tagihans as $tagihan) {
            $data[] = (object) [
                'id' => $tagihan->id,
                'madrasah' => $tagihan->madrasah,
                'tahun_anggaran' => $tagihan->tahun_anggaran,
                'total_nominal' => $tagihan->nominal,
                'status_pembayaran' => $tagihan->status,
                'nominal_dibayar' => $tagihan->nominal_dibayar ?? 0,
                'jatuh_tempo' => $tagihan->jatuh_tempo,
                'tanggal_pembayaran' => $tagihan->tanggal_pembayaran,
                'nomor_invoice' => $tagihan->nomor_invoice,
                'jenis_tagihan' => $tagihan->jenis_tagihan,
            ];
        }

        // Calculate total nominal lunas from database
        $totalLunasNominal = TagihanModel::where('tahun_anggaran', $tahun)->where('status', 'lunas')->sum('nominal');

        // Calculate total tagihan nominal for belum_lunas and pending
        $totalTagihanNominal = TagihanModel::where('tahun_anggaran', $tahun)->whereIn('status', ['belum_lunas', 'pending'])->sum('nominal');

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
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Update status pembayaran di tabel tagihans
        $tagihan = TagihanModel::where('madrasah_id', $request->madrasah_id)
            ->where('tahun_anggaran', $request->tahun)
            ->first();

        if ($tagihan) {
            $tagihan->update([
                'status' => 'lunas',
                'nominal_dibayar' => $request->nominal,
                'keterangan' => $request->keterangan,
                'tanggal_pembayaran' => now(),
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
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran cash berhasil dicatat'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tagihan tidak ditemukan'
        ], 404);
    }

    public function pembayaranMidtrans(Request $request)
    {
        $request->validate([
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
        ]);

        $madrasah = Madrasah::findOrFail($request->madrasah_id);
        $tagihan = TagihanModel::where('madrasah_id', $request->madrasah_id)
            ->where('tahun_anggaran', $request->tahun)
            ->first();

        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }

        $amount = (int) $tagihan->nominal;

        // Generate unique order ID
        $orderId = 'UPPM-' . $madrasah->scod . '-' . $request->tahun . '-' . time();

        // Get Midtrans server key from app settings
        $appSetting = AppSetting::findOrFail(1);
        $serverKey = $appSetting->midtrans_server_key;

        if (!$serverKey) {
            return response()->json([
                'success' => false,
                'message' => 'Midtrans server key tidak dikonfigurasi'
            ], 500);
        }

            // Prepare transaction data for Midtrans API
            $transactionData = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $madrasah->name,
                    'email' => 'admin@' . strtolower(str_replace(' ', '', $madrasah->name)) . '.com',
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
                'notification_url' => route('midtrans.callback'),
                'callbacks' => [
                    'finish' => url('/uppm/pembayaran'),
                    'error' => url('/uppm/pembayaran'),
                    'pending' => url('/uppm/pembayaran'),
                ],
            ];

        try {
            // Use curl to create transaction via Midtrans API
            $url = $appSetting->midtrans_is_production
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $headers = [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transactionData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Add timeout

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            Log::info('Midtrans API Call', [
                'url' => $url,
                'headers' => $headers,
                'transaction_data' => $transactionData,
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'result' => $result,
            ]);

            if ($curlError) {
                Log::error('Curl Error', ['error' => $curlError]);
                return response()->json([
                    'success' => false,
                    'message' => 'Curl error: ' . $curlError,
                ], 500);
            }

            $response = json_decode($result, true);

            if ($httpCode == 201 && isset($response['token'])) {
                // Create payment record
                $payment = Payment::create([
                    'madrasah_id' => $request->madrasah_id,
                    'tahun_anggaran' => $request->tahun,
                    'nominal' => $tagihan->nominal,
                    'metode_pembayaran' => 'midtrans',
                    'status' => 'pending',
                    'keterangan' => 'Pembayaran via Midtrans',
                    'tagihan_id' => $tagihan->id,
                    'order_id' => $orderId,
                ]);

                return response()->json([
                    'success' => true,
                    'snap_token' => $response['token'],
                    'order_id' => $orderId,
                    'payment_id' => $payment->id,
                ]);
            } else {
                Log::error('Midtrans API Error', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'transaction_data' => $transactionData,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat transaksi Midtrans: ' . ($response['error_messages'][0] ?? 'Unknown error'),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Transaction Failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage(),
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

    public function midtransCallback(Request $request)
    {
        // Initialize Midtrans config for consistent key usage
        $this->initMidtrans();
        $serverKey = Config::$serverKey;

        $hashed = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed !== $request->signature_key) {
            Log::warning('Midtrans Callback Invalid Signature', [
                'order_id' => $request->order_id
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature'
            ], 403);
        }

        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $payment->update([
                    'status' => 'success',
                    'transaction_id' => $request->transaction_id,
                    'payment_type' => $request->payment_type,
                ]);

                if ($payment->tagihan_id) {
                    TagihanModel::where('id', $payment->tagihan_id)->update([
                        'status' => 'lunas',
                        'nominal_dibayar' => $payment->nominal,
                        'tanggal_pembayaran' => now(),
                    ]);
                }
                break;

            case 'pending':
                $payment->update(['status' => 'pending']);
                break;

            default:
                $payment->update(['status' => 'failed']);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    public function paymentResult(Request $request)
    {
        try {
            $dataMidtrans = json_decode($request->result_data);

            Log::info('Raw payment result data', [
                'raw_data' => $request->result_data,
                'decoded_data' => $dataMidtrans
            ]);

            if (!$dataMidtrans || !isset($dataMidtrans->order_id)) {
                Log::error('Invalid Midtrans data', ['data' => $request->result_data]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data Midtrans tidak valid'
                ], 400);
            }

            Log::info('Payment result received', [
                'order_id' => $dataMidtrans->order_id,
                'transaction_status' => $dataMidtrans->transaction_status,
                'fraud_status' => $dataMidtrans->fraud_status ?? null,
                'payment_type' => $dataMidtrans->payment_type ?? null,
                'gross_amount' => $dataMidtrans->gross_amount ?? null
            ]);

            $payment = Payment::where('order_id', $dataMidtrans->order_id)->first();

            if (!$payment) {
                Log::error('Payment not found', ['order_id' => $dataMidtrans->order_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment tidak ditemukan'
                ], 404);
            }

            // Determine payment status - handle sandbox vs production differences
            $transactionStatus = $dataMidtrans->transaction_status;
            $fraudStatus = $dataMidtrans->fraud_status ?? null;

            // For sandbox testing, accept more statuses as success
            $appSetting = AppSetting::find(1);
            $isProduction = $appSetting ? $appSetting->midtrans_is_production : false;

            if (!$isProduction) {
                // In sandbox, accept these statuses as successful
                $isSuccess = in_array($transactionStatus, ['capture', 'settlement', 'success', '200']);
            } else {
                // In production, be strict
                $isSuccess = in_array($transactionStatus, ['capture', 'settlement', 'success']);
            }

            $paymentStatus = $isSuccess ? 'success' : ($transactionStatus == 'pending' ? 'pending' : 'failed');

            Log::info('Processing payment result', [
                'order_id' => $dataMidtrans->order_id,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'is_success' => $isSuccess,
                'payment_status' => $paymentStatus,
                'current_payment_status' => $payment->status,
                'is_production' => $isProduction
            ]);

            // Update payment record
            $updateData = [
                'status' => $paymentStatus,
                'payment_type' => $dataMidtrans->payment_type ?? null,
                'transaction_id' => $dataMidtrans->transaction_id ?? null,
                'pdf_url' => $dataMidtrans->pdf_url ?? null,
                'response_midtrans' => json_encode($dataMidtrans),
            ];

            $payment->update($updateData);

            Log::info('Payment record updated', [
                'payment_id' => $payment->id,
                'new_status' => $paymentStatus,
                'tagihan_id' => $payment->tagihan_id
            ]);

            // Update tagihan jika sukses
            if ($isSuccess && $payment->tagihan_id) {
                $tagihan = TagihanModel::find($payment->tagihan_id);
                if ($tagihan) {
                    $oldStatus = $tagihan->status;
                    $tagihan->update([
                        'status' => 'lunas',
                        'nominal_dibayar' => $payment->nominal,
                        'tanggal_pembayaran' => now(),
                    ]);

                    Log::info('Tagihan updated to lunas', [
                        'tagihan_id' => $payment->tagihan_id,
                        'nominal' => $payment->nominal,
                        'old_status' => $oldStatus,
                        'new_status' => 'lunas'
                    ]);
                } else {
                    Log::error('Tagihan not found for update', ['tagihan_id' => $payment->tagihan_id]);
                }
            }

            // Return appropriate response based on payment status
            if ($isSuccess) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses'
                ]);
            } elseif ($transactionStatus == 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sedang diproses'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran gagal diproses'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment result processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran'
            ], 500);
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
