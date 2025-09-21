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
        Schema::table('users', function (Blueprint $table) {
            // First, ensure the ketugasan column exists
            if (!Schema::hasColumn('users', 'ketugasan')) {
                $table->string('ketugasan')->nullable();
            }

            // Modify the ketugasan column to use enum with only two allowed values
            $table->enum('ketugasan', ['tenaga pendidik', 'kepala madrasah/sekolah'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to string column
            $table->string('ketugasan')->nullable()->change();
        });
    }
};
