<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spp_siswa_bills', function (Blueprint $table) {
            if (Schema::hasColumn('spp_siswa_bills', 'denda')) {
                $table->dropColumn('denda');
            }
        });

        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            if (Schema::hasColumn('spp_siswa_settings', 'denda_harian')) {
                $table->dropColumn('denda_harian');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spp_siswa_bills', function (Blueprint $table) {
            if (!Schema::hasColumn('spp_siswa_bills', 'denda')) {
                $table->decimal('denda', 15, 2)->default(0)->after('nominal');
            }
        });

        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('spp_siswa_settings', 'denda_harian')) {
                $table->decimal('denda_harian', 15, 2)->default(0)->after('tanggal_jatuh_tempo');
            }
        });
    }
};
