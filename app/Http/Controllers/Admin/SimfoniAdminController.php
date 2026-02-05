<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simfoni;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SimfoniAdminController extends Controller
{
    public function index()
    {
        $simfonis = Simfoni::with('user.madrasah')->get();

        // Calculate Simfoni completion report
        $kabupatenOrder = [
            'Kabupaten Bantul',
            'Kabupaten Gunungkidul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $laporanData = [];

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_sudah' => 0,
                'total_belum' => 0,
                'total_tenaga_pendidik' => 0,
                'persentase' => 0
            ];

            foreach ($madrasahs as $madrasah) {
                $totalTeachers = User::where('madrasah_id', $madrasah->id)
                    ->where('role', 'tenaga_pendidik')
                    ->count();

                $teachersWithSimfoni = Simfoni::whereHas('user', function ($q) use ($madrasah) {
                    $q->where('madrasah_id', $madrasah->id)
                      ->where('role', 'tenaga_pendidik');
                })->count();

                $teachersWithoutSimfoni = $totalTeachers - $teachersWithSimfoni;

                $persentase = $totalTeachers > 0 ? ($teachersWithSimfoni / $totalTeachers) * 100 : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'sudah' => $teachersWithSimfoni,
                    'belum' => $teachersWithoutSimfoni,
                    'total' => $totalTeachers,
                    'persentase' => $persentase
                ];

                $kabupatenData['total_sudah'] += $teachersWithSimfoni;
                $kabupatenData['total_belum'] += $teachersWithoutSimfoni;
                $kabupatenData['total_tenaga_pendidik'] += $totalTeachers;
            }

            $kabupatenData['persentase'] = $kabupatenData['total_tenaga_pendidik'] > 0
                ? ($kabupatenData['total_sudah'] / $kabupatenData['total_tenaga_pendidik']) * 100
                : 0;

            $laporanData[] = $kabupatenData;
        }

        return view('admin.simfoni.index', compact('simfonis', 'laporanData'));
    }

    /**
     * Generate PDF for Simfoni record
     */
    public function pdfSimfoni($id)
    {
        $simfoni = Simfoni::with('user.madrasah')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.simfoni-template', compact('simfoni'))
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
            ->header('Content-Disposition', 'inline; filename="SIMFONI_' . ($simfoni->nama_lengkap_gelar ?? 'GTK') . '.pdf"');
    }
}
