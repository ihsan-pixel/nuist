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

        // Ambil semua PPDB setting untuk tahun ini
        $ppdbSettings = PPDBSetting::where('tahun', $tahun)
            ->with('sekolah')
            ->get();

        // Statistik keseluruhan
        $totalSekolah = $ppdbSettings->count();
        $totalPendaftar = PPDBPendaftar::whereIn(
            'ppdb_setting_id',
            $ppdbSettings->pluck('id')
        )->count();

        $statistik = [
            'total_sekolah' => $totalSekolah,
            'total_pendaftar' => $totalPendaftar,
            'pending' => PPDBPendaftar::whereIn(
                'ppdb_setting_id',
                $ppdbSettings->pluck('id')
            )->where('status', 'pending')->count(),
            'verifikasi' => PPDBPendaftar::whereIn(
                'ppdb_setting_id',
                $ppdbSettings->pluck('id')
            )->where('status', 'verifikasi')->count(),
            'lulus' => PPDBPendaftar::whereIn(
                'ppdb_setting_id',
                $ppdbSettings->pluck('id')
            )->where('status', 'lulus')->count(),
            'tidak_lulus' => PPDBPendaftar::whereIn(
                'ppdb_setting_id',
                $ppdbSettings->pluck('id')
            )->where('status', 'tidak_lulus')->count(),
        ];

        // Detail per sekolah
        $detailSekolah = $ppdbSettings->map(function ($setting) {
            return [
                'sekolah' => $setting->sekolah,
                'slug' => $setting->slug,
                'total' => $setting->pendaftars()->count(),
                'lulus' => $setting->pendaftars()->where('status', 'lulus')->count(),
                'tidak_lulus' => $setting->pendaftars()->where('status', 'tidak_lulus')->count(),
                'pending' => $setting->pendaftars()->where('status', 'pending')->count(),
                'verifikasi' => $setting->pendaftars()->where('status', 'verifikasi')->count(),
            ];
        });

        return view('ppdb.dashboard.lp', compact('statistik', 'detailSekolah', 'ppdbSettings'));
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