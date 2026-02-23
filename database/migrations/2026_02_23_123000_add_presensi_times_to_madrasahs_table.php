<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            // Avoid using ->after() to prevent failures when referenced column does not exist in older schemas
            $table->time('presensi_masuk_start')->nullable();
            $table->time('presensi_masuk_end')->nullable();
            $table->time('presensi_pulang_start')->nullable();
            $table->time('presensi_pulang_end')->nullable();
            $table->time('presensi_pulang_jumat')->nullable();
            $table->time('presensi_pulang_sabtu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->dropColumn([
                'presensi_masuk_start',
                'presensi_masuk_end',
                'presensi_pulang_start',
                'presensi_pulang_end',
                'presensi_pulang_jumat',
                'presensi_pulang_sabtu',
            ]);
        });
    }
};
