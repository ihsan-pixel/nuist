<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, populate madrasah_id for existing presensi records where it's null
        DB::statement('
            UPDATE presensis p
            JOIN users u ON p.user_id = u.id
            SET p.madrasah_id = u.madrasah_id
            WHERE p.madrasah_id IS NULL
        ');

        // Drop the old unique constraint
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'tanggal']);
        });

        // Add new unique constraint that includes madrasah_id
        Schema::table('presensis', function (Blueprint $table) {
            $table->unique(['user_id', 'tanggal', 'madrasah_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new unique constraint
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'tanggal', 'madrasah_id']);
        });

        // Restore the old unique constraint
        Schema::table('presensis', function (Blueprint $table) {
            $table->unique(['user_id', 'tanggal']);
        });

        // Note: We don't unset madrasah_id back to null as it would be complex
        // and the down migration is mainly for rollback purposes
    }
};
