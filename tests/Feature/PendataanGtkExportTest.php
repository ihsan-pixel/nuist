<?php

namespace Tests\Feature;

use App\Exports\PendataanGtkExport;
use App\Models\GtkPendataan;
use App\Models\Madrasah;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class PendataanGtkExportTest extends TestCase
{
    public function test_workbook_preserves_grouped_headers_identifiers_and_numeric_salary(): void
    {
        $user = new User([
            'name' => '=1+1', 'gelar' => 'S.Pd.', 'nuist_id' => '000123',
            'no_hp' => '081234567890', 'email' => 'guru@example.test',
            'is_active' => false, 'tmt' => '2020-01-02',
        ]);
        $user->setRelation('madrasah', new Madrasah(['name' => 'Sekolah Uji', 'scod' => '001']));
        $user->setRelation('gtkPendataan', new GtkPendataan([
            'nik' => '0012345678901234', 'gaji_satpen' => 1500000.50,
            'gaji_sertifikasi' => 0, 'nama_mgmp' => 'Matematika',
        ]));
        $user->setRelation('mgmpMemberships', collect());

        $sheet = $this->workbookSheet(new PendataanGtkExport(collect([$user])));

        $this->assertSame('W', $sheet->getHighestColumn());
        $this->assertContains('F1:J1', $sheet->getMergeCells());
        $this->assertContains('K1:T1', $sheet->getMergeCells());
        $this->assertContains('U1:W1', $sheet->getMergeCells());
        $this->assertSame('NIK', $sheet->getCell('F2')->getValue());
        $this->assertSame('Produk kerja kolaboratif', $sheet->getCell('W2')->getValue());
        $this->assertSame('001', $sheet->getCell('B3')->getValue());
        $this->assertSame('000123', $sheet->getCell('C3')->getValue());
        $this->assertSame(DataType::TYPE_STRING, $sheet->getCell('D3')->getDataType());
        $this->assertSame('0012345678901234', $sheet->getCell('F3')->getValue());
        $this->assertSame('081234567890', $sheet->getCell('I3')->getValue());
        $this->assertSame('guru@example.test', $sheet->getCell('J3')->getValue());
        $this->assertSame('02-01-2020', $sheet->getCell('L3')->getValue());
        $this->assertSame(1500000.5, $sheet->getCell('Q3')->getValue());
        $this->assertEquals(0, $sheet->getCell('S3')->getValue());
        $this->assertSame(DataType::TYPE_NUMERIC, $sheet->getCell('S3')->getDataType());
        $this->assertNull($sheet->getCell('T3')->getValue());
        $this->assertSame('Matematika', $sheet->getCell('U3')->getValue());
        $this->assertSame('Tidak Aktif', $sheet->getCell('V3')->getValue());
    }

    public function test_empty_export_still_has_headers(): void
    {
        $sheet = $this->workbookSheet(new PendataanGtkExport(collect()));
        $this->assertSame(2, $sheet->getHighestRow());
        $this->assertSame('No', $sheet->getCell('A1')->getValue());
        $this->assertSame('F3', $sheet->getFreezePane());
    }

    private function workbookSheet(PendataanGtkExport $export)
    {
        $path = tempnam(sys_get_temp_dir(), 'gtk-export-');
        try {
            file_put_contents($path, Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX));

            return IOFactory::load($path)->getActiveSheet();
        } finally {
            unlink($path);
        }
    }
}
