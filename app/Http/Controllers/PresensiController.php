<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Barryvdh\DomPDF\Facade\Pdf;

class PresensiController extends Controller
{
    public function index()
    {
        return view('presensi.index');
    }

    public function create()
    {
        return view('presensi.create');
    }

    public function store(Request $request)
    {
        // Handle presensi store logic
        return redirect()->route('presensi.index');
    }

    public function laporan()
    {
        return view('presensi.laporan');
    }

    public function pdfRekap($madrasahId, $bulan)
    {
        // Parsing bulan
        if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            list($year, $monthNum) = explode('-', $bulan);
        } elseif (preg_match('/^\d{1,2}$/', $bulan)) {
            $year = date('Y');
            $monthNum = $bulan;
        } else {
            $bulanParts = explode(' ', $bulan);
            $monthName = $bulanParts[0];
            $year = $bulanParts[1] ?? date('Y');
            $monthNum = date('m', strtotime("$monthName 1"));
        }

        // Ambil data presensi
        $presensis = Presensi::with('user.statusKepegawaian')
            ->whereHas('user', function ($q) use ($madrasahId) {
                $q->where('madrasah_id', $madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $monthNum)
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.presensi-rekap', [
            'presensis' => $presensis,
            'bulan' => $bulan,
        ])->setPaper('A4', 'landscape'); // agar cukup lebar

        return $pdf->download('Rekap Presensi '.$bulan.'.pdf');
    }
}
