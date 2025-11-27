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
        Schema::table('ppdb_settings', function (Blueprint $table) {
            // Drop unused columns that are not used in PPDB context
            $table->dropColumn([
                'status',
                'jadwal_buka',
                'jadwal_tutup',
                'biaya_pendidikan',
                'informasi_tambahan',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            // Restore the dropped columns
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('tahun');
            $table->dateTime('jadwal_buka')->nullable()->after('status');
            $table->dateTime('jadwal_tutup')->nullable()->after('jadwal_buka');
            $table->text('biaya_pendidikan')->nullable()->after('ekstrakurikuler');
            $table->text('informasi_tambahan')->nullable()->after('biaya_pendidikan');
        });
    }
};
