<?php

namespace App\Http\Controllers;

use App\Models\UppmSetting;
use App\Models\UppmSchoolData;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class UppmController extends Controller
{
    public function index()
    {
        return view('uppm.index');
    }

    public function dataSekolah(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasahs = Madrasah::all();
        $data = [];

        // Ambil pengaturan UPPM untuk tahun ini
        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        foreach ($madrasahs as $madrasah) {
            // Ambil data dari tabel data_sekolah berdasarkan madrasah_id dan tahun
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

            // Hitung total nominal berdasarkan pengaturan UPPM
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

        return view('uppm.data-sekolah', compact('data', 'tahun'));
    }

    public function perhitunganIuran(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        $madrasahs = Madrasah::all();
        $perhitungan = [];

        if ($setting) {
            foreach ($madrasahs as $madrasah) {
                $dataSekolah = DataSekolah::where('madrasah_id', $madrasah->id)
                    ->where('tahun', $tahun)
                    ->first();

                if ($dataSekolah) {
                    // Buat objek data dengan field yang diperlukan untuk perhitungan
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
                        'jumlah_karyawan_tetap' => 0, // Tidak ada di DataSekolah, set 0
                        'jumlah_karyawan_tidak_tetap' => 0, // Tidak ada di DataSekolah, set 0
                    ];

                    $nominalBulanan = $this->hitungNominalBulanan($schoolData, $setting);
                    $totalTahunan = $nominalBulanan * 12;

                    $perhitungan[] = [
                        'madrasah' => $madrasah,
                        'data' => $schoolData,
                        'nominal_bulanan' => $nominalBulanan,
                        'total_tahunan' => $totalTahunan,
                    ];
                }
            }
        }

        return view('uppm.perhitungan-iuran', compact('perhitungan', 'tahun', 'setting'));
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

    public function tagihan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $data = UppmSchoolData::with('madrasah')
            ->where('tahun_anggaran', $tahun)
            ->get();

        return view('uppm.tagihan', compact('data', 'tahun'));
    }

    public function invoice(Request $request)
    {
        $madrasah_id = $request->get('madrasah_id');
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

        // Buat objek data untuk perhitungan
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
            'jumlah_karyawan_tetap' => 0,
            'jumlah_karyawan_tidak_tetap' => 0,
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

        return view('uppm.invoice', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'rincian', 'tahun'));
    }

    public function downloadInvoice(Request $request)
    {
        $madrasah_id = $request->get('madrasah_id');
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

        // Buat objek data untuk perhitungan
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
            'jumlah_karyawan_tetap' => 0,
            'jumlah_karyawan_tidak_tetap' => 0,
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

        $pdf = Pdf::loadView('uppm.invoice-pdf', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'rincian', 'tahun'));
        return $pdf->download('invoice-uppm-' . $madrasah->name . '-' . $tahun . '.pdf');
    }

    public function pengaturan()
    {
        $settings = UppmSetting::orderBy('tahun_anggaran', 'desc')->get();
        return view('uppm.pengaturan', compact('settings'));
    }

    public function storePengaturan(Request $request)
    {
        $request->validate([
            'tahun_anggaran' => 'required|integer|min:2020|max:' . (date('Y') + 1) . '|unique:uppm_settings,tahun_anggaran',
            'nominal_siswa' => 'required|numeric|min:0',
            'nominal_pns_sertifikasi' => 'required|numeric|min:0',
            'nominal_pns_non_sertifikasi' => 'required|numeric|min:0',
            'nominal_gty_sertifikasi' => 'required|numeric|min:0',
            'nominal_gty_sertifikasi_inpassing' => 'required|numeric|min:0',
            'nominal_gty_non_sertifikasi' => 'required|numeric|min:0',
            'nominal_gtt' => 'required|numeric|min:0',
            'nominal_pty' => 'required|numeric|min:0',
            'nominal_ptt' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'skema_pembayaran' => 'required|in:lunas,cicilan',
            'aktif' => 'boolean',
            'catatan' => 'nullable|string',
            'format_invoice' => 'required|string',
        ]);

        $data = $request->all();

        UppmSetting::create($data);

        return redirect()->back()->with('success', 'Pengaturan UPPM berhasil disimpan');
    }

    public function updatePengaturan(Request $request, $id)
    {
        $setting = UppmSetting::findOrFail($id);

        $request->validate([
            'tahun_anggaran' => 'required|integer|min:2020|max:' . (date('Y') + 1) . '|unique:uppm_settings,tahun_anggaran,' . $id,
            'nominal_siswa' => 'required|numeric|min:0',
            'nominal_pns_sertifikasi' => 'required|numeric|min:0',
            'nominal_pns_non_sertifikasi' => 'required|numeric|min:0',
            'nominal_gty_sertifikasi' => 'required|numeric|min:0',
            'nominal_gty_sertifikasi_inpassing' => 'required|numeric|min:0',
            'nominal_gty_non_sertifikasi' => 'required|numeric|min:0',
            'nominal_gtt' => 'required|numeric|min:0',
            'nominal_pty' => 'required|numeric|min:0',
            'nominal_ptt' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'skema_pembayaran' => 'required|in:lunas,cicilan',
            'aktif' => 'boolean',
            'catatan' => 'nullable|string',
            'format_invoice' => 'required|string',
        ]);

        $data = $request->all();
        $data['aktif'] = $request->has('aktif');

        $setting->update($data);

        return response()->json(['success' => true, 'message' => 'Pengaturan UPPM berhasil diperbarui']);
    }

    public function destroyPengaturan($id)
    {
        $setting = UppmSetting::findOrFail($id);
        $setting->delete();

        return response()->json(['success' => true, 'message' => 'Pengaturan UPPM berhasil dihapus']);
    }
}
