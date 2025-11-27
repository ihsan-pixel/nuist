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
            // Add missing columns from lp-edit.blade.php form (only if they don't exist)
            if (!Schema::hasColumn('ppdb_settings', 'kabupaten')) {
                $table->string('kabupaten')->nullable()->after('nama_sekolah');
            }
            if (!Schema::hasColumn('ppdb_settings', 'alamat')) {
                $table->text('alamat')->nullable()->after('kabupaten');
            }
            if (!Schema::hasColumn('ppdb_settings', 'tagline')) {
                $table->string('tagline')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('ppdb_settings', 'akreditasi')) {
                $table->string('akreditasi')->nullable()->after('tagline');
            }
            if (!Schema::hasColumn('ppdb_settings', 'tahun_berdiri')) {
                $table->integer('tahun_berdiri')->nullable()->after('akreditasi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'telepon')) {
                $table->string('telepon')->nullable()->after('tahun_berdiri');
            }
            if (!Schema::hasColumn('ppdb_settings', 'email')) {
                $table->string('email')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('ppdb_settings', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('ppdb_settings', 'deskripsi_singkat')) {
                $table->text('deskripsi_singkat')->nullable()->after('website');
            }
            if (!Schema::hasColumn('ppdb_settings', 'sejarah')) {
                $table->text('sejarah')->nullable()->after('deskripsi_singkat');
            }
            if (!Schema::hasColumn('ppdb_settings', 'nilai_nilai')) {
                $table->text('nilai_nilai')->nullable()->after('sejarah');
            }
            // misi column may already exist, skipping
            if (!Schema::hasColumn('ppdb_settings', 'fasilitas')) {
                $table->json('fasilitas')->nullable()->after('nilai_nilai');
            }
            if (!Schema::hasColumn('ppdb_settings', 'keunggulan')) {
                $table->json('keunggulan')->nullable()->after('fasilitas');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jurusan')) {
                $table->json('jurusan')->nullable()->after('keunggulan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'prestasi')) {
                $table->json('prestasi')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'program_unggulan')) {
                $table->json('program_unggulan')->nullable()->after('prestasi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ekstrakurikuler')) {
                $table->json('ekstrakurikuler')->nullable()->after('program_unggulan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_nama')) {
                $table->string('kepala_sekolah_nama')->nullable()->after('ekstrakurikuler');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_gelar')) {
                $table->string('kepala_sekolah_gelar')->nullable()->after('kepala_sekolah_nama');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_sambutan')) {
                $table->text('kepala_sekolah_sambutan')->nullable()->after('kepala_sekolah_gelar');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_siswa')) {
                $table->integer('jumlah_siswa')->nullable()->after('kepala_sekolah_sambutan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_guru')) {
                $table->integer('jumlah_guru')->nullable()->after('jumlah_siswa');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_jurusan')) {
                $table->integer('jumlah_jurusan')->nullable()->after('jumlah_guru');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_sarana')) {
                $table->integer('jumlah_sarana')->nullable()->after('jumlah_jurusan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'video_profile')) {
                $table->string('video_profile')->nullable()->after('jumlah_sarana');
            }
            if (!Schema::hasColumn('ppdb_settings', 'brosur_pdf')) {
                $table->string('brosur_pdf')->nullable()->after('video_profile');
            }
            if (!Schema::hasColumn('ppdb_settings', 'galeri_foto')) {
                $table->json('galeri_foto')->nullable()->after('brosur_pdf');
            }
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
