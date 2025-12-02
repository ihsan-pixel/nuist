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
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('ppdb_pendaftar', 'nilai_akhir_raport')) {
                $table->dropColumn('nilai_akhir_raport');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (!Schema::hasColumn('ppdb_pendaftar', 'nilai_akhir_raport')) {
                $table->decimal('nilai_akhir_raport', 5, 2)->nullable();
            }
        });
    }
};
