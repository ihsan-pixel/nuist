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
        Schema::create('talenta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('madrasah_id')->constrained()->onDelete('cascade');
            $table->year('tahun_pelaporan');
            $table->enum('status', ['draft', 'published'])->default('draft');

            // TPT Level 1
            $table->string('nomor_talenta_1')->nullable();
            $table->integer('skor_penilaian_1')->nullable();
            $table->string('sertifikat_tpt_1')->nullable();
            $table->string('produk_unggulan_1')->nullable();

            // TPT Level 2
            $table->string('nomor_talenta_2')->nullable();
            $table->integer('skor_penilaian_2')->nullable();
            $table->string('sertifikat_tpt_2')->nullable();
            $table->string('produk_unggulan_2')->nullable();

            // TPT Level 3
            $table->string('nomor_talenta_3')->nullable();
            $table->integer('skor_penilaian_3')->nullable();
            $table->string('sertifikat_tpt_3')->nullable();
            $table->string('produk_unggulan_3')->nullable();

            // TPT Level 4
            $table->string('nomor_talenta_4')->nullable();
            $table->integer('skor_penilaian_4')->nullable();
            $table->string('sertifikat_tpt_4')->nullable();
            $table->string('produk_unggulan_4')->nullable();

            // TPT Level 5
            $table->string('nomor_talenta_5')->nullable();
            $table->integer('skor_penilaian_5')->nullable();
            $table->string('sertifikat_tpt_5')->nullable();
            $table->string('produk_unggulan_5')->nullable();

            // Lampiran Step 1
            $table->string('lampiran_step_1')->nullable();

            // Pendidikan Kader
            $table->enum('pkpnu_status', ['sudah', 'belum'])->nullable();
            $table->string('pkpnu_sertifikat')->nullable();
            $table->enum('mknu_status', ['sudah', 'belum'])->nullable();
            $table->string('mknu_sertifikat')->nullable();
            $table->enum('pmknu_status', ['sudah', 'belum'])->nullable();
            $table->string('pmknu_sertifikat')->nullable();

            // Lampiran Step 2
            $table->string('lampiran_step_2')->nullable();

            // Proyeksi Diri
            $table->enum('jabatan_saat_ini', ['guru', 'kaprodi', 'kepala_laboratorium', 'kepala_perpustakaan', 'kepala_bengkel', 'bendahara_i'])->nullable();
            $table->enum('proyeksi_akademik', ['guru_terampil', 'guru_mahir', 'guru_ahli'])->nullable();
            $table->enum('proyeksi_jabatan_level2_umum', ['wakil_kepala_sekolah', 'kaprodi', 'kepala_perpustakaan', 'bendahara_sekolah'])->nullable();
            $table->enum('proyeksi_jabatan_level2_khusus', ['kepala_unit_usaha', 'leader_sister_school', 'leader_jejaring', 'leader_unggulan_sekolah', 'leader_prestasi_sekolah', 'leader_panjaminan_mutu'])->nullable();
            $table->enum('proyeksi_jabatan_level1', ['kepala_sekolah', 'kepala_madrasah', 'tidak'])->nullable();
            $table->enum('proyeksi_jabatan_top_leader', ['pengawas', 'pembina_utama', 'tidak'])->nullable();
            $table->text('studi_lanjut')->nullable();
            $table->text('leader_mgmp')->nullable();
            $table->text('produk_ajar')->nullable();
            $table->text('prestasi_kompetitif')->nullable();

            // Lampiran Step 3
            $table->string('lampiran_step_3')->nullable();

            // Data Diri
            $table->string('nama_lengkap_gelar');
            $table->string('nama_panggilan');
            $table->string('nomor_ktp');
            $table->string('nip_maarif');
            $table->string('nomor_talenta');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('email_aktif');
            $table->string('nomor_wa');
            $table->text('alamat_ktp');
            $table->text('alamat_tinggal');
            $table->string('link_fb')->nullable();
            $table->string('link_tiktok')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('foto_resmi')->nullable();
            $table->string('foto_bebas')->nullable();
            $table->string('foto_keluarga')->nullable();

            // Data Pendidikan
            $table->string('asal_sekolah_sd')->nullable();
            $table->string('asal_sekolah_smp')->nullable();
            $table->string('asal_sekolah_sma')->nullable();
            $table->string('asal_sekolah_s1')->nullable();
            $table->string('asal_sekolah_s2')->nullable();
            $table->string('asal_sekolah_s3')->nullable();
            $table->string('ijazah_s1')->nullable();
            $table->string('ijazah_s2')->nullable();
            $table->string('ijazah_s3')->nullable();

            // Data Pendapatan
            $table->enum('level_pendapatan_internal', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);
            $table->string('pekerjaan_eksternal')->nullable();
            $table->string('pendapatan_eksternal')->nullable();

            // Data Riwayat Kerja
            $table->string('gtt_ptt_tanggal')->nullable();
            $table->string('gtt_ptt_sk')->nullable();
            $table->string('gty_tanggal')->nullable();
            $table->string('gty_sk')->nullable();
            $table->integer('masa_kerja_lpmnu');
            $table->string('riwayat_jabatan_pemula');
            $table->string('riwayat_jabatan_terampil');
            $table->string('riwayat_jabatan_mahir');
            $table->string('riwayat_jabatan_ahli');

            // Lampiran Step 4
            $table->string('lampiran_step_4')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta');
    }
};
