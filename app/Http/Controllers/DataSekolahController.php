<?php

namespace App\Http\Controllers;

use App\Models\DataSekolah;
use App\Models\Madrasah;
use Illuminate\Http\Request;

class DataSekolahController extends Controller
{
    public function siswa(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasahs = Madrasah::all();

        // Ambil data siswa dari database atau default 0
        $dataSekolah = DataSekolah::where('tahun', $tahun)->get()->keyBy('madrasah_id');

        $data = [];
        foreach ($madrasahs as $madrasah) {
            $dataSekolahItem = $dataSekolah->get($madrasah->id);
            $data[] = [
                'madrasah' => $madrasah,
                'tahun' => $tahun,
                'jumlah_siswa' => $dataSekolahItem ? $dataSekolahItem->jumlah_siswa : 0,
            ];
        }

        return view('data-sekolah.siswa', compact('data', 'tahun'));
    }

    public function guru(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $madrasahs = Madrasah::all();

        // Ambil data dari tabel data_sekolah
        $dataSekolah = DataSekolah::where('tahun', $tahun)->get()->keyBy('madrasah_id');

        $data = [];
        foreach ($madrasahs as $madrasah) {
            $dataSekolahItem = $dataSekolah->get($madrasah->id);

            $data[] = [
                'madrasah' => $madrasah,
                'tahun' => $tahun,
                'jumlah_pns_sertifikasi' => $dataSekolahItem ? $dataSekolahItem->jumlah_pns_sertifikasi : 0,
                'jumlah_pns_non_sertifikasi' => $dataSekolahItem ? $dataSekolahItem->jumlah_pns_non_sertifikasi : 0,
                'jumlah_gty_sertifikasi' => $dataSekolahItem ? $dataSekolahItem->jumlah_gty_sertifikasi : 0,
                'jumlah_gty_sertifikasi_inpassing' => $dataSekolahItem ? $dataSekolahItem->jumlah_gty_sertifikasi_inpassing : 0,
                'jumlah_gty_non_sertifikasi' => $dataSekolahItem ? $dataSekolahItem->jumlah_gty_non_sertifikasi : 0,
                'jumlah_gtt' => $dataSekolahItem ? $dataSekolahItem->jumlah_gtt : 0,
                'jumlah_pty' => $dataSekolahItem ? $dataSekolahItem->jumlah_pty : 0,
                'jumlah_ptt' => $dataSekolahItem ? $dataSekolahItem->jumlah_ptt : 0,
                'total_guru' => $dataSekolahItem ? (
                    $dataSekolahItem->jumlah_pns_sertifikasi +
                    $dataSekolahItem->jumlah_pns_non_sertifikasi +
                    $dataSekolahItem->jumlah_gty_sertifikasi +
                    $dataSekolahItem->jumlah_gty_sertifikasi_inpassing +
                    $dataSekolahItem->jumlah_gty_non_sertifikasi +
                    $dataSekolahItem->jumlah_gtt +
                    $dataSekolahItem->jumlah_pty +
                    $dataSekolahItem->jumlah_ptt
                ) : 0,
            ];
        }

        return view('data-sekolah.guru', compact('data', 'tahun'));
    }
}
