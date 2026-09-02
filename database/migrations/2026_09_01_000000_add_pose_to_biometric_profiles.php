<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('biometric_profiles', 'pose')) {
            Schema::table('biometric_profiles', function (Blueprint $table) {
                $table->string('pose', 16)->nullable()->after('model_version')->index();
            });
        }

        try {
            Schema::table('biometric_profiles', function (Blueprint $table) {
                $table->dropUnique('biometric_profiles_user_id_unique');
            });
        } catch (\Throwable) {
            // Fresh databases may not contain the historical unique index.
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('biometric_profiles', 'pose')) {
            Schema::table('biometric_profiles', function (Blueprint $table) {
                $table->dropColumn('pose');
            });
        }
    }
};
