<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class GenerateTeachingScheduleTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:teaching-schedule-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Excel template for Teaching Schedule import';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating Teaching Schedule Excel template...');

        $filename = 'public/templates/teaching_schedule_import_template.xlsx';

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'school_scod',
            'teacher_name',
            'day',
            'subject',
            'class_name',
            'start_time',
            'end_time'
        ];

        // Add headers to first row
        foreach ($headers as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
        }

        // Add example data using generic examples (database connection not available during command execution)
        $exampleData = [
            ['SCOD001', 'Nama Guru 1', 'Senin', 'Matematika', 'Kelas 1A', '08:00', '09:00'],
            ['SCOD001', 'Nama Guru 2', 'Selasa', 'Bahasa Indonesia', 'Kelas 1B', '09:00', '10:00'],
            ['SCOD002', 'Nama Guru 3', 'Rabu', 'TIK', 'Kelas 2A', '10:00', '11:00']
        ];

        foreach ($exampleData as $rowIndex => $rowData) {
            foreach ($rowData as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 2, $value);
            }
        }

        // Auto-size columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Save the file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'teaching_schedule_template');
        $writer->save($tempFile);

        // Move to public/templates
        $directory = dirname($filename);
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        Storage::put($filename, file_get_contents($tempFile));
        unlink($tempFile);

        $this->info('✅ Teaching Schedule Excel template generated successfully!');
        $this->info('📁 Template saved as: ' . $filename);
    }
}
