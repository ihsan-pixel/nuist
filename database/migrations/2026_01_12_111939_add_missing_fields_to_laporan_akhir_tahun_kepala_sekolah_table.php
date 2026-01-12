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
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            // Step 2: Capaian Utama 3 Tahun Berjalan
            $table->integer('jumlah_siswa_2023')->nullable();
            $table->integer('jumlah_siswa_2024')->nullable();
            $table->integer('jumlah_siswa_2025')->nullable();
            $table->decimal('persentase_alumni_bekerja', 5, 2)->nullable();
            $table->decimal('persentase_alumni_wirausaha', 5, 2)->nullable();
            $table->decimal('persentase_alumni_tidak_terdeteksi', 5, 2)->nullable();
            $table->string('bosnas_2023')->nullable();
            $table->string('bosnas_2024')->nullable();
            $table->string('bosnas_2025')->nullable();
            $table->string('bosda_2023')->nullable();
            $table->string('bosda_2024')->nullable();
            $table->string('bosda_2025')->nullable();
            $table->string('spp_bppp_lain_2023')->nullable();
            $table->string('spp_bppp_lain_2024')->nullable();
            $table->string('spp_bppp_lain_2025')->nullable();
            $table->string('pendapatan_unit_usaha_2023')->nullable();
            $table->string('pendapatan_unit_usaha_2024')->nullable();
            $table->string('pendapatan_unit_usaha_2025')->nullable();
            $table->string('status_akreditasi')->nullable();
            $table->date('tanggal_akreditasi_mulai')->nullable();
            $table->date('tanggal_akreditasi_berakhir')->nullable();

            // Step 3: Layanan Pendidikan
            $table->text('model_layanan_pendidikan')->nullable();
            $table->text('capaian_layanan_menonjol')->nullable();
            $table->text('masalah_layanan_utama')->nullable();

            // Step 4: SDM
            $table->integer('pns_sertifikasi')->nullable();
            $table->integer('pns_non_sertifikasi')->nullable();
            $table->integer('gty_sertifikasi_inpassing')->nullable();
            $table->integer('gty_sertifikasi')->nullable();
            $table->integer('gty_non_sertifikasi')->nullable();
            $table->integer('gtt')->nullable();
            $table->integer('pty')->nullable();
            $table->integer('ptt')->nullable();
            $table->integer('jumlah_talenta')->nullable();
            $table->json('nama_talenta')->nullable();
            $table->json('alasan_talenta')->nullable();
            $table->json('kondisi_guru')->nullable();
            $table->json('masalah_sdm_utama')->nullable();

            // Step 5: Keuangan
            $table->text('sumber_dana_utama')->nullable();
            $table->string('kondisi_keuangan_akhir_tahun')->nullable();
            $table->text('catatan_pengelolaan_keuangan')->nullable();

            // Step 6: PPDB
            $table->text('metode_ppdb')->nullable();
            $table->text('hasil_ppdb_tahun_berjalan')->nullable();
            $table->text('masalah_utama_ppdb')->nullable();

            // Step 7: Unggulan
            $table->text('nama_program_unggulan')->nullable();
            $table->text('alasan_pemilihan_program')->nullable();
            $table->text('target_unggulan')->nullable();
            $table->text('kontribusi_unggulan')->nullable();
            $table->text('sumber_biaya_program')->nullable();
            $table->text('tim_program_unggulan')->nullable();

            // Step 8: Refleksi
            $table->text('keberhasilan_terbesar_tahun_ini')->nullable();
            $table->text('masalah_paling_berat_dihadapi')->nullable();
            $table->text('keputusan_sulit_diambil')->nullable();

            // Step 9: Risiko
            $table->text('risiko_terbesar_satpen_tahun_depan')->nullable();
            $table->json('fokus_perbaikan_tahun_depan')->nullable();

            // Step 10: Pernyataan
            $table->boolean('pernyataan_benar')->nullable();
            $table->text('signature_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_siswa_2023', 'jumlah_siswa_2024', 'jumlah_siswa_2025',
                'persentase_alumni_bekerja', 'persentase_alumni_wirausaha', 'persentase_alumni_tidak_terdeteksi',
                'bosnas_2023', 'bosnas_2024', 'bosnas_2025',
                'bosda_2023', 'bosda_2024', 'bosda_2025',
                'spp_bppp_lain_2023', 'spp_bppp_lain_2024', 'spp_bppp_lain_2025',
                'pendapatan_unit_usaha_2023', 'pendapatan_unit_usaha_2024', 'pendapatan_unit_usaha_2025',
                'status_akreditasi', 'tanggal_akreditasi_mulai', 'tanggal_akreditasi_berakhir',
                'model_layanan_pendidikan', 'capaian_layanan_menonjol', 'masalah_layanan_utama',
                'pns_sertifikasi', 'pns_non_sertifikasi', 'gty_sertifikasi_inpassing', 'gty_sertifikasi', 'gty_non_sertifikasi', 'gtt', 'pty', 'ptt',
                'jumlah_talenta', 'nama_talenta', 'alasan_talenta', 'kondisi_guru', 'masalah_sdm_utama',
                'sumber_dana_utama', 'kondisi_keuangan_akhir_tahun', 'catatan_pengelolaan_keuangan',
                'metode_ppdb', 'hasil_ppdb_tahun_berjalan', 'masalah_utama_ppdb',
                'nama_program_unggulan', 'alasan_pemilihan_program', 'target_unggulan', 'kontribusi_unggulan', 'sumber_biaya_program', 'tim_program_unggulan',
                'keberhasilan_terbesar_tahun_ini', 'masalah_paling_berat_dihadapi', 'keputusan_sulit_diambil',
                'risiko_terbesar_satpen_tahun_depan', 'fokus_perbaikan_tahun_depan',
                'pernyataan_benar', 'signature_data'
            ]);
        });
    }
};
