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
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->json('polygon_koordinat_2')->nullable()->after('polygon_koordinat')->comment('Koordinat poligon kedua untuk area presensi');
            $table->boolean('enable_dual_polygon')->default(false)->after('polygon_koordinat_2')->comment('Aktifkan penggunaan dua poligon presensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->dropColumn(['polygon_koordinat_2', 'enable_dual_polygon']);
        });
    }
};
