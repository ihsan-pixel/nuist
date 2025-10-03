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
            $table->foreignId('status_kepegawaian_id')->nullable()->constrained('status_kepegawaian')->onDelete('cascade');
            $table->unique('status_kepegawaian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropForeign(['status_kepegawaian_id']);
            $table->dropUnique(['status_kepegawaian_id']);
            $table->dropColumn('status_kepegawaian_id');
        });
    }
};
