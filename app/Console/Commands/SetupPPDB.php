<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use App\Models\PPDBJalur;
use App\Models\Madrasah;
use App\Models\User;

class SetupPPDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:setup {--force : Overwrite existing PPDB settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup PPDB dengan data testing untuk semua sekolah';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Setting up PPDB...');

        $sekolah = Madrasah::first();
        if (!$sekolah) {
            $this->error('❌ Tidak ada sekolah. Buat sekolah terlebih dahulu.');
            return Command::FAILURE;
        }

        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolah->id)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && !$this->option('force')) {
            $this->warn('⚠️  PPDB sudah ada untuk tahun ini. Gunakan --force untuk overwrite.');
            return Command::FAILURE;
        }

        if ($ppdbSetting && $this->option('force')) {
            $this->info('🔄 Menghapus PPDB lama...');
            $ppdbSetting->pendaftars()->delete();
            $ppdbSetting->jalurs()->delete();
            $ppdbSetting->verifikasis()->delete();
            $ppdbSetting->delete();
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

        $this->info("✅ PPDB Setting dibuat untuk {$sekolah->nama}");

        // Buat Jalur
        $jalurs = [
            ['nama' => 'Jalur Prestasi', 'desc' => 'Untuk siswa dengan prestasi akademik', 'urutan' => 1],
            ['nama' => 'Jalur Reguler', 'desc' => 'Jalur pendaftaran biasa', 'urutan' => 2],
            ['nama' => 'Jalur Afirmasi', 'desc' => 'Untuk siswa dari keluarga kurang mampu', 'urutan' => 3],
        ];

        foreach ($jalurs as $jalur) {
            PPDBJalur::create([
                'ppdb_setting_id' => $ppdbSetting->id,
                'nama_jalur' => $jalur['nama'],
                'keterangan' => $jalur['desc'],
                'urutan' => $jalur['urutan'],
            ]);
        }

        $this->info("✅ 3 Jalur pendaftaran dibuat");

        // Buat sample pendaftar
        $jalurIds = PPDBJalur::where('ppdb_setting_id', $ppdbSetting->id)->pluck('id')->toArray();

        for ($i = 1; $i <= 5; $i++) {
            PPDBPendaftar::create([
                'ppdb_setting_id' => $ppdbSetting->id,
                'ppdb_jalur_id' => $jalurIds[array_rand($jalurIds)],
                'nomor_pendaftaran' => $ppdbSetting->slug . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => "Calon Siswa {$i}",
                'nisn' => '123456789' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'asal_sekolah' => "SMP Negeri {$i}",
                'jurusan_pilihan' => $i % 2 == 0 ? 'IPA' : 'IPS',
                'status' => 'pending',
            ]);
        }

        $this->info("✅ 5 Sample pendaftar dibuat");

        $this->info("\n📊 PPDB Setup Summary:");
        $this->line("  Sekolah: {$sekolah->nama}");
        $this->line("  Tahun: " . now()->year);
        $this->line("  Status: Buka");
        $this->line("  Jadwal: " . $ppdbSetting->jadwal_buka->format('d M Y') . " - " . $ppdbSetting->jadwal_tutup->format('d M Y'));
        $this->line("  Jalur: 3");
        $this->line("  Sample Pendaftar: 5 (status: pending)");
        $this->line("\n🌐 Akses:");
        $this->line("  Public: http://localhost:8000/ppdb");
        $this->line("  Detail: http://localhost:8000/ppdb/{$ppdbSetting->slug}");
        $this->line("  Daftar: http://localhost:8000/ppdb/{$ppdbSetting->slug}/daftar");

        return Command::SUCCESS;
    }
}
