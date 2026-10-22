<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mgmp_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Existing unassigned groups must receive an owner before restoring NOT NULL.
        if (\Illuminate\Support\Facades\DB::table('mgmp_groups')->whereNull('user_id')->exists()) {
            throw new \RuntimeException('Tentukan pengelola seluruh grup MGMP sebelum rollback.');
        }
        Schema::table('mgmp_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
