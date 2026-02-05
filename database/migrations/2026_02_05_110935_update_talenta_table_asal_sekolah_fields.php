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
        Schema::table('talenta', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta', function (Blueprint $table) {
            // Recreate the old enum column
            $table->enum('asal_sekolah', ['sd', 'smp', 'sma', 's1', 's2', 's3'])->after('foto_keluarga');

            // Drop the new columns
            $table->dropColumn([
                'asal_sekolah_sd',
                'asal_sekolah_smp',
                'asal_sekolah_sma',
                'asal_sekolah_s1',
                'asal_sekolah_s2',
                'asal_sekolah_s3'
            ]);
        });
    }
};
