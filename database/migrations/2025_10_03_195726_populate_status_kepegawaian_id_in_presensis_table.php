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
            UPDATE presensis
            SET status_kepegawaian_id = users.status_kepegawaian_id
            FROM users
            WHERE presensis.user_id = users.id
            AND presensis.status_kepegawaian_id IS NULL
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
