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
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->boolean('singleton')->default(true)->unique()->after('id')->comment('Ensure only one record exists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropUnique('presensi_settings_singleton_unique');
            $table->dropColumn('singleton');
        });
    }
};
