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
        Schema::table('ppdb_settings', function (Blueprint $table) {
            // Add missing columns from lp-edit.blade.php form
            $table->string('kabupaten')->nullable()->after('nama_sekolah');
            $table->text('alamat')->nullable()->after('kabupaten');
            $table->string('tagline')->nullable()->after('alamat');
            $table->string('akreditasi')->nullable()->after('tagline');
            $table->integer('tahun_berdiri')->nullable()->after('akreditasi');
            $table->string('telepon')->nullable()->after('tahun_berdiri');
            $table->string('email')->nullable()->after('telepon');
            $table->string('website')->nullable()->after('email');
            $table->text('deskripsi_singkat')->nullable()->after('website');
            $table->text('sejarah')->nullable()->after('deskripsi_singkat');
            $table->text('nilai_nilai')->nullable()->after('sejarah');
            $table->json('misi')->nullable()->after('nilai_nilai');
            $table->json('fasilitas')->nullable()->after('misi');
            $table->json('keunggulan')->nullable()->after('fasilitas');
            $table->json('jurusan')->nullable()->after('keunggulan');
            $table->json('prestasi')->nullable()->after('jurusan');
            $table->json('program_unggulan')->nullable()->after('prestasi');
            $table->json('ekstrakurikuler')->nullable()->after('program_unggulan');
            $table->string('kepala_sekolah_nama')->nullable()->after('ekstrakurikuler');
            $table->string('kepala_sekolah_gelar')->nullable()->after('kepala_sekolah_nama');
            $table->text('kepala_sekolah_sambutan')->nullable()->after('kepala_sekolah_gelar');
            $table->integer('jumlah_siswa')->nullable()->after('kepala_sekolah_sambutan');
            $table->integer('jumlah_guru')->nullable()->after('jumlah_siswa');
            $table->integer('jumlah_jurusan')->nullable()->after('jumlah_guru');
            $table->integer('jumlah_sarana')->nullable()->after('jumlah_jurusan');
            $table->string('video_profile')->nullable()->after('jumlah_sarana');
            $table->string('brosur_pdf')->nullable()->after('video_profile');
            $table->json('galeri_foto')->nullable()->after('brosur_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            $table->dropColumn([
                'kabupaten',
                'alamat',
                'tagline',
                'akreditasi',
                'tahun_berdiri',
                'telepon',
                'email',
                'website',
                'deskripsi_singkat',
                'sejarah',
                'nilai_nilai',
                'misi',
                'fasilitas',
                'keunggulan',
                'jurusan',
                'prestasi',
                'program_unggulan',
                'ekstrakurikuler',
                'kepala_sekolah_nama',
                'kepala_sekolah_gelar',
                'kepala_sekolah_sambutan',
                'jumlah_siswa',
                'jumlah_guru',
                'jumlah_jurusan',
                'jumlah_sarana',
                'video_profile',
                'brosur_pdf',
                'galeri_foto'
            ]);
        });
    }
};
