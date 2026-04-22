<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('izins', function (Blueprint $table) {
            if (!Schema::hasColumn('izins', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal');
            }

            if (!Schema::hasColumn('izins', 'hari_presensi')) {
                $table->json('hari_presensi')->nullable()->after('tanggal_selesai');
            }

            if (!Schema::hasColumn('izins', 'hari_tidak_presensi')) {
                $table->json('hari_tidak_presensi')->nullable()->after('hari_presensi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('izins', function (Blueprint $table) {
            if (Schema::hasColumn('izins', 'hari_tidak_presensi')) {
                $table->dropColumn('hari_tidak_presensi');
            }

            if (Schema::hasColumn('izins', 'hari_presensi')) {
                $table->dropColumn('hari_presensi');
            }

            if (Schema::hasColumn('izins', 'tanggal_selesai')) {
                $table->dropColumn('tanggal_selesai');
            }
        });
    }
};
