<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class BpppmnuAttendanceExport extends StringValueBinder implements FromCollection, WithCustomValueBinder, WithHeadings
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Nama', 'ID NUIST', 'Jabatan', 'Status', 'Waktu Hadir (WIB)'];
    }

    public function collection(): Collection
    {
        return $this->rows->map(fn ($row) => [$row->name, $row->nuist_id, $row->jabatan, $row->status, $row->attended_at]);
    }
}
