<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('teaching_attendances') || ! Schema::hasColumn('teaching_attendances', 'id')) {
            return;
        }

        // This table predates the current migrations and may exist without an
        // auto-incrementing primary key in an imported database.
        if (DB::getDriverName() === 'mysql') {
            $database = DB::getDatabaseName();
            $hasPrimaryKey = (bool) DB::scalar(
                'SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
                 WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = \'PRIMARY KEY\'',
                [$database, 'teaching_attendances']
            );
            $hasIdKey = (bool) DB::scalar(
                'SELECT COUNT(*) FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
                 AND INDEX_NAME IN (\'PRIMARY\', \'teaching_attendances_id_unique\')',
                [$database, 'teaching_attendances', 'id']
            );

            if (! $hasIdKey && ! $hasPrimaryKey) {
                DB::statement('ALTER TABLE `teaching_attendances` ADD PRIMARY KEY (`id`)');
            } elseif (! $hasIdKey) {
                DB::statement(
                    'ALTER TABLE `teaching_attendances` ADD UNIQUE KEY `teaching_attendances_id_unique` (`id`)'
                );
            }

            DB::statement(
                'ALTER TABLE `teaching_attendances` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT'
            );
        }
    }

    public function down(): void
    {
        // Keep the generated-key behavior on rollback so existing inserts do
        // not become invalid again.
    }
};
