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
        Schema::table('tagihans', function (Blueprint $table) {
            $table->enum('jenis_pembayaran', ['online', 'cash'])->default('cash');
            $table->string('metode_pembayaran')->nullable(); // e.g., 'qris', 'va', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['jenis_pembayaran', 'metode_pembayaran']);
        });
    }
};
