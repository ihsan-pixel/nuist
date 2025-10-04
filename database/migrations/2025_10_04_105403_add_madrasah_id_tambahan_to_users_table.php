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
            $table->foreignId('madrasah_id_tambahan')->nullable()->constrained('madrasahs')->onDelete('set null')->after('pemenuhan_beban_kerja_lain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['madrasah_id_tambahan']);
            $table->dropColumn('madrasah_id_tambahan');
        });
    }
};
