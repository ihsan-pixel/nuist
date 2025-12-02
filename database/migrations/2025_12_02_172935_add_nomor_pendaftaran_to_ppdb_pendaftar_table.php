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
            if (!Schema::hasColumn('ppdb_pendaftar', 'nomor_pendaftaran')) {
                $table->string('nomor_pendaftaran')->nullable()->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('ppdb_pendaftar', 'nomor_pendaftaran')) {
                $table->dropColumn('nomor_pendaftaran');
            }
        });
    }
};
