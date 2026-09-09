<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PendataanGtkExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting, WithCustomValueBinder, WithStrictNullComparison
{
    public function __construct(private readonly Collection $gtk)
    {
    }

    public function headings(): array
    {
        return [
            ['No', 'SCOD', 'NUIST ID', 'Nama dan Gelar', 'Asal Sekolah', 'Data Identitas GTK', '', '', '', '', 'Data Kepegawaian', '', '', '', '', '', '', '', '', '', 'MGMP', '', ''],
            ['', '', '', '', '', 'NIK', 'Alamat', 'Gol. Darah', 'No HP', 'Email Aktif', 'TMT SK 1', 'TMT SK Terakhir', 'Nomor SK Pertama', 'Tahun SK Pertama', 'Masa Kerja', 'Jabatan', 'Gaji dari Satpen (Rp)', 'No. Sertifikasi Pendidik', 'Sertifikasi (Rp)', 'Tunjangan rerata per bulan (Rp)', 'Nama MGMP', 'Status Keaktifan', 'Produk kerja kolaboratif'],
        ];
    }

    public function collection(): Collection
    {
        return $this->gtk->values()->map(function (User $user, int $index) {
            $data = $user->gtkPendataan;
            $mgmp = $data?->nama_mgmp ?: $user->mgmpMemberships
                ->map(fn ($membership) => $membership->mgmpGroup?->name)
                ->filter()->unique()->implode(', ');

            return [
                $index + 1,
                $user->madrasah?->scod,
                $user->nuist_id,
                $user->name . ($user->gelar ? ', ' . $user->gelar : ''),
                $user->madrasah?->name,
                $data?->nik,
                $user->alamat,
                $data?->gol_darah,
                $user->no_hp,
                $data?->email_aktif ?: $user->email,
                $data?->tmt_sk_pertama?->format('d-m-Y'),
                ($data?->tmt_sk_terakhir ?? $user->tmt)?->format('d-m-Y'),
                $data?->nomor_sk_pertama,
                $data?->tahun_sk_pertama,
                $user->masa_kerja,
                $user->jabatan ?: $user->ketugasan,
                $data?->gaji_satpen === null ? null : (float) $data->gaji_satpen,
                $data?->nomor_sertifikasi_pendidik,
                $data?->gaji_sertifikasi === null ? null : (float) $data->gaji_sertifikasi,
                $data?->tunjangan_rerata_bulanan === null ? null : (float) $data->tunjangan_rerata_bulanan,
                $mgmp,
                ($user->is_active ?? true) ? 'Aktif' : 'Tidak Aktif',
                $data?->produk_kerja_kolaboratif,
            ];
        });
    }

    public function bindValue(Cell $cell, $value): bool
    {
        // Preserve identifiers and prevent user-entered text from becoming formulas.
        if ($value !== null && ($cell->getRow() <= 2 || !in_array($cell->getColumn(), ['A', 'N', 'Q', 'S', 'T'], true))) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return ['Q' => '#,##0.00', 'S' => '#,##0.00', 'T' => '#,##0.00'];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            foreach (['A1:A2', 'B1:B2', 'C1:C2', 'D1:D2', 'E1:E2', 'F1:J1', 'K1:T1', 'U1:W1'] as $range) {
                $sheet->mergeCells($range);
            }
            $lastRow = max(2, $sheet->getHighestRow());
            $sheet->getStyle("A1:W{$lastRow}")->applyFromArray([
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
            ]);
            $sheet->getStyle('A1:W2')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
            ]);
            foreach (['F1:J2' => 'EFF6FF', 'K1:T2' => 'F0FDF4', 'U1:W2' => 'FFF7ED'] as $range => $color) {
                $sheet->getStyle($range)->getFill()->getStartColor()->setRGB($color);
            }
            foreach (range('A', 'W') as $column) {
                $sheet->getColumnDimension($column)->setWidth(22);
            }
            foreach (['D', 'E', 'G', 'J', 'W'] as $column) {
                $sheet->getColumnDimension($column)->setWidth(36);
            }
            $sheet->getColumnDimension('A')->setWidth(7);
            $sheet->getColumnDimension('B')->setWidth(12);
            $sheet->getRowDimension(1)->setRowHeight(28);
            $sheet->getRowDimension(2)->setRowHeight(45);
            $sheet->freezePane('F3');
        }];
    }
}
