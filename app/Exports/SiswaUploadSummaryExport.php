<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaUploadSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $rows
    ) {
    }

    public function headings(): array
    {
        return [
            'No',
            'SCOD',
            'Nama Sekolah / Madrasah',
            'Status Upload',
            'Jumlah Siswa',
            'Rata-rata Kelengkapan',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows;
    }
}
