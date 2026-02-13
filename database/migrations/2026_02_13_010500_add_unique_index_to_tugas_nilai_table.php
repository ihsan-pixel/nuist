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
        Schema::table('tugas_nilai', function (Blueprint $table) {
            // Prevent duplicate rows for same tugas & penilai (race conditions)
            if (!Schema::hasColumn('tugas_nilai', 'tugas_talenta_level1_id')) return;

            // create unique index if not exists
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = array_map(function($idx) { return $idx->getName(); }, $sm->listTableIndexes('tugas_nilai'));

            if (!in_array('tugas_penilai_unique', $indexes)) {
                $table->unique(['tugas_talenta_level1_id', 'penilai_id'], 'tugas_penilai_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_nilai', function (Blueprint $table) {
            $table->dropUnique('tugas_penilai_unique');
        });
    }
};
