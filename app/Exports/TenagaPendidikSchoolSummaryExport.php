<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TenagaPendidikSchoolSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(private readonly Collection $rows)
    {
    }

    public function headings(): array
    {
        return [
            'No',
            'SCOD',
            'Nama Sekolah/Madrasah',
            'Jumlah Guru (GTY/GTT)',
            'Jumlah Karyawan (PTY/PTT)',
            'Total',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->map(function (array $row) {
            return [
                $row['no'],
                $row['scod'],
                $row['madrasah'],
                $row['jumlah_guru'],
                $row['jumlah_karyawan'],
                $row['total'],
            ];
        });
    }
}
