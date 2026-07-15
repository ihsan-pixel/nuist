<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('app_settings', 'mobile_attendance_verification_mode')) {
                $table->string('mobile_attendance_verification_mode', 30)
                    ->default('selfie');
            }
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            if (Schema::hasColumn('app_settings', 'mobile_attendance_verification_mode')) {
                $table->dropColumn('mobile_attendance_verification_mode');
            }
        });
    }
};
