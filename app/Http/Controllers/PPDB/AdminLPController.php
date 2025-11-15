<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use Illuminate\Http\Request;

class AdminLPController extends Controller
{
    /**
     * Dashboard Admin LP. Ma'arif
     * Monitoring semua sekolah, statistik, dan grafik pendaftar
     */
    public function index()
    {
        $tahun = now()->year;

        // Ambil semua madrasah dan PPDB setting untuk tahun ini
        $madrasahs = \App\Models\Madrasah::with(['ppdbSettings' => function($query) use ($tahun) {
            $query->where('tahun', $tahun);
        }])->get();

        // Statistik keseluruhan
        $totalSekolah = $madrasahs->count();
        $totalPendaftar = 0;
        $totalBuka = 0;

        $statistik = [
            'total_sekolah' => $totalSekolah,
            'total_pendaftar' => 0,
            'pending' => 0,
            'verifikasi' => 0,
            'lulus' => 0,
            'tidak_lulus' => 0,
        ];

        // Detail per sekolah
        $detailSekolah = $madrasahs->map(function ($madrasah) use (&$statistik, &$totalPendaftar, &$totalBuka) {
            $ppdbSetting = $madrasah->ppdbSettings->first();

            $data = [
                'sekolah' => $madrasah,
                'ppdb_setting' => $ppdbSetting,
                'total' => 0,
                'lulus' => 0,
                'tidak_lulus' => 0,
                'pending' => 0,
                'verifikasi' => 0,
                'status_ppdb' => 'tidak_aktif',
            ];

            if ($ppdbSetting) {
                $pendaftars = $ppdbSetting->pendaftars();

                $data['total'] = $pendaftars->count();
                $data['lulus'] = $pendaftars->where('status', 'lulus')->count();
                $data['tidak_lulus'] = $pendaftars->where('status', 'tidak_lulus')->count();
                $data['pending'] = $pendaftars->where('status', 'pending')->count();
                $data['verifikasi'] = $pendaftars->where('status', 'verifikasi')->count();

                // Cek status PPDB
                $now = now();
                if ($ppdbSetting->status === 'buka' &&
                    $now->between($ppdbSetting->jadwal_buka, $ppdbSetting->jadwal_tutup)) {
                    $data['status_ppdb'] = 'buka';
                    $totalBuka++;
                } elseif ($ppdbSetting->status === 'buka' && $now->gt($ppdbSetting->jadwal_tutup)) {
                    $data['status_ppdb'] = 'tutup';
                } else {
                    $data['status_ppdb'] = 'belum_buka';
                }

                // Update statistik keseluruhan
                $statistik['total_pendaftar'] += $data['total'];
                $statistik['pending'] += $data['pending'];
                $statistik['verifikasi'] += $data['verifikasi'];
                $statistik['lulus'] += $data['lulus'];
                $statistik['tidak_lulus'] += $data['tidak_lulus'];
            }

            return $data;
        });

        $statistik['total_buka'] = $totalBuka;

        return view('ppdb.dashboard.lp', compact('statistik', 'detailSekolah', 'madrasahs'));
    }

    /**
     * Detail PPDB per sekolah untuk LP
     */
    public function detailSekolah($slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->firstOrFail();

        $pendaftars = $ppdbSetting->pendaftars()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistik = [
            'total' => $ppdbSetting->pendaftars()->count(),
            'lulus' => $ppdbSetting->pendaftars()->where('status', 'lulus')->count(),
            'tidak_lulus' => $ppdbSetting->pendaftars()->where('status', 'tidak_lulus')->count(),
            'pending' => $ppdbSetting->pendaftars()->where('status', 'pending')->count(),
            'verifikasi' => $ppdbSetting->pendaftars()->where('status', 'verifikasi')->count(),
        ];

        return view('ppdb.dashboard.lp-detail', compact('ppdbSetting', 'pendaftars', 'statistik'));
    }
}
