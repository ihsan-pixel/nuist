<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return; // SQLite/string roles already accept the new value.
        }

        $role = collect(Schema::getColumns('users'))->firstWhere('name', 'role');
        if (! $role || $role['type_name'] !== 'enum' || str_contains($role['type'], "'pengurus_bpppmnu'")) {
            return;
        }

        // Preserve the installed enum list, nullability, default, and collation.
        $type = substr($role['type'], 0, -1).",'pengurus_bpppmnu')";
        $nullable = $role['nullable'] ? ' NULL' : ' NOT NULL';
        // MySQL schema metadata can return enum defaults either as `user` or
        // as the already-quoted string `'user'`. Normalize before quoting it
        // for the ALTER statement, otherwise the latter becomes `'''user'''`.
        $defaultValue = $role['default'];
        if (is_string($defaultValue) && preg_match("/^'(.*)'$/s", $defaultValue, $matches)) {
            $defaultValue = str_replace("''", "'", $matches[1]);
        }

        $default = $defaultValue !== null
            ? " DEFAULT '".str_replace("'", "''", $defaultValue)."'"
            : ($role['nullable'] ? ' DEFAULT NULL' : '');
        $collation = ! empty($role['collation']) && preg_match('/^[a-zA-Z0-9_]+$/', $role['collation'])
            ? ' COLLATE '.$role['collation'] : '';

        DB::statement('ALTER TABLE `users` MODIFY COLUMN `role` '.$type.$collation.$nullable.$default);
    }

    public function down(): void
    {
        // Intentionally retain the additive role value: existing accounts must
        // never be truncated or silently reassigned when rolling back tables.
    }
};
