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
        // Modify the existing id column to be auto-incrementing primary key
        DB::statement('ALTER TABLE laporan_akhir_tahun_kepala_sekolah MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            //
        });
    }
};
