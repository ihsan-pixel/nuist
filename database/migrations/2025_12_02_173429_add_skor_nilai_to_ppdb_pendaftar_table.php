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
            if (!Schema::hasColumn('ppdb_pendaftar', 'skor_nilai')) {
                $table->integer('skor_nilai')->nullable()->default(null);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('ppdb_pendaftar', 'skor_nilai')) {
                $table->dropColumn('skor_nilai');
            }
        });
    }
};
