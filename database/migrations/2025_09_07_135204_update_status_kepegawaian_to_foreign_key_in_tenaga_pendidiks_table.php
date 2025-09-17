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
        Schema::table('tenaga_pendidiks', function (Blueprint $table) {
            // Drop the existing string column
            $table->dropColumn('status_kepegawaian');

            // Add foreign key column
            $table->foreignId('status_kepegawaian_id')->nullable()->constrained('status_kepegawaian')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_pendidiks', function (Blueprint $table) {
            // Drop the foreign key constraint and column
            $table->dropForeign(['status_kepegawaian_id']);
            $table->dropColumn('status_kepegawaian_id');

            // Restore the original string column
            $table->string('status_kepegawaian')->nullable();
        });
    }
};
