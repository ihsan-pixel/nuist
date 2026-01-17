<?php

namespace App\Http\Controllers;

use App\Models\UppmSetting;
use App\Models\UppmSchoolData;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    // Pembayaran Methods
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasahs = Madrasah::all();
        $data = [];

        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        foreach ($madrasahs as $madrasah) {
            $dataSekolah = DataSekolah::where('madrasah_id', $madrasah->id)
                ->where('tahun', $tahun)
                ->first();

            $jumlah_siswa = $dataSekolah->jumlah_siswa ?? 0;
            $jumlah_pns_sertifikasi = $dataSekolah->jumlah_pns_sertifikasi ?? 0;
            $jumlah_pns_non_sertifikasi = $dataSekolah->jumlah_pns_non_sertifikasi ?? 0;
            $jumlah_gty_sertifikasi = $dataSekolah->jumlah_gty_sertifikasi ?? 0;
            $jumlah_gty_sertifikasi_inpassing = $dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0;
            $jumlah_gty_non_sertifikasi = $dataSekolah->jumlah_gty_non_sertifikasi ?? 0;
            $jumlah_gtt = $dataSekolah->jumlah_gtt ?? 0;
            $jumlah_pty = $dataSekolah->jumlah_pty ?? 0;
            $jumlah_ptt = $dataSekolah->jumlah_ptt ?? 0;

            $total_nominal = 0;
            if ($setting) {
                $monthly_nominal = ($jumlah_siswa * $setting->nominal_siswa) +
                                   ($jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi) +
                                   ($jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi) +
                                   ($jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi) +
                                   ($jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing) +
                                   ($jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi) +
                                   ($jumlah_gtt * $setting->nominal_gtt) +
                                   ($jumlah_pty * $setting->nominal_pty) +
                                   ($jumlah_ptt * $setting->nominal_ptt);
                $total_nominal = $monthly_nominal * 12;
            }

            $data[] = (object) [
                'id' => null,
                'madrasah' => $madrasah,
                'tahun_anggaran' => $tahun,
                'jumlah_siswa' => $jumlah_siswa,
                'jumlah_pns_sertifikasi' => $jumlah_pns_sertifikasi,
                'jumlah_pns_non_sertifikasi' => $jumlah_pns_non_sertifikasi,
                'jumlah_gty_sertifikasi' => $jumlah_gty_sertifikasi,
                'jumlah_gty_sertifikasi_inpassing' => $jumlah_gty_sertifikasi_inpassing,
                'jumlah_gty_non_sertifikasi' => $jumlah_gty_non_sertifikasi,
                'jumlah_gtt' => $jumlah_gtt,
                'jumlah_pty' => $jumlah_pty,
                'jumlah_ptt' => $jumlah_ptt,
                'total_nominal' => $total_nominal,
                'status_pembayaran' => 'belum_lunas',
                'nominal_dibayar' => 0,
            ];
        }

        return view('pembayaran.index', compact('data', 'tahun'));
    }

    public function detail(Request $request, $madrasah_id)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasah = Madrasah::findOrFail($madrasah_id);
        $dataSekolah = DataSekolah::where('madrasah_id', $madrasah_id)->where('tahun', $tahun)->first();

        if (!$dataSekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan untuk tahun tersebut.');
        }

        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'Pengaturan UPPM tidak ditemukan untuk tahun tersebut.');
        }

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

        return view('pembayaran.detail', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'tahun'));
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
        // Untuk sekarang, kita akan update status di UppmSchoolData atau buat record baru
        $uppmData = UppmSchoolData::updateOrCreate(
            [
                'madrasah_id' => $request->madrasah_id,
                'tahun_anggaran' => $request->tahun,
            ],
            [
                'nominal_dibayar' => $request->nominal,
                'status_pembayaran' => 'lunas', // atau 'sebagian' tergantung logic
            ]
        );

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
        $nominal += $schoolData->jumlah_karyawan_tetap * $setting->nominal_karyawan_tetap;
        $nominal += $schoolData->jumlah_karyawan_tidak_tetap * $setting->nominal_karyawan_tidak_tetap;

        return $nominal;
    }
}
