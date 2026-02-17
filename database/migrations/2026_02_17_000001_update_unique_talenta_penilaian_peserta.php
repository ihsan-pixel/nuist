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
        // Before attempting to drop the old unique index, check if any foreign keys
        // in other tables reference `talenta_penilaian_peserta`. MySQL prevents
        // dropping an index that is required by a foreign key constraint.

        $refs = DB::select(<<<'SQL'
            SELECT DISTINCT TABLE_NAME, CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
              AND REFERENCED_TABLE_NAME = 'talenta_penilaian_peserta'
              AND REFERENCED_COLUMN_NAME IN ('talenta_peserta_id', 'user_id')
        SQL
        );

        if (count($refs) > 0) {
            // There are referencing foreign keys; skip altering the unique index to avoid
            // breaking constraints. Log a message to the migration output so operator
            // can inspect and handle it manually.
            echo "Skipping unique-index change for talenta_penilaian_peserta because other tables reference it via foreign keys:\n";
            foreach ($refs as $r) {
                echo " - {$r->TABLE_NAME} -> constraint {$r->CONSTRAINT_NAME}\n";
            }
            echo "Please drop or modify those foreign keys manually if you wish to change the unique index.\n";
            return;
        }

        Schema::table('talenta_penilaian_peserta', function (Blueprint $table) {
            // drop the old unique index on (talenta_peserta_id, user_id) if it exists
            if (Schema::hasColumn('talenta_penilaian_peserta', 'talenta_peserta_id')) {
                try {
                    $table->dropUnique('talenta_penilaian_peserta_unique');
                } catch (\Exception $e) {
                    // ignore if it doesn't exist or cannot be dropped for some reason
                }
            }

            // add a composite unique including materi_id so penilaian is unique per (peserta, user, materi)
            try {
                $table->unique(['talenta_peserta_id', 'user_id', 'materi_id'], 'talenta_penilaian_peserta_unique_v2');
            } catch (\Exception $e) {
                // if adding the unique fails, ignore to avoid breaking migration
            }
        });
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
