<?php

namespace App\Imports;

use App\Models\TahunPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;

class TahunPelajaranImport implements ToModel
{
    public function model(array $row)
    {
        return new TahunPelajaran([
            'name' => $row[0],
        ]);
    }
}
