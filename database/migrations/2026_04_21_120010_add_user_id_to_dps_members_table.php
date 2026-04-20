<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dps_members', function (Blueprint $table) {
            if (!Schema::hasColumn('dps_members', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('madrasah_id');
                $table->index(['user_id']);
            }
        });

        // Foreign key depends on existing schema; add only if table exists.
        Schema::table('dps_members', function (Blueprint $table) {
            if (Schema::hasColumn('dps_members', 'user_id') && Schema::hasTable('users')) {
                // Avoid exception if constraint already exists in some environments.
                try {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                } catch (\Throwable $e) {
                    // no-op
                }
            }
        });
    }

    public function down()
    {
        Schema::table('dps_members', function (Blueprint $table) {
            if (Schema::hasColumn('dps_members', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Throwable $e) {
                    // no-op
                }
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

