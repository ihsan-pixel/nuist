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
            $table->boolean('is_fake_location_keluar')->default(false)->after('is_fake_location');
            $table->json('fake_location_analysis_keluar')->nullable()->after('fake_location_analysis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['is_fake_location_keluar', 'fake_location_analysis_keluar']);
        });
    }
};
