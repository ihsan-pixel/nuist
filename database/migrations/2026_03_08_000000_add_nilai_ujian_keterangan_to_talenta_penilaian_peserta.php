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
        Schema::table('talenta_penilaian_peserta', function (Blueprint $table) {
            if (!Schema::hasColumn('talenta_penilaian_peserta', 'nilai_ujian')) {
                $table->unsignedSmallInteger('nilai_ujian')->nullable()->after('sikap');
            }
            if (!Schema::hasColumn('talenta_penilaian_peserta', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('nilai_ujian');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_penilaian_peserta', function (Blueprint $table) {
            if (Schema::hasColumn('talenta_penilaian_peserta', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('talenta_penilaian_peserta', 'nilai_ujian')) {
                $table->dropColumn('nilai_ujian');
            }
        });
    }
};
