<?php

namespace App\Http\Controllers;

use App\Models\DataSekolah;
use App\Models\Madrasah;
use Illuminate\Http\Request;

class DataSekolahController extends Controller
{
    public function siswa(Request $request)
    {
        $user = auth()->user();

        // Filter madrasah berdasarkan role user
        if ($user->role === 'admin') {
            $madrasahs = Madrasah::where('id', $user->madrasah_id)->get();

            // Untuk admin, tampilkan data untuk semua tahun 2023-2026
            $tahunList = [2023, 2024, 2025, 2026];
            $dataSekolah = DataSekolah::whereIn('tahun', $tahunList)
                ->where('madrasah_id', $user->madrasah_id)
                ->get()
                ->groupBy('tahun');

            $data = [];
            foreach ($madrasahs as $madrasah) {
                $madrasahData = ['madrasah' => $madrasah];
                foreach ($tahunList as $tahun) {
                    $tahunData = $dataSekolah->get($tahun)?->firstWhere('madrasah_id', $madrasah->id);
                    $madrasahData[$tahun] = $tahunData ? $tahunData->jumlah_siswa : 0;
                }
                $data[] = $madrasahData;
            }

            return view('data-sekolah.siswa', compact('data', 'tahunList'));
        } else {
            // Untuk super_admin, tampilkan seperti sebelumnya dengan filter tahun
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
    }

    public function guru(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Filter madrasah berdasarkan role user
        $user = auth()->user();
        if ($user->role === 'admin') {
            $madrasahs = Madrasah::where('id', $user->madrasah_id)->get();
        } else {
            $madrasahs = Madrasah::all();
        }

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

    public function updateSiswa(Request $request, $madrasahId)
    {
        // Cek apakah user admin hanya bisa update madrasah_id nya sendiri
        $user = auth()->user();
        if ($user->role === 'admin' && $user->madrasah_id != $madrasahId) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengupdate data sekolah ini'], 403);
        }

        $request->validate([
            'tahun' => 'required|integer|min:2023|max:' . (date('Y') + 1),
            'jumlah_siswa' => 'required|integer|min:0',
        ]);

        $dataSekolah = DataSekolah::updateOrCreate(
            ['madrasah_id' => $madrasahId, 'tahun' => $request->tahun],
            ['jumlah_siswa' => $request->jumlah_siswa]
        );

        return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui']);
    }

    public function updateGuru(Request $request, $madrasahId)
    {
        // Cek apakah user admin hanya bisa update madrasah_id nya sendiri
        $user = auth()->user();
        if ($user->role === 'admin' && $user->madrasah_id != $madrasahId) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengupdate data sekolah ini'], 403);
        }

        $request->validate([
            'tahun' => 'required|integer|min:2023|max:' . (date('Y') + 1),
            'jumlah_pns_sertifikasi' => 'required|integer|min:0',
            'jumlah_pns_non_sertifikasi' => 'required|integer|min:0',
            'jumlah_gty_sertifikasi' => 'required|integer|min:0',
            'jumlah_gty_sertifikasi_inpassing' => 'required|integer|min:0',
            'jumlah_gty_non_sertifikasi' => 'required|integer|min:0',
            'jumlah_gtt' => 'required|integer|min:0',
            'jumlah_pty' => 'required|integer|min:0',
            'jumlah_ptt' => 'required|integer|min:0',
        ]);

        $dataSekolah = DataSekolah::updateOrCreate(
            ['madrasah_id' => $madrasahId, 'tahun' => $request->tahun],
            [
                'jumlah_pns_sertifikasi' => $request->jumlah_pns_sertifikasi,
                'jumlah_pns_non_sertifikasi' => $request->jumlah_pns_non_sertifikasi,
                'jumlah_gty_sertifikasi' => $request->jumlah_gty_sertifikasi,
                'jumlah_gty_sertifikasi_inpassing' => $request->jumlah_gty_sertifikasi_inpassing,
                'jumlah_gty_non_sertifikasi' => $request->jumlah_gty_non_sertifikasi,
                'jumlah_gtt' => $request->jumlah_gtt,
                'jumlah_pty' => $request->jumlah_pty,
                'jumlah_ptt' => $request->jumlah_ptt,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Data guru berhasil diperbarui']);
    }
}
