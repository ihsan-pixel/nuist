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
        Schema::create('ppdb_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('madrasahs')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('nama_sekolah');
            $table->year('tahun');
            $table->string('kabupaten')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tagline')->nullable();
            $table->string('akreditasi')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->text('sejarah')->nullable();
            $table->json('nilai_nilai')->nullable();
            $table->text('visi')->nullable();
            $table->json('misi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->json('keunggulan')->nullable();
            $table->json('jurusan')->nullable();
            $table->json('prestasi')->nullable();
            $table->json('program_unggulan')->nullable();
            $table->json('ekstrakurikuler')->nullable();
            $table->json('testimoni')->nullable();
            $table->string('kepala_sekolah_nama')->nullable();
            $table->string('kepala_sekolah_gelar')->nullable();
            $table->text('kepala_sekolah_sambutan')->nullable();
            $table->string('kepala_sekolah_foto')->nullable();
            $table->integer('jumlah_siswa')->nullable();
            $table->integer('jumlah_guru')->nullable();
            $table->integer('jumlah_jurusan')->nullable();
            $table->integer('jumlah_sarana')->nullable();
            $table->string('video_profile')->nullable();
            $table->string('brosur_pdf')->nullable();
            $table->json('galeri_foto')->nullable();
            $table->string('link_video_youtube')->nullable();
            $table->string('logo')->nullable();
            $table->string('name')->nullable();
            $table->json('faq')->nullable();
            $table->json('alur_pendaftaran')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_settings');
    }
};
