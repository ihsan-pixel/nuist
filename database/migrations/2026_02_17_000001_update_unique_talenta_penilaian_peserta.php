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
        // NOTE: This migration attempts to change a unique index to include `materi_id`.
        // In many production databases the existing index may be required by foreign
        // key constraints and cannot be dropped automatically. To avoid causing
        // failures during `php artisan migrate` we skip making schema changes here.
        //
        // If you want to apply the unique-index change, perform the following steps
        // manually on the database after reviewing constraints and backups:
        // 1. Inspect any foreign keys referencing `talenta_penilaian_peserta` and
        //    drop/adjust them as appropriate.
        // 2. ALTER TABLE to drop the old unique index `talenta_penilaian_peserta_unique`.
        // 3. ALTER TABLE to add the new unique index on
        //    (talenta_peserta_id, user_id, materi_id) named
        //    `talenta_penilaian_peserta_unique_v2`.
        //
        // We're intentionally leaving this migration as a no-op to avoid breaking
        // production deployments. The application controller already handles
        // legacy rows safely.
        echo "Skipping automatic unique-index migration for talenta_penilaian_peserta.\n";
        return;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_penilaian_peserta', function (Blueprint $table) {
            try {
                $table->dropUnique('talenta_penilaian_peserta_unique_v2');
            } catch (\Exception $e) {
                // ignore
            }

            // restore the old unique index (without materi_id)
            $table->unique(['talenta_peserta_id', 'user_id'], 'talenta_penilaian_peserta_unique');
        });
    }
};
