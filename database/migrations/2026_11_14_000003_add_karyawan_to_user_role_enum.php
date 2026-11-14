<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $role = collect(Schema::getColumns('users'))->firstWhere('name', 'role');

        if (! $role || $role['type_name'] !== 'enum' || str_contains($role['type'], "'karyawan'")) {
            return;
        }

        $type = substr($role['type'], 0, -1).",'karyawan')";
        $nullable = $role['nullable'] ? ' NULL' : ' NOT NULL';
        $defaultValue = $role['default'];

        if (is_string($defaultValue) && preg_match("/^'(.*)'$/s", $defaultValue, $matches)) {
            $defaultValue = str_replace("''", "'", $matches[1]);
        }

        $default = $defaultValue !== null
            ? " DEFAULT '".str_replace("'", "''", (string) $defaultValue)."'"
            : ($role['nullable'] ? ' DEFAULT NULL' : '');

        $collation = ! empty($role['collation']) && preg_match('/^[a-zA-Z0-9_]+$/', $role['collation'])
            ? ' COLLATE '.$role['collation'] : '';

        DB::statement('ALTER TABLE `users` MODIFY COLUMN `role` '.$type.$collation.$nullable.$default);
    }

    public function down(): void
    {
        // Retain the additive role so existing karyawan accounts are not damaged.
    }
};
