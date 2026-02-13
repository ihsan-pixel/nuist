<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_nilai', function (Blueprint $table) {

            // Pastikan kolom ada
            if (
                Schema::hasColumn('tugas_nilai', 'tugas_talenta_level1_id') &&
                Schema::hasColumn('tugas_nilai', 'penilai_id')
            ) {
                $table->unique(
                    ['tugas_talenta_level1_id', 'penilai_id'],
                    'tugas_penilai_unique'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas_nilai', function (Blueprint $table) {
            $table->dropUnique('tugas_penilai_unique');
        });
    }
};
