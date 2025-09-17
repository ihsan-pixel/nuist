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
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->time('waktu_mulai_presensi_masuk')->nullable()->after('batas_diperbolehkan_presensi_pulang')->comment('Waktu mulai presensi masuk');
            $table->time('waktu_akhir_presensi_masuk')->nullable()->after('waktu_mulai_presensi_masuk')->comment('Waktu akhir presensi masuk (setelah ini terlambat)');
            $table->time('waktu_mulai_presensi_pulang')->nullable()->after('waktu_akhir_presensi_masuk')->comment('Waktu mulai presensi pulang');
            $table->time('waktu_akhir_presensi_pulang')->nullable()->after('waktu_mulai_presensi_pulang')->comment('Waktu akhir presensi pulang (setelah ini tidak bisa presensi)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn([
                'waktu_mulai_presensi_masuk',
                'waktu_akhir_presensi_masuk',
                'waktu_mulai_presensi_pulang',
                'waktu_akhir_presensi_pulang'
            ]);
        });
    }
};
