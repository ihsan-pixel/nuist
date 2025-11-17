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
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('selfie_masuk_path')->nullable()->after('location_readings');
            $table->string('selfie_keluar_path')->nullable()->after('selfie_masuk_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['selfie_masuk_path', 'selfie_keluar_path']);
        });
    }
};
