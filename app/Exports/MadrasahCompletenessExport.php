<?php

namespace App\Exports;

use App\Models\Madrasah;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class MadrasahCompletenessExport implements FromCollection, WithHeadings
{
    protected $kabupaten;

    public function __construct($kabupaten)
    {
        $this->kabupaten = $kabupaten;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Madrasah',
            'Alamat',
            'Logo',
            'Latitude',
            'Longitude',
            'Map Link',
            'Polygon (koordinat)',
            'Hari KBM',
            'SCOD',
            'Status Guru',
            'Kelengkapan (%)'
        ];
    }

    public function collection()
    {
        $rows = collect();

        $madrasahs = Madrasah::where('kabupaten', $this->kabupaten)
            ->get();

        foreach ($madrasahs as $index => $madrasah) {
            // Fields to check for completeness
            $fields = ['alamat', 'logo', 'latitude', 'longitude', 'map_link', 'polygon_koordinat', 'hari_kbm', 'scod'];

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

            $fieldStatus['status_guru'] = $hasTeacher ? '✅' : '❌';

            // Calculate percentage only based on madrasah fields (7 fields)
            $percentage = round(($filled / count($fields)) * 100);

            $rows->push([
                $index + 1,
                $madrasah->name,
                $fieldStatus['alamat'],
                $fieldStatus['logo'],
                $fieldStatus['latitude'],
                $fieldStatus['longitude'],
                $fieldStatus['map_link'],
                $fieldStatus['polygon_koordinat'],
                $fieldStatus['hari_kbm'],
                $madrasah->scod,
                $fieldStatus['status_guru'],
                $percentage . '%'
            ]);
        }

        return $rows;
    }
}
