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
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            // Data Diri Siswa
            $table->string('nik')->nullable()->after('nisn');
            $table->string('agama')->nullable()->after('jenis_kelamin');
            $table->string('ppdb_email_siswa')->nullable()->change();

            // Data Orang Tua
            $table->string('nama_ayah')->nullable()->after('asal_sekolah');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            $table->string('nomor_hp_ayah')->nullable()->after('pekerjaan_ibu');
            $table->string('nomor_hp_ibu')->nullable()->after('nomor_hp_ayah');
            $table->text('alamat_lengkap')->nullable()->after('nomor_hp_ibu');
            $table->string('status_keluarga')->nullable()->after('alamat_lengkap');

            // Data Sekolah Asal
            $table->string('npsn_sekolah_asal')->nullable()->after('asal_sekolah');
            $table->year('tahun_lulus')->nullable()->after('npsn_sekolah_asal');

            // Data Akademik
            $table->decimal('nilai_akhir_raport', 5, 2)->nullable()->after('nilai');
            $table->decimal('rata_rata_nilai_raport', 5, 2)->nullable()->after('nilai_akhir_raport');
            $table->string('nomor_ijazah')->nullable()->after('rata_rata_nilai_raport');
            $table->string('nomor_skhun')->nullable()->after('nomor_ijazah');
            $table->string('nomor_peserta_un')->nullable()->after('nomor_skhun');

            // Dokumen tambahan
            $table->string('berkas_akta_kelahiran')->nullable()->after('berkas_ijazah');
            $table->string('berkas_ktp_ayah')->nullable()->after('berkas_akta_kelahiran');
            $table->string('berkas_ktp_ibu')->nullable()->after('berkas_ktp_ayah');
            $table->string('berkas_raport')->nullable()->after('berkas_ktp_ibu');
            $table->string('berkas_sertifikat_prestasi')->nullable()->after('berkas_raport');
            $table->string('berkas_kip_pkh')->nullable()->after('berkas_sertifikat_prestasi');
            $table->string('berkas_bukti_domisili')->nullable()->after('berkas_kip_pkh');
            $table->string('berkas_surat_mutasi')->nullable()->after('berkas_bukti_domisili');
            $table->string('berkas_surat_keterangan_lulus')->nullable()->after('berkas_surat_mutasi');
            $table->string('berkas_skl')->nullable()->after('berkas_surat_keterangan_lulus');

            // Sistem Poin (Skoring)
            $table->integer('skor_nilai')->default(0)->after('ranking');
            $table->integer('skor_prestasi')->default(0)->after('skor_nilai');
            $table->integer('skor_domisili')->default(0)->after('skor_prestasi');
            $table->integer('skor_dokumen')->default(0)->after('skor_domisili');
            $table->integer('skor_total')->default(0)->after('skor_dokumen');

            // Jalur Inden
            $table->boolean('is_inden')->default(false)->after('jalur');
            $table->text('surat_keterangan_sementara')->nullable()->after('is_inden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'agama', 'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu',
                'nomor_hp_ayah', 'nomor_hp_ibu', 'alamat_lengkap', 'status_keluarga',
                'npsn_sekolah_asal', 'tahun_lulus', 'nilai_akhir_raport', 'rata_rata_nilai_raport',
                'nomor_ijazah', 'nomor_skhun', 'nomor_peserta_un',
                'berkas_akta_kelahiran', 'berkas_ktp_ayah', 'berkas_ktp_ibu', 'berkas_raport',
                'berkas_sertifikat_prestasi', 'berkas_kip_pkh', 'berkas_bukti_domisili',
                'berkas_surat_mutasi', 'berkas_surat_keterangan_lulus', 'berkas_skl',
                'skor_nilai', 'skor_prestasi', 'skor_domisili', 'skor_dokumen', 'skor_total',
                'is_inden', 'surat_keterangan_sementara'
            ]);
        });
    }
};
