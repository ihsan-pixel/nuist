<?php

namespace App\Http\Controllers;

use App\Models\UppmSetting;
use App\Models\UppmSchoolData;
use App\Models\Madrasah;
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

        foreach ($madrasahs as $madrasah) {
            // Hitung jumlah guru berdasarkan status kepegawaian dari tabel users
            $guruCounts = \App\Models\User::where('madrasah_id', $madrasah->id)
                ->whereNotNull('status_kepegawaian_id')
                ->selectRaw('status_kepegawaian_id, COUNT(*) as count')
                ->groupBy('status_kepegawaian_id')
                ->pluck('count', 'status_kepegawaian_id')
                ->toArray();

            // Map status kepegawaian ke field yang sesuai
            $jumlah_pns_sertifikasi = $guruCounts[1] ?? 0; // PNS Sertifikasi
            $jumlah_pns_non_sertifikasi = $guruCounts[2] ?? 0; // PNS Non Sertifikasi
            $jumlah_gty_sertifikasi = $guruCounts[3] ?? 0; // GTY Sertifikasi
            $jumlah_gty_sertifikasi_inpassing = $guruCounts[4] ?? 0; // GTY Sertifikasi Inpassing
            $jumlah_gty_non_sertifikasi = $guruCounts[5] ?? 0; // GTY Non Sertifikasi
            $jumlah_gtt = $guruCounts[6] ?? 0; // GTT
            $jumlah_pty = $guruCounts[7] ?? 0; // PTY
            $jumlah_ptt = $guruCounts[8] ?? 0; // PTT

            // Cek apakah ada data UPPM untuk tahun ini
            $uppmData = UppmSchoolData::where('madrasah_id', $madrasah->id)
                ->where('tahun_anggaran', $tahun)
                ->first();

            if ($uppmData) {
                // Gunakan data dari tabel uppm_school_data jika ada
                $data[] = $uppmData;
            } else {
                // Buat objek dengan data dari users
                $data[] = (object) [
                    'id' => null,
                    'madrasah' => $madrasah,
                    'tahun_anggaran' => $tahun,
                    'jumlah_siswa' => 0, // Belum ada data siswa, set 0
                    'jumlah_pns_sertifikasi' => $jumlah_pns_sertifikasi,
                    'jumlah_pns_non_sertifikasi' => $jumlah_pns_non_sertifikasi,
                    'jumlah_gty_sertifikasi' => $jumlah_gty_sertifikasi,
                    'jumlah_gty_sertifikasi_inpassing' => $jumlah_gty_sertifikasi_inpassing,
                    'jumlah_gty_non_sertifikasi' => $jumlah_gty_non_sertifikasi,
                    'jumlah_gtt' => $jumlah_gtt,
                    'jumlah_pty' => $jumlah_pty,
                    'jumlah_ptt' => $jumlah_ptt,
                    'total_nominal' => 0,
                    'status_pembayaran' => 'belum_lunas',
                    'nominal_dibayar' => 0,
                ];
            }
        }

        return view('uppm.data-sekolah', compact('data', 'tahun'));
    }

    public function perhitunganIuran(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $setting = UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'Pengaturan UPPM untuk tahun ' . $tahun . ' belum ada atau tidak aktif');
        }

        $madrasahs = Madrasah::all();
        $perhitungan = [];

        foreach ($madrasahs as $madrasah) {
            $schoolData = UppmSchoolData::where('madrasah_id', $madrasah->id)
                ->where('tahun_anggaran', $tahun)
                ->first();

            if ($schoolData) {
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

    public function invoice($id)
    {
        $schoolData = UppmSchoolData::with('madrasah')->findOrFail($id);
        $setting = UppmSetting::where('tahun_anggaran', $schoolData->tahun_anggaran)->first();

        $nominalBulanan = $this->hitungNominalBulanan($schoolData, $setting);
        $totalTahunan = $nominalBulanan * 12;

        $rincian = [
            'siswa' => $schoolData->jumlah_siswa * $setting->nominal_siswa * 12,
            'guru_tetap' => $schoolData->jumlah_guru_tetap * $setting->nominal_guru_tetap * 12,
            'guru_tidak_tetap' => $schoolData->jumlah_guru_tidak_tetap * $setting->nominal_guru_tidak_tetap * 12,
            'guru_pns' => $schoolData->jumlah_guru_pns * $setting->nominal_guru_pns * 12,
            'guru_pppk' => $schoolData->jumlah_guru_pppk * $setting->nominal_guru_pppk * 12,
            'karyawan_tetap' => $schoolData->jumlah_karyawan_tetap * $setting->nominal_karyawan_tetap * 12,
            'karyawan_tidak_tetap' => $schoolData->jumlah_karyawan_tidak_tetap * $setting->nominal_karyawan_tidak_tetap * 12,
        ];

        return view('uppm.invoice', compact('schoolData', 'setting', 'nominalBulanan', 'totalTahunan', 'rincian'));
    }

    public function downloadInvoice($id)
    {
        $schoolData = UppmSchoolData::with('madrasah')->findOrFail($id);
        $setting = UppmSetting::where('tahun_anggaran', $schoolData->tahun_anggaran)->where('aktif', true)->first();

        $nominalBulanan = $this->hitungNominalBulanan($schoolData, $setting);
        $totalTahunan = $nominalBulanan * 12;

        $rincian = [
            'siswa' => $schoolData->jumlah_siswa * $setting->nominal_siswa * 12,
            'pns_sertifikasi' => $schoolData->jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi * 12,
            'pns_non_sertifikasi' => $schoolData->jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi * 12,
            'gty_sertifikasi' => $schoolData->jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi * 12,
            'gty_sertifikasi_inpassing' => $schoolData->jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing * 12,
            'gty_non_sertifikasi' => $schoolData->jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi * 12,
            'gtt' => $schoolData->jumlah_gtt * $setting->nominal_gtt * 12,
            'pty' => $schoolData->jumlah_pty * $setting->nominal_pty * 12,
            'ptt' => $schoolData->jumlah_ptt * $setting->nominal_ptt * 12,
            'karyawan_tetap' => $schoolData->jumlah_karyawan_tetap * $setting->nominal_karyawan_tetap * 12,
            'karyawan_tidak_tetap' => $schoolData->jumlah_karyawan_tidak_tetap * $setting->nominal_karyawan_tidak_tetap * 12,
        ];

        $pdf = Pdf::loadView('uppm.invoice-pdf', compact('schoolData', 'setting', 'nominalBulanan', 'totalTahunan', 'rincian'));
        return $pdf->download('invoice-uppm-' . $schoolData->madrasah->name . '-' . $schoolData->tahun_anggaran . '.pdf');
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
