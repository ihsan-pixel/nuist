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

        // Get madrasah to determine hari_kbm
        $madrasah = Madrasah::find($madrasahId);
        $hariKbm = $madrasah ? $madrasah->hari_kbm : '5'; // Default to 5 if not set

        // Get all tenaga pendidik for this madrasah
        $tenagaPendidik = User::with('statusKepegawaian')
            ->where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        // Get all unique dates that have presensi for this madrasah in the specified month, filtered by working days and excluding holidays
        $rawDates = Presensi::whereHas('user', function ($q) use ($madrasahId) {
                $q->where('madrasah_id', $madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $monthNum)
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->orderBy('date', 'asc')
            ->pluck('date');

        // Filter dates to only working days based on hari_kbm and exclude holidays
        $dates = [];
        foreach ($rawDates as $date) {
            $carbonDate = Carbon::parse($date);
            $dayOfWeek = $carbonDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday

            // Check if it's a working day based on hari_kbm
            $isWorkingDay = false;
            if ($hariKbm == '5') {
                // Monday to Friday (1-5)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5);
            } elseif ($hariKbm == '6') {
                // Monday to Saturday (1-6)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6);
            }

            // Exclude holidays
            if ($isWorkingDay && !Holiday::isHoliday($date)) {
                $dates[] = $date;
            }
        }

        // Prepare data for PDF
        $data = [];
        foreach ($dates as $date) {
            foreach ($tenagaPendidik as $tp) {
                $presensi = Presensi::where('user_id', $tp->id)
                    ->whereDate('tanggal', $date)
                    ->first();

                $hari = Carbon::parse($date)->locale('id')->dayName;

                $data[] = [
                    'tanggal' => $date,
                    'hari' => $hari,
                    'user' => $tp,
                    'presensi' => $presensi,
                    'status' => $presensi ? $presensi->status : 'alpha/tidak presensi',
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.presensi-rekap', [
            'data' => $data,
            'bulan' => $bulan,
        ])->setPaper('A4', 'landscape'); // agar cukup lebar

        return $pdf->download('Rekap Presensi '.$bulan.'.pdf');
    }
}
