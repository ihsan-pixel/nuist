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
        Schema::table('uppm_settings', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn([
                'nominal_guru_tetap',
                'nominal_guru_tidak_tetap',
                'nominal_guru_pns',
                'nominal_guru_pppk'
            ]);

            // Tambah kolom baru sesuai status kepegawaian
            $table->decimal('nominal_pns_sertifikasi', 15, 2)->default(0)->after('nominal_siswa');
            $table->decimal('nominal_pns_non_sertifikasi', 15, 2)->default(0)->after('nominal_pns_sertifikasi');
            $table->decimal('nominal_gty_sertifikasi', 15, 2)->default(0)->after('nominal_pns_non_sertifikasi');
            $table->decimal('nominal_gty_sertifikasi_inpassing', 15, 2)->default(0)->after('nominal_gty_sertifikasi');
            $table->decimal('nominal_gty_non_sertifikasi', 15, 2)->default(0)->after('nominal_gty_sertifikasi_inpassing');
            $table->decimal('nominal_gtt', 15, 2)->default(0)->after('nominal_gty_non_sertifikasi');
            $table->decimal('nominal_pty', 15, 2)->default(0)->after('nominal_gtt');
            $table->decimal('nominal_ptt', 15, 2)->default(0)->after('nominal_pty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uppm_settings', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn([
                'nominal_pns_sertifikasi',
                'nominal_pns_non_sertifikasi',
                'nominal_gty_sertifikasi',
                'nominal_gty_sertifikasi_inpassing',
                'nominal_gty_non_sertifikasi',
                'nominal_gtt',
                'nominal_pty',
                'nominal_ptt'
            ]);

            // Tambah kembali kolom lama
            $table->decimal('nominal_guru_tetap', 15, 2)->default(0);
            $table->decimal('nominal_guru_tidak_tetap', 15, 2)->default(0);
            $table->decimal('nominal_guru_pns', 15, 2)->default(0);
            $table->decimal('nominal_guru_pppk', 15, 2)->default(0);
        });
    }
};
