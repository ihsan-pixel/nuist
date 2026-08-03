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
            'Total Guru + Pegawai',
            'Kelengkapan Data Users',
            'Presensi Kehadiran',
            'Disiplin Kehadiran',
            'Periode Aktif',
            'Guru Sudah Jadwal',
            'Guru Belum Jadwal',
            'Cakupan Jadwal Guru',
            'Jurnal Mengajar',
            'Disiplin Jurnal',
            'Pengajuan SK Yayasan',
            'Kelengkapan SK',
            'Data Siswa',
            'Kelengkapan Siswa',
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
                $row['total_teacher_employees'] . ' (Guru ' . $row['total_teachers'] . ', Pegawai ' . $row['total_employees'] . ')',
                number_format((float) $row['user_completion_percentage'], 1) . '%',
                $row['actual_attendance'] . ' / ' . $row['expected_attendance'] . ' (' . $row['attendance_month_label'] . ')',
                number_format((float) $row['attendance_discipline_percentage'], 1) . '%',
                $row['active_period_status'] . ' - ' . ($row['has_active_period'] ? $row['active_period_label'] : $row['latest_period_label']),
                $row['total_teachers_with_schedule'] . ' dari ' . $row['eligible_teacher_total'],
                $row['total_teachers_without_schedule'],
                number_format((float) $row['schedule_coverage_percentage'], 1) . '%',
                $row['total_teaching_attendances'] . ' / ' . $row['journal_expected_meetings'],
                number_format((float) $row['journal_discipline_percentage'], 1) . '%',
                $row['total_sk_submissions'],
                number_format((float) $row['sk_completeness_percentage'], 1) . '% (Valid ' . $row['sk_latest_batch_valid_rows'] . '/' . $row['sk_latest_batch_total_rows'] . ')',
                $row['total_students'],
                number_format((float) $row['student_completion_percentage'], 1) . '%',
                number_format((float) $row['overall_completion_percentage'], 1) . '%',
                $row['rank'],
            ];
        });
    }
}
