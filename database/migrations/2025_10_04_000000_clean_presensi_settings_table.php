<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear all existing records in presensi_settings to remove duplicates and legacy data
        DB::table('presensi_settings')->truncate();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for rollback, as this is a cleaning migration
    }
};
