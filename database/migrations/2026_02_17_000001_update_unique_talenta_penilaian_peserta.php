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
        Schema::table('talenta_penilaian_peserta', function (Blueprint $table) {
            // drop the old unique index on (talenta_peserta_id, user_id) if it exists
            if (Schema::hasColumn('talenta_penilaian_peserta', 'talenta_peserta_id')) {
                // index name as created in original migration
                try {
                    $table->dropUnique('talenta_penilaian_peserta_unique');
                } catch (\Exception $e) {
                    // ignore if it doesn't exist
                }
            }

            // add a composite unique including materi_id so penilaian is unique per (peserta, user, materi)
            $table->unique(['talenta_peserta_id', 'user_id', 'materi_id'], 'talenta_penilaian_peserta_unique_v2');
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
