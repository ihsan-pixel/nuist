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
        Schema::table('tugas_talenta_level1', function (Blueprint $table) {
            if (!Schema::hasColumn('tugas_talenta_level1', 'kelompok_id')) {
                $table->unsignedBigInteger('kelompok_id')->nullable()->after('user_id');
                // optional foreign key, keep nullable to avoid migration issues if table does not exist
                $table->foreign('kelompok_id')->references('id')->on('talenta_kelompoks')->onDelete('set null');
                $table->index(['kelompok_id', 'area', 'jenis_tugas']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_talenta_level1', function (Blueprint $table) {
            if (Schema::hasColumn('tugas_talenta_level1', 'kelompok_id')) {
                $table->dropForeign(['kelompok_id']);
                $table->dropIndex(['kelompok_id', 'area', 'jenis_tugas']);
                $table->dropColumn('kelompok_id');
            }
        });
    }
};
