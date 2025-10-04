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
            $table->enum('hari_kbm', ['5', '6'])->nullable()->after('polygon_koordinat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->dropColumn('hari_kbm');
        });
    }
};
