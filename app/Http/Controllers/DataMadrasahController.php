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
            foreach ($fields as $field) {
                if (!is_null($madrasah->$field)) {
                    $filled++;
                }
            }

            // Check if there is at least one tenaga pendidik for this madrasah
            $hasTeacher = User::where('madrasah_id', $madrasah->id)
                ->where('role', 'tenaga_pendidik')
                ->exists();

            if ($hasTeacher) {
                $filled++;
            }

            // Calculate percentage
            $percentage = round(($filled / 9) * 100);

            // Status guru
            $statusGuru = $hasTeacher ? '✅' : '❌';

            // Add calculated data to madrasah object
            $madrasah->completeness_percentage = $percentage;
            $madrasah->status_guru = $statusGuru;

            return $madrasah;
        });

        return view('admin.data_madrasah', compact('madrasahs'));
    }
}
