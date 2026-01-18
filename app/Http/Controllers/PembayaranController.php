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

        // Generate unique order ID - ensure scod is not empty and clean
        $scod = trim($madrasah->scod) ?: 'DEFAULT';
        $scod = preg_replace('/[^A-Za-z0-9\-]/', '', $scod); // Remove special characters
        $orderId = 'UPPM-' . $scod . '-' . $request->tahun . '-' . time();

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
                    'email' => $madrasah->email ?? 'billing@yourdomain.com', // Email dari madrasah atau fallback
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
                'callbacks' => [
                    'finish' => url('/uppm/pembayaran'),
                    'error' => url('/uppm/pembayaran'),
                    'pending' => url('/uppm/pembayaran'),
                ],
                'notification_url' => secure_url('/midtrans/callback'), // Pastikan HTTPS
            ];

        try {
            // Gunakan Snap SDK resmi untuk konsistensi
            $this->initMidtrans();
            $snapToken = Snap::getSnapToken($transactionData);

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
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'payment_id' => $payment->id,
            ]);
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

    /**
     * Handle Midtrans Payment Notification Callback
     * URL: POST /midtrans/callback
     */
    public function midtransCallback(Request $request)
    {
        try {
            // Set Midtrans config
            $this->initMidtrans();
            $serverKey = Config::$serverKey;

            // Get raw POST data dari Midtrans
            $notification = $request->all();

            Log::info('MIDTRANS CALLBACK HIT', $notification);

            // Validasi field wajib dari Midtrans
            if (!isset(
                $notification['order_id'],
                $notification['transaction_status'],
                $notification['signature_key']
            )) {
                Log::error('Invalid callback payload - missing required fields', $notification);
                return response()->json(['status' => 'invalid payload'], 400);
            }

            // Validasi signature (penting untuk security)
            $signature = hash('sha512',
                $notification['order_id'] .
                $notification['status_code'] .
                $notification['gross_amount'] .
                $serverKey
            );

            if ($signature !== $notification['signature_key']) {
                Log::error('Invalid signature key', [
                    'order_id' => $notification['order_id'],
                    'expected' => $signature,
                    'received' => $notification['signature_key']
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }

            // Cari payment berdasarkan order_id
            $payment = Payment::where('order_id', $notification['order_id'])->first();

            if (!$payment) {
                Log::error('Payment not found', ['order_id' => $notification['order_id']]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            // Proteksi idempotent - Midtrans bisa kirim callback berkali-kali
            if ($payment->status === 'success') {
                Log::info('Payment already processed, skipping duplicate callback', [
                    'order_id' => $notification['order_id']
                ]);
                return response()->json(['status' => 'already processed']);
            }

            // Map status Midtrans ke status aplikasi dengan cek fraud_status
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? null;

            if ($transactionStatus === 'capture') {
                // Untuk capture, cek fraud_status
                if ($fraudStatus === 'accept') {
                    $newStatus = 'success';
                } elseif ($fraudStatus === 'challenge') {
                    $newStatus = 'pending'; // Tunggu manual review
                } else {
                    $newStatus = 'failed';
                }
            } elseif ($transactionStatus === 'settlement') {
                $newStatus = 'success'; // Dana sudah diterima
            } elseif ($transactionStatus === 'pending') {
                $newStatus = 'pending'; // Menunggu pembayaran
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $newStatus = 'failed';
            } else {
                $newStatus = 'failed';
            }

            // Update payment status
            $payment->update([
                'status' => $newStatus,
                'transaction_id' => $notification['transaction_id'] ?? null,
                'payment_type' => $notification['payment_type'] ?? null,
                'pdf_url' => $notification['pdf_url'] ?? null,
                'paid_at' => in_array($notification['transaction_status'], ['capture', 'settlement']) ? now() : null,
            ]);

            // Jika status berubah ke success, update tagihan juga dengan cek nominal
            if ($newStatus === 'success') {
                if ($payment->tagihan_id) {
                    $tagihan = TagihanModel::find($payment->tagihan_id);
                    if ($tagihan) {
                        // Cek apakah pembayaran mencukupi nominal tagihan
                        if ($payment->nominal >= $tagihan->nominal) {
                            $tagihan->update([
                                'status' => 'lunas',
                                'nominal_dibayar' => $payment->nominal,
                                'tanggal_pembayaran' => now(),
                            ]);

                            Log::info('Tagihan updated to lunas', [
                                'tagihan_id' => $payment->tagihan_id,
                                'nominal_bayar' => $payment->nominal,
                                'nominal_tagihan' => $tagihan->nominal
                            ]);
                        } else {
                            // Pembayaran kurang, tetap pending
                            $tagihan->update([
                                'status' => 'pending',
                                'nominal_dibayar' => $payment->nominal,
                            ]);

                            Log::info('Tagihan partial payment', [
                                'tagihan_id' => $payment->tagihan_id,
                                'nominal_bayar' => $payment->nominal,
                                'nominal_tagihan' => $tagihan->nominal
                            ]);
                        }
                    }
                }
            }

            Log::info('Payment callback processed', [
                'order_id' => $notification['order_id'],
                'status' => $newStatus,
                'transaction_status' => $notification['transaction_status']
            ]);

            // Response ke Midtrans (harus 200 OK)
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Callback processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $request->order_id ?? 'unknown',
                'trace' => $e->getTraceAsString()
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
