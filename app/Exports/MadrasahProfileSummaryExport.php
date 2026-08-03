<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MadrasahProfileSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'Jumlah Guru',
            'Jumlah Pegawai',
            'Total Guru + Pegawai',
            'Pengajuan SK',
            'Data Siswa',
            'Persentase',
            'Rank',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->map(function (array $row) {
            return [
                $row['rank'],
                $row['scod'],
                $row['school_name'],
                $row['total_teachers'] . ' (' . $row['total_teachers_percentage'] . '%)',
                $row['total_employees'] . ' (' . $row['total_employees_percentage'] . '%)',
                $row['total_teacher_employees'] . ' (' . $row['total_teacher_employees_percentage'] . '%)',
                $row['total_sk_submissions'] . ' (' . $row['total_sk_submissions_percentage'] . '%)',
                $row['total_students'] . ' (' . $row['total_students_percentage'] . '%)',
                $row['overall_completion_percentage'] . '%',
                $row['rank'],
            ];
        });
    }
}
