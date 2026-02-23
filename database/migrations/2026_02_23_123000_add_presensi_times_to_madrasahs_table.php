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
            $table->time('presensi_masuk_start')->nullable()->after('jam_operasional_buka');
            $table->time('presensi_masuk_end')->nullable()->after('presensi_masuk_start');
            $table->time('presensi_pulang_start')->nullable()->after('presensi_masuk_end');
            $table->time('presensi_pulang_end')->nullable()->after('presensi_pulang_start');
            $table->time('presensi_pulang_jumat')->nullable()->after('presensi_pulang_end');
            $table->time('presensi_pulang_sabtu')->nullable()->after('presensi_pulang_jumat');
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
