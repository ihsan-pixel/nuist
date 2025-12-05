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
        Schema::table('simfoni', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simfoni', function (Blueprint $table) {
            // E. STATUS KEKADERAN
            $table->dropColumn([
                'status_kader_diri',
                'pendidikan_kader',
                'status_kader_ayah',
                'status_kader_ibu',
                'status_kader_pasangan',
                'pilihan_status_kader'
            ]);

            // F. DATA KELUARGA
            $table->dropColumn([
                'nama_ayah',
                'nama_ibu',
                'nama_pasangan',
                'jumlah_anak'
            ]);

            // G. PROYEKSI KE DEPAN
            $table->dropColumn([
                'akan_kuliah_s2',
                'skor_akan_kuliah_s2',
                'akan_mendaftar_pns',
                'skor_akan_mendaftar_pns',
                'akan_mendaftar_pppk',
                'skor_akan_mendaftar_pppk',
                'akan_mengikuti_ppg',
                'skor_akan_mengikuti_ppg',
                'akan_menulis_buku_modul_riset',
                'skor_akan_menulis_buku_modul_riset',
                'akan_mengikuti_seleksi_diklat_cakep',
                'skor_akan_mengikuti_seleksi_diklat_cakep',
                'akan_membimbing_riset_prestasi_siswa',
                'skor_akan_membimbing_riset_prestasi_siswa',
                'akan_masuk_tim_unggulan_sekolah_madrasah',
                'skor_akan_masuk_tim_unggulan_sekolah_madrasah',
                'akan_kompetisi_pimpinan_level_ii',
                'skor_akan_kompetisi_pimpinan_level_ii',
                'akan_aktif_mengikuti_pelatihan',
                'skor_akan_aktif_mengikuti_pelatihan',
                'akan_aktif_mgmp_mkk',
                'skor_akan_aktif_mgmp_mkk',
                'akan_mengikuti_pendidikan_kader_nu',
                'skor_akan_mengikuti_pendidikan_kader_nu',
                'akan_aktif_membantu_kegiatan_lembaga',
                'skor_akan_aktif_membantu_kegiatan_lembaga',
                'akan_aktif_mengikuti_kegiatan_nu',
                'skor_akan_aktif_mengikuti_kegiatan_nu',
                'akan_aktif_ikut_zis_kegiatan_sosial',
                'skor_akan_aktif_ikut_zis_kegiatan_sosial',
                'akan_mengembangkan_unit_usaha_satpen',
                'skor_akan_mengembangkan_unit_usaha_satpen',
                'akan_bekerja_disiplin_produktif',
                'skor_akan_bekerja_disiplin_produktif',
                'akan_loyal_nu_aktif_masyarakat',
                'skor_akan_loyal_nu_aktif_masyarakat',
                'bersedia_dipindah_satpen_lain',
                'skor_bersedia_dipindah_satpen_lain',
                'skor_proyeksi',
                'kategori_proyeksi'
            ]);
        });
    }
};
