<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The ketugasan column is already a string, so no schema change needed
        // This migration is for documentation purposes only
        // The allowed values are now: 'tenaga pendidik', 'kepala madrasah/sekolah', 'penjaga sekolah'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes needed as we're not modifying schema
    }
};
