<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class BpppmnuGuestInvitationTemplateExport extends StringValueBinder implements FromArray, WithCustomValueBinder, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return ['Nama', 'Instansi', 'Nomor HP'];
    }

    public function array(): array
    {
        return [
            ['Ahmad Fauzi', 'MI Contoh', '081234567890'],
            ['Siti Aminah', 'Fatayat NU', '085678901234'],
        ];
    }
}
