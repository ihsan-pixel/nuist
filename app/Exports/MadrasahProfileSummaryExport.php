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
            'Ranking',
            'SCOD',
            'Nama Sekolah / Madrasah',
            'Yayasan',
            'Kabupaten',
            'Persentase Kelengkapan',
            'Indikator Lengkap',
            'Jumlah Guru',
            'Jumlah Pegawai',
            'Total Guru + Pegawai',
            'Tertib Presensi',
            'Jumlah Periode Jadwal Mengajar',
            'Periode Acuan',
            'Status Periode',
            'Jadwal Mengajar Sesuai Periode',
            'Presensi Jurnal Mengajar Sesuai Periode',
            'Data Jumlah Siswa per Kelas',
            'Total Siswa pada Data Kelas',
            'Pengajuan SK',
            'Data Siswa',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->map(function (array $row) {
            return [
                $row['rank'],
                $row['scod'],
                $row['school_name'],
                $row['yayasan_name'],
                $row['kabupaten'],
                $row['overall_completion_percentage'] . '%',
                $row['filled_indicator_count'] . '/8',
                $row['total_teachers'],
                $row['total_employees'],
                $row['total_teacher_employees'],
                $row['presensi_config_percentage'] . '% (' . $row['presensi_config_filled'] . '/' . $row['presensi_config_total'] . ')',
                $row['total_periods'],
                $row['selected_period_label'],
                $row['selected_period_scope'],
                $row['total_teaching_schedules'],
                $row['total_teaching_attendances'],
                $row['total_class_student_records'],
                $row['total_class_students'],
                $row['total_sk_submissions'],
                $row['total_students'],
            ];
        });
    }
}
