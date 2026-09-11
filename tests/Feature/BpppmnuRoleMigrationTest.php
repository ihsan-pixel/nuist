<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BpppmnuRoleMigrationTest extends TestCase
{
    public function test_mysql_role_migration_preserves_existing_enum_and_default(): void
    {
        Schema::shouldReceive('getColumns')->once()->with('users')->andReturn([
            ['name' => 'role', 'type_name' => 'enum', 'type' => "enum('user','admin_yayasan','pengurus')", 'nullable' => false, 'default' => "'user'", 'collation' => 'utf8mb4_unicode_ci'],
        ]);
        DB::shouldReceive('getDriverName')->once()->andReturn('mysql');
        DB::shouldReceive('statement')->once()->with("ALTER TABLE `users` MODIFY COLUMN `role` enum('user','admin_yayasan','pengurus','pengurus_bpppmnu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'")->andReturn(true);
        (require database_path('migrations/2026_09_11_000000_add_pengurus_bpppmnu_role.php'))->up();
    }

    public function test_role_migration_is_idempotent(): void
    {
        Schema::shouldReceive('getColumns')->once()->with('users')->andReturn([
            ['name' => 'role', 'type_name' => 'enum', 'type' => "enum('user','pengurus_bpppmnu')"],
        ]);
        DB::shouldReceive('getDriverName')->once()->andReturn('mysql');
        DB::shouldReceive('statement')->never();
        (require database_path('migrations/2026_09_11_000000_add_pengurus_bpppmnu_role.php'))->up();
    }
}
