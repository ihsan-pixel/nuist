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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_kepegawaian');
            $table->foreignId('status_kepegawaian_id')->nullable()->constrained('status_kepegawaian')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['status_kepegawaian_id']);
            $table->dropColumn('status_kepegawaian_id');
            $table->string('status_kepegawaian', 20)->nullable();
        });
    }
};
