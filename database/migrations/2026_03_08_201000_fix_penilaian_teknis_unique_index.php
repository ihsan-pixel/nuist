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
        Schema::table('talenta_penilaian_teknis', function (Blueprint $table) {
            // Remove the old unique constraint on user_id (if present)
            try {
                $table->dropUnique(['user_id']);
            } catch (\Exception $e) {
                // ignore if it doesn't exist
            }

            // Add composite unique index so each (talenta_layanan_teknis_id, user_id)
            // can only have one row — this supports multiple teknis per user.
            if (!Schema::hasColumn('talenta_penilaian_teknis', 'talenta_layanan_teknis_id')) {
                // If column missing for some reason, don't add the index
                return;
            }

            try {
                $table->unique(['talenta_layanan_teknis_id', 'user_id'], 'talenta_penilaian_teknis_teknis_user_unique');
            } catch (\Exception $e) {
                // ignore if already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_penilaian_teknis', function (Blueprint $table) {
            try {
                $table->dropUnique('talenta_penilaian_teknis_teknis_user_unique');
            } catch (\Exception $e) {
                // ignore
            }

            try {
                $table->unique('user_id');
            } catch (\Exception $e) {
                // ignore
            }
        });
    }
};
