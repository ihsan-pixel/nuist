<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biometric_profiles', function (Blueprint $table) {
            $table->unique('user_id', 'biometric_profiles_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('biometric_profiles', function (Blueprint $table) {
            $table->dropUnique('biometric_profiles_user_id_unique');
        });
    }
};
