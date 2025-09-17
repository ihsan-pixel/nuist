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
            if (Schema::hasColumn('presensi_settings', 'batas_akhir_presensi_masuk')) {
                $table->dropColumn('batas_akhir_presensi_masuk');
            }
            if (Schema::hasColumn('presensi_settings', 'batas_diperbolehkan_presensi_pulang')) {
                $table->dropColumn('batas_diperbolehkan_presensi_pulang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->time('batas_akhir_presensi_masuk')->nullable()->after('radius_presensi')->comment('Legacy batas akhir presensi masuk');
            $table->time('batas_diperbolehkan_presensi_pulang')->nullable()->after('batas_akhir_presensi_masuk')->comment('Legacy batas diperbolehkan presensi pulang');
        });
    }
};
