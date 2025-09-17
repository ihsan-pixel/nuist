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
            $table->integer('radius_presensi')->nullable()->after('batas_diperbolehkan_presensi_pulang')->comment('Radius presensi dalam meter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn('radius_presensi');
        });
    }
};
