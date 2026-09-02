<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('personal_access_tokens') || ! Schema::hasColumn('personal_access_tokens', 'id')) {
            return;
        }

        DB::statement(
            'ALTER TABLE `personal_access_tokens` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT'
        );
    }

    public function down(): void
    {
        // Keep the auto-increment property on rollback; removing it would
        // recreate the login failure for existing Sanctum token tables.
    }
};
