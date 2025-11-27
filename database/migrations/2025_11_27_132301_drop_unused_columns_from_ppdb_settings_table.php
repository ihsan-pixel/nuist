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
                'periode_presensi_mulai',
                'periode_presensi_selesai',
                'wajib_unggah_foto',
                'wajib_unggah_kk',
                'syarat_tambahan',
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
            $table->date('periode_presensi_mulai')->nullable()->after('informasi_tambahan');
            $table->date('periode_presensi_selesai')->nullable()->after('periode_presensi_mulai');
            $table->boolean('wajib_unggah_foto')->default(false)->after('periode_presensi_selesai');
            $table->boolean('wajib_unggah_kk')->default(false)->after('wajib_unggah_foto');
            $table->text('syarat_tambahan')->nullable()->after('wajib_unggah_kk');
        });
    }
};
