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
            'alamat' => $row[1] ?? null,
            'latitude' => $row[2] ?? null,
            'longitude' => $row[3] ?? null,
            'map_link' => $row[4] ?? null,
            'logo' => $row[5] ?? null,
        ]);
    }
}
