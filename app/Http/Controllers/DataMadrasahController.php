<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\User;
use Illuminate\Http\Request;

class DataMadrasahController extends Controller
{
    /**
     * Display the data completeness for all madrasahs
     */
    public function index()
    {
        $madrasahs = Madrasah::all()->map(function ($madrasah) {
            // Fields to check for completeness
            $fields = ['alamat', 'logo', 'latitude', 'longitude', 'map_link', 'polygon_koordinat', 'hari_kbm'];

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

            if ($hasTeacher) {
                $filled++;
                $fieldStatus['status_guru'] = '✅';
            } else {
                $fieldStatus['status_guru'] = '❌';
            }

            // Calculate percentage
            $percentage = round(($filled / 8) * 100);
            if ($percentage > 100) {
                $percentage = 100;
            }

            // Add calculated data to madrasah object
            $madrasah->completeness_percentage = $percentage;
            $madrasah->field_status = $fieldStatus;

            return $madrasah;
        });

        return view('admin.data_madrasah', compact('madrasahs'));
    }
}
