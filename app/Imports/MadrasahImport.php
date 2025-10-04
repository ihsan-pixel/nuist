<?php

namespace App\Imports;

use App\Models\Madrasah;
use Maatwebsite\Excel\Concerns\ToModel;

class MadrasahImport implements ToModel
{
    public function model(array $row)
    {
        return new Madrasah([
            'name' => $row[0] ?? null,
            'kabupaten' => $row[1] ?? null,
            'alamat' => $row[2] ?? null,
            'latitude' => $row[3] ?? null,
            'longitude' => $row[4] ?? null,
            'map_link' => $row[5] ?? null,
            'logo' => $row[6] ?? null,
        ]);
    }
}
