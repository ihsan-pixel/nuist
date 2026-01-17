<?php

namespace App\Http\Controllers;

use App\Models\UppmSetting;
use App\Models\UppmSchoolData;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\Payment;
use App\Models\Tagihan;
use App\Models\Yayasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    // Pembayaran Methods
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $tagihans = Tagihan::where('tahun_anggaran', $tahun)->with('madrasah')->get();
        $data = [];

        foreach ($tagihans as $tagihan) {
            $data[] = (object) [
                'id' => $tagihan->id,
                'madrasah' => $tagihan->madrasah,
                'tahun_anggaran' => $tagihan->tahun_anggaran,
                'total_nominal' => $tagihan->nominal,
                'status_pembayaran' => $tagihan->status,
                'nominal_dibayar' => 0, // TODO: calculate from payments
                'jatuh_tempo' => $tagihan->jatuh_tempo,
            ];
        }

        return view('pembayaran.index', compact('data', 'tahun'));
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

        $uniqueCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // Check if tagihan exists for this madrasah and tahun
        $tagihan = Tagihan::where('madrasah_id', $madrasah->id)
            ->where('tahun_anggaran', $tahun)
            ->first();

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan belum dibuat untuk madrasah ' . $madrasah->name . ' pada tahun ' . $tahun . '. Silakan buat tagihan terlebih dahulu.');
        }

        return view('pembayaran.detail', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'tahun', 'rincian', 'yayasan', 'uniqueCode', 'tagihan'));
    }

    public function pembayaranCash(Request $request)
    {
        $request->validate([
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan pembayaran cash ke database
        $payment = Payment::create([
            'madrasah_id' => $request->madrasah_id,
            'tahun_anggaran' => $request->tahun,
            'nominal' => $request->nominal,
            'metode_pembayaran' => 'cash',
            'status' => 'success',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Pembayaran cash berhasil dicatat');
    }

    public function pembayaranMidtrans(Request $request)
    {
        $request->validate([
            'madrasah_id' => 'required|exists:madrasahs,id',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Buat record pembayaran dengan status pending
        $payment = Payment::create([
            'madrasah_id' => $request->madrasah_id,
            'tahun_anggaran' => $request->tahun,
            'nominal' => $request->nominal,
            'metode_pembayaran' => 'midtrans',
            'status' => 'pending',
            'keterangan' => 'Pembayaran via Midtrans',
        ]);

        // TODO: Implementasi Midtrans API
        // Untuk sekarang, return placeholder response
        return response()->json([
            'success' => false,
            'message' => 'Integrasi Midtrans belum diimplementasikan',
            'payment_id' => $payment->id,
        ]);
    }

    public function checkTagihan(Request $request)
    {
        $madrasahId = $request->get('madrasah_id');
        $tahun = $request->get('tahun');

        $tagihan = Tagihan::where('madrasah_id', $madrasahId)
            ->where('tahun_anggaran', $tahun)
            ->first();

        return response()->json([
            'exists' => $tagihan ? true : false
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
