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
            // Hero Section
            $table->string('tagline')->nullable();
            $table->text('deskripsi_singkat')->nullable();

            // Sejarah Sekolah
            $table->year('tahun_berdiri')->nullable();
            $table->text('sejarah')->nullable();
            $table->string('akreditasi')->nullable();
            $table->text('nilai_nilai')->nullable();

            // Visi & Misi
            $table->text('visi')->nullable();
            $table->json('misi')->nullable(); // Array of mission points

            // Keunggulan & Selling Point
            $table->json('keunggulan')->nullable(); // Array of advantages with icons

            // Fasilitas
            $table->json('fasilitas')->nullable(); // Array of facilities with photos

            // Jurusan/Program Studi
            $table->json('jurusan')->nullable(); // Array of majors/programs

            // Prestasi
            $table->json('prestasi')->nullable(); // Array of achievements

            // Program Unggulan & Ekstrakurikuler
            $table->json('program_unggulan')->nullable();
            $table->json('ekstrakurikuler')->nullable();

            // Testimoni
            $table->json('testimoni')->nullable(); // Array of testimonials

            // Kepala Sekolah
            $table->string('kepala_sekolah_nama')->nullable();
            $table->string('kepala_sekolah_gelar')->nullable();
            $table->text('kepala_sekolah_sambutan')->nullable();
            $table->string('kepala_sekolah_foto')->nullable();

            // Data Statistik
            $table->integer('jumlah_siswa')->nullable();
            $table->integer('jumlah_guru')->nullable();
            $table->integer('jumlah_jurusan')->nullable();
            $table->integer('jumlah_sarana')->nullable();

            // Galeri & Media
            $table->json('galeri_foto')->nullable();
            $table->string('video_profile')->nullable();

            // Kontak & Lokasi
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->time('jam_operasional_buka')->nullable();
            $table->time('jam_operasional_tutup')->nullable();

            // Bonus
            $table->string('brosur_pdf')->nullable();
            $table->json('faq')->nullable();
            $table->json('alur_pendaftaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->dropColumn([
                'tagline', 'deskripsi_singkat',
                'tahun_berdiri', 'sejarah', 'akreditasi', 'nilai_nilai',
                'visi', 'misi',
                'keunggulan',
                'fasilitas',
                'jurusan',
                'prestasi',
                'program_unggulan', 'ekstrakurikuler',
                'testimoni',
                'kepala_sekolah_nama', 'kepala_sekolah_gelar', 'kepala_sekolah_sambutan', 'kepala_sekolah_foto',
                'jumlah_siswa', 'jumlah_guru', 'jumlah_jurusan', 'jumlah_sarana',
                'galeri_foto', 'video_profile',
                'telepon', 'email', 'website', 'jam_operasional_buka', 'jam_operasional_tutup',
                'brosur_pdf', 'faq', 'alur_pendaftaran'
            ]);
        });
    }
};
