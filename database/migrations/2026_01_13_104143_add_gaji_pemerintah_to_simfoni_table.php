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
        Schema::table('simfoni', function (Blueprint $table) {
            $table->decimal('gaji_pemerintah', 15, 2)->nullable()->after('gaji_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simfoni', function (Blueprint $table) {
            $table->dropColumn('gaji_pemerintah');
        });
    }
};
