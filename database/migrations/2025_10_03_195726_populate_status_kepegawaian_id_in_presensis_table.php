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
        // Populate status_kepegawaian_id for existing presensi records
        DB::statement('
            UPDATE presensis p
            JOIN users u ON p.user_id = u.id
            SET p.status_kepegawaian_id = u.status_kepegawaian_id
            WHERE p.status_kepegawaian_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, set status_kepegawaian_id to NULL for rollback
        DB::statement('
            UPDATE presensis
            SET status_kepegawaian_id = NULL
        ');
    }
};
