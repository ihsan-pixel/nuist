<?php

namespace App\Exports;

use App\Support\SkYayasanImportSynchronizer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SkYayasanUserImportTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'No',
            'NUIST ID',
            'Nama',
            'Gelar',
            'Tempat Lahir',
            'Tanggal Lahir',
            "NIP Ma'arif",
            'NUPTK',
            'Nomor Kartanu',
            'TMT Pertama',
            'Masa Kerja',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Program Studi',
            'Mapel/Tugas yang Diampu',
            'Penilaian Kinerja',
            'Keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                1,
                '',
                'Agus',
                '',
                'Sleman',
                '04 Mei 1990',
                "19900504201507401697",
                "718290171615",
                "34072891.0001",
                '01 Juli 2015',
                '11 Tahun 00 Bulan',
                'S1, Universitas Negeri Yogyakarta',
                '2014',
                'Pendidikan Matematika',
                'Matematika',
                '85,00',
                'Perpanjangan GTY',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $options = implode(',', SkYayasanImportSynchronizer::allowedKeteranganOptions());
                $sheet = $event->sheet->getDelegate();

                for ($row = 2; $row <= 500; $row++) {
                    $validation = $sheet->getCell("Q{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Keterangan tidak valid');
                    $validation->setError('Pilih salah satu dari 6 opsi keterangan yang tersedia.');
                    $validation->setPromptTitle('Pilih keterangan');
                    $validation->setPrompt('Gunakan salah satu nilai yang tersedia pada daftar.');
                    $validation->setFormula1('"' . $options . '"');
                }
            },
        ];
    }
}
