<?php

namespace App\Imports;

use App\Models\StatusKepegawaian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StatusKepegawaianImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StatusKepegawaian([
            'name' => $row['name'],
        ]);
    }
}
