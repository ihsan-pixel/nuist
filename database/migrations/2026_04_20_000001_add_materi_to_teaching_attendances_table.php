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
        if (Schema::hasTable('teaching_attendances') && !Schema::hasColumn('teaching_attendances', 'materi')) {
            Schema::table('teaching_attendances', function (Blueprint $table) {
                $table->text('materi')->nullable()->after('lokasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('teaching_attendances') && Schema::hasColumn('teaching_attendances', 'materi')) {
            Schema::table('teaching_attendances', function (Blueprint $table) {
                $table->dropColumn('materi');
            });
        }
    }
};
