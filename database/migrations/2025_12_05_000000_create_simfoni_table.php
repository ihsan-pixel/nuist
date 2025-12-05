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
        Schema::create('simfoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // A. DATA SK
            $table->string('nama_lengkap_gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('kartanu')->nullable();
            $table->string('nipm')->nullable();
            $table->string('nik')->nullable();
            $table->date('tmt')->nullable();
            $table->string('strata_pendidikan')->nullable();
            $table->string('pt_asal')->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->string('program_studi')->nullable();

            // B. RIWAYAT KERJA
            $table->string('status_kerja')->nullable();
            $table->date('tanggal_sk_pertama')->nullable();
            $table->string('nomor_sk_pertama')->nullable();
            $table->string('nomor_sertifikasi_pendidik')->nullable();
            $table->longText('riwayat_kerja_sebelumnya')->nullable();

            // C. KEAHLIAN DAN DATA LAIN
            $table->longText('keahlian')->nullable();
            $table->string('kedudukan_lpm')->nullable();
            $table->longText('prestasi')->nullable();
            $table->string('tahun_sertifikasi_impassing')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->longText('alamat_lengkap')->nullable();

            // D. DATA KEUANGAN/KESEJAHTERAAN
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->decimal('gaji_sertifikasi', 15, 2)->nullable();
            $table->decimal('gaji_pokok', 15, 2)->nullable();
            $table->decimal('honor_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_pasangan', 15, 2)->nullable();
            $table->decimal('total_penghasilan', 15, 2)->nullable();
            $table->string('masa_kerja')->nullable();
            $table->string('kategori_penghasilan')->nullable();

            // E. STATUS KEKADERAN
            $table->string('status_kader_diri')->nullable();
            $table->string('pendidikan_kader')->nullable();
            $table->string('status_kader_ayah')->nullable();
            $table->string('status_kader_ibu')->nullable();
            $table->string('status_kader_pasangan')->nullable();
            $table->string('pilihan_status_kader')->nullable();

            // F. DATA KELUARGA
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->integer('jumlah_anak')->nullable();

            // G. PROYEKSI KE DEPAN
            $table->string('akan_kuliah_s2')->nullable();
            $table->integer('skor_akan_kuliah_s2')->nullable();
            $table->string('akan_mendaftar_pns')->nullable();
            $table->integer('skor_akan_mendaftar_pns')->nullable();
            $table->string('akan_mendaftar_pppk')->nullable();
            $table->integer('skor_akan_mendaftar_pppk')->nullable();
            $table->string('akan_mengikuti_ppg')->nullable();
            $table->integer('skor_akan_mengikuti_ppg')->nullable();
            $table->string('akan_menulis_buku_modul_riset')->nullable();
            $table->integer('skor_akan_menulis_buku_modul_riset')->nullable();
            $table->string('akan_mengikuti_seleksi_diklat_cakep')->nullable();
            $table->integer('skor_akan_mengikuti_seleksi_diklat_cakep')->nullable();
            $table->string('akan_membimbing_riset_prestasi_siswa')->nullable();
            $table->integer('skor_akan_membimbing_riset_prestasi_siswa')->nullable();
            $table->string('akan_masuk_tim_unggulan_sekolah_madrasah')->nullable();
            $table->integer('skor_akan_masuk_tim_unggulan_sekolah_madrasah')->nullable();
            $table->string('akan_kompetisi_pimpinan_level_ii')->nullable();
            $table->integer('skor_akan_kompetisi_pimpinan_level_ii')->nullable();
            $table->string('akan_aktif_mengikuti_pelatihan')->nullable();
            $table->integer('skor_akan_aktif_mengikuti_pelatihan')->nullable();
            $table->string('akan_aktif_mgmp_mkk')->nullable();
            $table->integer('skor_akan_aktif_mgmp_mkk')->nullable();
            $table->string('akan_mengikuti_pendidikan_kader_nu')->nullable();
            $table->integer('skor_akan_mengikuti_pendidikan_kader_nu')->nullable();
            $table->string('akan_aktif_membantu_kegiatan_lembaga')->nullable();
            $table->integer('skor_akan_aktif_membantu_kegiatan_lembaga')->nullable();
            $table->string('akan_aktif_mengikuti_kegiatan_nu')->nullable();
            $table->integer('skor_akan_aktif_mengikuti_kegiatan_nu')->nullable();
            $table->string('akan_aktif_ikut_zis_kegiatan_sosial')->nullable();
            $table->integer('skor_akan_aktif_ikut_zis_kegiatan_sosial')->nullable();
            $table->string('akan_mengembangkan_unit_usaha_satpen')->nullable();
            $table->integer('skor_akan_mengembangkan_unit_usaha_satpen')->nullable();
            $table->string('akan_bekerja_disiplin_produktif')->nullable();
            $table->integer('skor_akan_bekerja_disiplin_produktif')->nullable();
            $table->string('akan_loyal_nu_aktif_masyarakat')->nullable();
            $table->integer('skor_akan_loyal_nu_aktif_masyarakat')->nullable();
            $table->string('bersedia_dipindah_satpen_lain')->nullable();
            $table->integer('skor_bersedia_dipindah_satpen_lain')->nullable();
            $table->integer('skor_proyeksi')->nullable(); // Skor total proyeksi (0-19)
            $table->string('kategori_proyeksi')->nullable(); // Kategori proyeksi (optimal/baik/cukup/kurang/buruk)

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simfoni');
    }
};
