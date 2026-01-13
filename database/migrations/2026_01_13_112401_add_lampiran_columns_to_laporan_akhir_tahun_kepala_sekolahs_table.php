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
            $table->string('lampiran_step_1')->nullable();
            $table->string('lampiran_step_2')->nullable();
            $table->string('lampiran_step_3')->nullable();
            $table->string('lampiran_step_4')->nullable();
            $table->string('lampiran_step_5')->nullable();
            $table->string('lampiran_step_6')->nullable();
            $table->string('lampiran_step_7')->nullable();
            $table->string('lampiran_step_8')->nullable();
            $table->string('lampiran_step_9')->nullable();
            $table->string('lampiran_step_10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhir_tahun_kepala_sekolahs', function (Blueprint $table) {
            //
        });
    }
};
