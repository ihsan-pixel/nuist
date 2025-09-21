<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateTenagaPendidikTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tenaga-pendidik-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Excel template files for Tenaga Pendidik import';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating Tenaga Pendidik Excel templates...');

        // Template kosong
        $this->generateEmptyTemplate();

        // Template contoh data guru
        $this->generateGuruTemplate();

        // Template contoh data kepala madrasah
        $this->generateKepalaMadrasahTemplate();

        $this->info('✅ All Excel templates generated successfully!');
        $this->info('📁 Templates saved in: public/template/');
    }

    private function generateEmptyTemplate()
    {
        $filename = 'public/template/tenaga_pendidik_template.xlsx';

        // Header untuk template kosong
        $headers = [
            'nama',
            'email',
            'tempat_lahir',
            'tanggal_lahir',
            'no_hp',
            'kartanu',
            'nip',
            'nuptk',
            'npk',
            'madrasah_id',
            'pendidikan_terakhir',
            'tahun_lulus',
            'program_studi',
            'status_kepegawaian_id',
            'tmt',
            'ketugasan',
            'mengajar',
            'alamat'
        ];

        $this->createExcelFile($filename, $headers, []);
        $this->info('📄 Empty template generated: tenaga_pendidik_template.xlsx');
    }

    private function generateGuruTemplate()
    {
        $filename = 'public/template/tenaga_pendidik_contoh_guru.xlsx';

        $headers = [
            'nama',
            'email',
            'tempat_lahir',
            'tanggal_lahir',
            'no_hp',
            'kartanu',
            'nip',
            'nuptk',
            'npk',
            'madrasah_id',
            'pendidikan_terakhir',
            'tahun_lulus',
            'program_studi',
            'status_kepegawaian_id',
            'tmt',
            'ketugasan',
            'mengajar',
            'alamat'
        ];

        $data = [
            [
                'Dr. Ahmad Fauzi, S.Pd., M.Pd.',
                'ahmad.fauzi@nuist.sch.id',
                'Yogyakarta',
                '1985-03-15',
                '081234567890',
                '123456789',
                '198503152010011001',
                '1234567890123456',
                'A001',
                '1',
                'S2 Pendidikan Matematika',
                '2010',
                'Matematika Terapan',
                '1',
                '2010-07-01',
                'tenaga pendidik',
                'Guru Matematika',
                'Jl. Pendidikan No. 123, Sleman, Yogyakarta'
            ],
            [
                'Siti Nurhaliza, S.Pd.',
                'siti.nurhaliza@nuist.sch.id',
                'Sleman',
                '1990-08-20',
                '081987654321',
                '987654321',
                '199008202015022002',
                '6543210987654321',
                'B002',
                '1',
                'S1 Pendidikan Bahasa',
                '2012',
                'Bahasa dan Sastra Indonesia',
                '2',
                '2015-01-15',
                'tenaga pendidik',
                'Guru Bahasa Indonesia',
                'Jl. Bahasa No. 456, Ngaglik, Sleman'
            ],
            [
                'Muhammad Rizki, S.Kom.',
                'muhammad.rizki@nuist.sch.id',
                'Bantul',
                '1988-12-10',
                '085678912345',
                '456789123',
                '198812102012011003',
                '9876543210987654',
                'C003',
                '1',
                'S1 Teknik Informatika',
                '2011',
                'Sistem Informasi',
                '3',
                '2012-08-01',
                'tenaga pendidik',
                'Guru TIK',
                'Jl. Teknologi No. 789, Bantul, Yogyakarta'
            ]
        ];

        $this->createExcelFile($filename, $headers, $data);
        $this->info('📄 Guru template generated: tenaga_pendidik_contoh_guru.xlsx');
    }

    private function generateKepalaMadrasahTemplate()
    {
        $filename = 'public/template/tenaga_pendidik_contoh_kepala_madrasah.xlsx';

        $headers = [
            'nama',
            'email',
            'tempat_lahir',
            'tanggal_lahir',
            'no_hp',
            'kartanu',
            'nip',
            'nuptk',
            'npk',
            'madrasah_id',
            'pendidikan_terakhir',
            'tahun_lulus',
            'program_studi',
            'status_kepegawaian_id',
            'tmt',
            'ketugasan',
            'mengajar',
            'alamat'
        ];

        $data = [
            [
                'Dr. H. Ahmad Surya, M.Pd.',
                'kepala.sma.maarif1@nuist.sch.id',
                'Yogyakarta',
                '1975-06-10',
                '081111111111',
                '111111111',
                '197506101995031001',
                '1111111111111111',
                'K001',
                '1',
                'S2 Manajemen Pendidikan',
                '1998',
                'Administrasi Pendidikan',
                '1',
                '1998-08-01',
                'kepala madrasah/sekolah',
                'Kepala Sekolah',
                'Jl. Pendidikan No. 1, Sleman, Yogyakarta 55581'
            ],
            [
                'Dra. Siti Aminah, M.Pd.',
                'kepala.smk.maarif1@nuist.sch.id',
                'Sleman',
                '1978-09-15',
                '081222222222',
                '222222222',
                '197809151998032002',
                '2222222222222222',
                'K002',
                '1',
                'S2 Manajemen Pendidikan',
                '2000',
                'Administrasi Pendidikan',
                '1',
                '2000-09-01',
                'kepala madrasah/sekolah',
                'Kepala Sekolah',
                'Jl. Teknologi No. 2, Mlati, Sleman 55584'
            ]
        ];

        $this->createExcelFile($filename, $headers, $data);
        $this->info('📄 Kepala Madrasah template generated: tenaga_pendidik_contoh_kepala_madrasah.xlsx');
    }

    private function createExcelFile($filename, $headers, $data)
    {
        // Buat direktori jika belum ada
        $directory = dirname($filename);
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Untuk sementara, buat file CSV terlebih dahulu
        // Nanti bisa diubah ke format Excel jika diperlukan
        $content = '';

        // Tambahkan header
        $content .= implode(',', $headers) . "\n";

        // Tambahkan data
        foreach ($data as $row) {
            $content .= implode(',', array_map(function($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row)) . "\n";
        }

        // Simpan sebagai file sementara
        $tempFile = $filename . '.tmp';
        Storage::put($tempFile, $content);

        // Rename ke nama final
        Storage::move($tempFile, $filename);

        // Untuk Excel sebenarnya, bisa menggunakan library seperti PhpSpreadsheet
        // Tapi untuk sekarang, buat file CSV yang bisa dibuka dengan Excel
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        if ($csvFilename !== $filename) {
            Storage::copy($filename, $csvFilename);
        }
    }
}
