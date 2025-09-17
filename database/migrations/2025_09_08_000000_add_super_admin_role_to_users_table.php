<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperAdminRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update role column to include 'super_admin'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->change();
        });

        // If you want to update existing roles or add super_admin users, you can do it here or via seeder
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->change();
        });
    }
}
