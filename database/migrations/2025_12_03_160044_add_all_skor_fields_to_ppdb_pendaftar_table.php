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
            // $table->integer('skor_nilai')->default(0)->after('rata_rata_nilai_raport');
            // $table->integer('skor_prestasi')->default(0)->after('skor_nilai');
            // $table->integer('skor_domisili')->default(0)->after('skor_prestasi');
            // $table->integer('skor_dokumen')->default(0)->after('skor_domisili');
            $table->integer('skor_total')->default(0)->after('skor_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn(['skor_total']);
        });
    }
};
