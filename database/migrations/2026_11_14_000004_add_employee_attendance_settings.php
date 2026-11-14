<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('app_settings', 'employee_attendance_enabled')) $table->boolean('employee_attendance_enabled')->default(true);
            if (! Schema::hasColumn('app_settings', 'employee_wifi_allowed_ips')) $table->text('employee_wifi_allowed_ips')->nullable();
            if (! Schema::hasColumn('app_settings', 'employee_entry_start')) $table->string('employee_entry_start', 5)->default('06:00');
            if (! Schema::hasColumn('app_settings', 'employee_entry_end')) $table->string('employee_entry_end', 5)->default('10:00');
            if (! Schema::hasColumn('app_settings', 'employee_exit_start')) $table->string('employee_exit_start', 5)->default('15:00');
            if (! Schema::hasColumn('app_settings', 'employee_exit_end')) $table->string('employee_exit_end', 5)->default('22:00');
        });
    }

    public function down(): void {}
};
