<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration will DROP the old unique index named
     * `talenta_penilaian_peserta_unique` (if it exists) and add a new
     * unique index that includes `materi_id` so uniqueness is per
     * (talenta_peserta_id, user_id, materi_id).
     *
     * NOTE: The migration uses raw statements with guards to avoid
     * fatal errors if the indexes are not present. Run in a maintenance
     * window and ensure you have a DB backup before applying.
     *
     * @return void
     */
    public function up(): void
    {
        // If the old index exists, drop it.
        $oldIndex = DB::select("SHOW INDEX FROM `talenta_penilaian_peserta` WHERE Key_name = 'talenta_penilaian_peserta_unique'");
        if (!empty($oldIndex)) {
            DB::statement("ALTER TABLE `talenta_penilaian_peserta` DROP INDEX `talenta_penilaian_peserta_unique`");
        }

        // Add the new unique index including materi_id if not present.
        $newIndex = DB::select("SHOW INDEX FROM `talenta_penilaian_peserta` WHERE Key_name = 'talenta_penilaian_peserta_unique_v2'");
        if (empty($newIndex)) {
            DB::statement("ALTER TABLE `talenta_penilaian_peserta` ADD UNIQUE KEY `talenta_penilaian_peserta_unique_v2` (`talenta_peserta_id`, `user_id`, `materi_id`)");
        }
    }

    /**
     * Reverse the migrations.
     *
     * This will attempt to drop the v2 index and recreate the old
     * unique index on (talenta_peserta_id, user_id).
     *
     * @return void
     */
    public function down(): void
    {
        $newIndex = DB::select("SHOW INDEX FROM `talenta_penilaian_peserta` WHERE Key_name = 'talenta_penilaian_peserta_unique_v2'");
        if (!empty($newIndex)) {
            DB::statement("ALTER TABLE `talenta_penilaian_peserta` DROP INDEX `talenta_penilaian_peserta_unique_v2`");
        }

        $oldIndex = DB::select("SHOW INDEX FROM `talenta_penilaian_peserta` WHERE Key_name = 'talenta_penilaian_peserta_unique'");
        if (empty($oldIndex)) {
            DB::statement("ALTER TABLE `talenta_penilaian_peserta` ADD UNIQUE KEY `talenta_penilaian_peserta_unique` (`talenta_peserta_id`, `user_id`)");
        }
    }
};
