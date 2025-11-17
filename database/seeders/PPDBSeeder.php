<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\PPDBSetting;
use App\Models\PPDBJalur;
use App\Models\Madrasah;

class PPDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil sekolah pertama untuk testing
        $sekolah = Madrasah::first();

        if (!$sekolah) {
            $this->command->warn('Tidak ada sekolah. Buat sekolah terlebih dahulu.');
            return;
        }

        // Buat PPDB Setting
        $ppdbSetting = PPDBSetting::create([
            'sekolah_id' => $sekolah->id,
            'slug' => Str::slug($sekolah->nama) . '-2025',
            'nama_sekolah' => $sekolah->nama,
            'tahun' => now()->year,
            'status' => 'buka',
            'jadwal_buka' => now()->subDays(7),
            'jadwal_tutup' => now()->addDays(30),
        ]);

        // Buat Jalur Pendaftaran
        $jalurs = [
            ['nama_jalur' => 'Jalur Zonasi', 'keterangan' => 'Berdasarkan domisili/alamat siswa', 'urutan' => 1],
            ['nama_jalur' => 'Jalur Prestasi', 'keterangan' => 'Untuk siswa dengan prestasi akademik', 'urutan' => 2],
        ];

        foreach ($jalurs as $jalur) {
            PPDBJalur::create([
                'ppdb_setting_id' => $ppdbSetting->id,
                'nama_jalur' => $jalur['nama_jalur'],
                'keterangan' => $jalur['keterangan'],
                'urutan' => $jalur['urutan'],
            ]);
        }

        $this->command->info("PPDB Seeder berjalan sukses untuk {$sekolah->nama}");
    }
}
