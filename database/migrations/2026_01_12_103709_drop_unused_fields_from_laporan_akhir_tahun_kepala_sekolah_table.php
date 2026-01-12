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
            $table->dropColumn(['jumlah_guru', 'jumlah_siswa', 'jumlah_kelas', 'prestasi_madrasah', 'kendala_utama', 'program_kerja_tahun_depan', 'tanggal_laporan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            $table->integer('jumlah_guru')->nullable();
            $table->integer('jumlah_siswa')->nullable();
            $table->integer('jumlah_kelas')->nullable();
            $table->text('prestasi_madrasah')->nullable();
            $table->text('kendala_utama')->nullable();
            $table->text('program_kerja_tahun_depan')->nullable();
            $table->date('tanggal_laporan')->nullable();
        });
    }
};
