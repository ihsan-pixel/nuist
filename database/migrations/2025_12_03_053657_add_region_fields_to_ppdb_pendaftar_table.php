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
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            // Data Wilayah - sesuai form daftar.blade.php
            if (!Schema::hasColumn('ppdb_pendaftar', 'provinsi')) {
                $table->string('provinsi')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'kabupaten')) {
                $table->string('kabupaten')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'kecamatan')) {
                $table->string('kecamatan')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'desa')) {
                $table->string('desa')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'provinsi', 'kabupaten', 'kecamatan', 'desa'
            ]);
        });
    }
};
