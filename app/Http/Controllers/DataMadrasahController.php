<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MadrasahCompletenessExport;
use Illuminate\Support\Str;

class DataMadrasahController extends Controller
{
    /**
     * Display the data completeness for all madrasahs
     */
    public function index()
    {
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $madrasahs = Madrasah::orderByRaw("FIELD(kabupaten, '" . implode("','", $kabupatenOrder) . "')")
            ->get()
            ->groupBy('kabupaten')
            ->map(function ($group) use ($kabupatenOrder) {
                return $group->sortBy(function ($madrasah) {
                    return $madrasah->scod ?? PHP_INT_MAX;
                })->map(function ($madrasah) {
                    // Fields to check for completeness
                    $fields = ['alamat', 'logo', 'latitude', 'longitude', 'map_link', 'polygon_koordinat', 'polygon_koordinat_2', 'enable_dual_polygon', 'hari_kbm', 'scod'];

                    $filled = 0;
                    $fieldStatus = [];

                    foreach ($fields as $field) {
                        if (!is_null($madrasah->$field)) {
                            $filled++;
                            $fieldStatus[$field] = '✅';
                        } else {
                            $fieldStatus[$field] = '❌';
                        }
                    }

                    // Check if there is at least one tenaga pendidik for this madrasah
                    $hasTeacher = User::where('madrasah_id', $madrasah->id)
                        ->where('role', 'tenaga_pendidik')
                        ->exists();

                    // Status guru column is not changed, so just show check or cross based on existence
                    $fieldStatus['status_guru'] = $hasTeacher ? '✅' : '❌';

                    // Calculate percentage only based on madrasah fields (7 fields)
                    $percentage = round(($filled / count($fields)) * 100);
                    if ($percentage > 100) {
                        $percentage = 100;
                    }

                    // Add calculated data to madrasah object
                    $madrasah->completeness_percentage = $percentage;
                    $madrasah->field_status = $fieldStatus;

                    return $madrasah;
                });
            });

        $tenagaPendidikData = [];
        foreach ($kabupatenOrder as $kabupaten) {
            $tenagaPendidikData[$kabupaten] = Madrasah::where('kabupaten', $kabupaten)
                ->with(['users' => function($query) {
                    $query->where('role', 'tenaga_pendidik')->with('statusKepegawaian');
                }])
                ->orderByRaw('CAST(scod AS UNSIGNED)')
                ->get()
                ->map(function ($madrasah) {
                    $data = [
                        'scod' => $madrasah->scod,
                        'name' => $madrasah->name,
                        'pns_sertifikasi' => 0,
                        'pns_non_sertifikasi' => 0,
                        'gty_sertifikasi_inpassing' => 0,
                        'gty_sertifikasi' => 0,
                        'gty' => 0,
                        'gtt' => 0,
                        'pty' => 0,
                        'ptt' => 0,
                        'tidak_diketahui' => 0,
                        'total' => 0,
                    ];
                    foreach ($madrasah->users as $user) {
                        $status = $user->statusKepegawaian->name ?? 'Tidak Diketahui';
                        $data['total'] += 1;
                        switch ($status) {
                            case 'PNS Sertifkasi':
                                $data['pns_sertifikasi'] += 1;
                                break;
                            case 'PNS Non Sertifkasi':
                                $data['pns_non_sertifikasi'] += 1;
                                break;
                            case 'GTY Sertifikasi Inpassing':
                                $data['gty_sertifikasi_inpassing'] += 1;
                                break;
                            case 'GTY Sertifikasi':
                                $data['gty_sertifikasi'] += 1;
                                break;
                            case 'GTY Non Sertifikasi':
                                $data['gty'] += 1;
                                break;
                            case 'GTT':
                                $data['gtt'] += 1;
                                break;
                            case 'PTY':
                                $data['pty'] += 1;
                                break;
                            case 'PTT':
                                $data['ptt'] += 1;
                                break;
                            default:
                                $data['tidak_diketahui'] += 1;
                                break;
                        }
                    }
                    return $data;
                });
        }

        return view('admin.data_madrasah', compact('madrasahs', 'kabupatenOrder', 'tenagaPendidikData'));
    }

    /**
     * Export madrasah completeness data for a specific kabupaten to Excel
     */
    public function export(Request $request)
    {
        $kabupaten = $request->query('kabupaten');

        if (!$kabupaten) {
            abort(400, 'Kabupaten parameter is required.');
        }

        return Excel::download(
            new MadrasahCompletenessExport($kabupaten),
            'Kelengkapan_Data_' . Str::slug($kabupaten) . '.xlsx'
        );
    }
}
