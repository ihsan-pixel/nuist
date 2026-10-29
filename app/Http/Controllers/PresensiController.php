<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Madrasah;
use App\Models\Holiday;
use Carbon\Carbon;
use App\Models\User;
use App\Services\ExternalTeachingPermissionService;
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
        [$year, $monthNum] = $this->parseReportMonth($bulan);

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
            if ($this->isWorkingDay($carbonDate, $hariKbm) && !Holiday::isHoliday($date)) {
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
                    'status' => $presensi
                        ? $presensi->status
                        : (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($tp, Carbon::parse($date)) ? 'izin' : 'alpha/tidak presensi'),
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.presensi-rekap', [
            'data' => $data,
            'bulan' => $bulan,
        ])->setPaper('A4', 'landscape'); // agar cukup lebar

        return $pdf->download('Rekap Presensi '.$bulan.'.pdf');
    }

    private function parseReportMonth(string $bulan): array
    {
        if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            return explode('-', $bulan);
        }

        if (preg_match('/^\d{1,2}$/', $bulan)) {
            return [date('Y'), $bulan];
        }

        $parts = explode(' ', $bulan);
        $year = $parts[1] ?? date('Y');
        $month = date('m', strtotime(($parts[0] ?? '') . ' 1'));

        return [$year, $month];
    }

    private function isWorkingDay(Carbon $date, string|int|null $hariKbm): bool
    {
        return match ((string) $hariKbm) {
            '5' => $date->dayOfWeek >= Carbon::MONDAY && $date->dayOfWeek <= Carbon::FRIDAY,
            '6' => $date->dayOfWeek >= Carbon::MONDAY && $date->dayOfWeek <= Carbon::SATURDAY,
            default => false,
        };
    }
}
