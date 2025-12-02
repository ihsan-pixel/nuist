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
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_jalur_id')) {
                $table->unsignedBigInteger('ppdb_jalur_id')->nullable()->after('ppdb_setting_id');
                $table->foreign('ppdb_jalur_id')->references('id')->on('ppdb_jalurs')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('ppdb_pendaftar', 'ppdb_jalur_id')) {
                $table->dropForeign(['ppdb_jalur_id']);
                $table->dropColumn('ppdb_jalur_id');
            }
        });
    }
};
