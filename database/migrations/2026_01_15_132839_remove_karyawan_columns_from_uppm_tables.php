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
        Schema::table('uppm_settings', function (Blueprint $table) {
            $table->dropColumn(['nominal_karyawan_tetap', 'nominal_karyawan_tidak_tetap']);
        });

        Schema::table('uppm_school_data', function (Blueprint $table) {
            $table->dropColumn(['jumlah_karyawan_tetap', 'jumlah_karyawan_tidak_tetap']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uppm_settings', function (Blueprint $table) {
            $table->decimal('nominal_karyawan_tetap', 15, 2)->default(0);
            $table->decimal('nominal_karyawan_tidak_tetap', 15, 2)->default(0);
        });

        Schema::table('uppm_school_data', function (Blueprint $table) {
            $table->integer('jumlah_karyawan_tetap')->default(0);
            $table->integer('jumlah_karyawan_tidak_tetap')->default(0);
        });
    }
};
