<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Roles are stored as strings in the existing users table. This migration
        // documents the new role without changing existing users or permissions.
    }

    public function down(): void
    {
        // No schema change is required for a string-backed role.
    }
};
