<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            // Drop the columns that have been moved to ppdb_settings
            $columnsToDrop = [
                'tagline', 'akreditasi', 'tahun_berdiri', 'telepon', 'email', 'website',
                'deskripsi_singkat', 'sejarah', 'nilai_nilai', 'visi', 'misi', 'fasilitas',
                'keunggulan', 'jurusan', 'prestasi', 'program_unggulan', 'ekstrakurikuler',
                'testimoni', 'kepala_sekolah_nama', 'kepala_sekolah_gelar', 'kepala_sekolah_sambutan',
                'jumlah_siswa', 'jumlah_guru', 'jumlah_jurusan', 'jumlah_sarana', 'video_profile',
                'brosur_pdf', 'galeri_foto', 'jam_operasional_buka', 'jam_operasional_tutup',
                'faq', 'alur_pendaftaran', 'ppdb_status', 'ppdb_jadwal_buka', 'ppdb_jadwal_tutup',
                'ppdb_kuota_total', 'ppdb_jadwal_pengumuman', 'ppdb_kuota_jurusan', 'ppdb_jalur',
                'ppdb_biaya_pendaftaran', 'ppdb_catatan_pengumuman'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('madrasahs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            // Restore the dropped columns (reverse migration)
            $table->string('tagline')->nullable()->after('name');
            $table->string('akreditasi')->nullable()->after('tagline');
            $table->integer('tahun_berdiri')->nullable()->after('akreditasi');
            $table->string('telepon')->nullable()->after('tahun_berdiri');
            $table->string('email')->nullable()->after('telepon');
            $table->string('website')->nullable()->after('email');
            $table->text('deskripsi_singkat')->nullable()->after('website');
            $table->text('sejarah')->nullable()->after('deskripsi_singkat');
            $table->text('nilai_nilai')->nullable()->after('sejarah');
            $table->text('visi')->nullable()->after('nilai_nilai');
            $table->json('misi')->nullable()->after('visi');
            $table->json('fasilitas')->nullable()->after('misi');
            $table->json('keunggulan')->nullable()->after('fasilitas');
            $table->json('jurusan')->nullable()->after('keunggulan');
            $table->json('prestasi')->nullable()->after('jurusan');
            $table->json('program_unggulan')->nullable()->after('prestasi');
            $table->json('ekstrakurikuler')->nullable()->after('program_unggulan');
            $table->json('testimoni')->nullable()->after('ekstrakurikuler');
            $table->string('kepala_sekolah_nama')->nullable()->after('testimoni');
            $table->string('kepala_sekolah_gelar')->nullable()->after('kepala_sekolah_nama');
            $table->text('kepala_sekolah_sambutan')->nullable()->after('kepala_sekolah_gelar');
            $table->integer('jumlah_siswa')->nullable()->after('kepala_sekolah_sambutan');
            $table->integer('jumlah_guru')->nullable()->after('jumlah_siswa');
            $table->integer('jumlah_jurusan')->nullable()->after('jumlah_guru');
            $table->integer('jumlah_sarana')->nullable()->after('jumlah_jurusan');
            $table->string('video_profile')->nullable()->after('jumlah_sarana');
            $table->string('brosur_pdf')->nullable()->after('video_profile');
            $table->json('galeri_foto')->nullable()->after('brosur_pdf');
            $table->time('jam_operasional_buka')->nullable()->after('galeri_foto');
            $table->time('jam_operasional_tutup')->nullable()->after('jam_operasional_buka');
            $table->json('faq')->nullable()->after('jam_operasional_tutup');
            $table->json('alur_pendaftaran')->nullable()->after('faq');
            $table->enum('ppdb_status', ['buka', 'tutup'])->default('tutup')->after('alur_pendaftaran');
            $table->dateTime('ppdb_jadwal_buka')->nullable()->after('ppdb_status');
            $table->dateTime('ppdb_jadwal_tutup')->nullable()->after('ppdb_jadwal_buka');
            $table->integer('ppdb_kuota_total')->nullable()->after('ppdb_jadwal_tutup');
            $table->dateTime('ppdb_jadwal_pengumuman')->nullable()->after('ppdb_kuota_total');
            $table->json('ppdb_kuota_jurusan')->nullable()->after('ppdb_jadwal_pengumuman');
            $table->json('ppdb_jalur')->nullable()->after('ppdb_kuota_jurusan');
            $table->text('ppdb_biaya_pendaftaran')->nullable()->after('ppdb_jalur');
            $table->text('ppdb_catatan_pengumuman')->nullable()->after('ppdb_biaya_pendaftaran');
        });
    }
};
