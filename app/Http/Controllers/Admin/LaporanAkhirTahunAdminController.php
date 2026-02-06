<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhirTahunKepalaSekolah;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class LaporanAkhirTahunAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only super admin can access this
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only super admin can access this feature.');
        }

        // Get all laporan akhir tahun
        $laporans = LaporanAkhirTahunKepalaSekolah::with(['user.madrasah'])
            ->orderBy('tahun_pelaporan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get completion data per madrasah
        $madrasahs = Madrasah::with(['tenagaPendidikUsers' => function($query) {
            $query->where('ketugasan', 'kepala madrasah/sekolah');
        }])->get();

        $laporanData = [];
        $kabupatenGroups = $madrasahs->groupBy(function($madrasah) {
            return $madrasah->kabupaten ?? 'Tidak Diketahui';
        });

        foreach ($kabupatenGroups as $kabupaten => $madrasahGroup) {
            $kabupatenData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_sudah' => 0,
                'total_belum' => 0,
                'total_kepala_sekolah' => 0,
                'persentase' => 0
            ];

            foreach ($madrasahGroup as $madrasah) {
                $kepalaSekolah = $madrasah->tenagaPendidikUsers->first();
                $sudahIsi = 0;
                $belumIsi = 0;

                if ($kepalaSekolah) {
                    $laporanExists = LaporanAkhirTahunKepalaSekolah::where('user_id', $kepalaSekolah->id)
                        ->where('tahun_pelaporan', 2025)
                        ->exists();

                    if ($laporanExists) {
                        $sudahIsi = 1;
                    } else {
                        $belumIsi = 1;
                    }
                } else {
                    $belumIsi = 1;
                }

                $total = $sudahIsi + $belumIsi;
                $persentase = $total > 0 ? ($sudahIsi / $total) * 100 : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod ?? '-',
                    'nama' => $madrasah->name,
                    'sudah' => $sudahIsi,
                    'belum' => $belumIsi,
                    'total' => $total,
                    'persentase' => $persentase
                ];

                $kabupatenData['total_sudah'] += $sudahIsi;
                $kabupatenData['total_belum'] += $belumIsi;
                $kabupatenData['total_kepala_sekolah'] += $total;
            }

            $kabupatenData['persentase'] = $kabupatenData['total_kepala_sekolah'] > 0
                ? ($kabupatenData['total_sudah'] / $kabupatenData['total_kepala_sekolah']) * 100
                : 0;

            $laporanData[] = $kabupatenData;
        }

        return view('admin.laporan-akhir-tahun.index', compact('laporans', 'laporanData'));
    }

    /**
     * Generate PDF for a specific laporan akhir tahun
     */
    public function pdf($id)
    {
        // Only super admin can access this
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only super admin can access this feature.');
        }

        // Get the laporan with user relationship
        $laporan = LaporanAkhirTahunKepalaSekolah::with('user')->findOrFail($id);

        // Generate PDF using the template
        $pdf = PDF::loadView('pdf.laporan-akhir-tahun-template', compact('laporan'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'margin_top' => 1,
                'margin_right' => 1,
                'margin_bottom' => 1,
                'margin_left' => 1,
            ]);

        // Stream PDF to browser (inline view)
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan_Akhir_Tahun_' . ($laporan->nama_kepala_sekolah_madrasah ?? 'Kepala_Sekolah') . '.pdf"');
    }
}
