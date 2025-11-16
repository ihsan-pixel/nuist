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
            // PPDB Settings columns
            $table->enum('ppdb_status', ['buka', 'tutup'])->default('tutup')->after('status');
            $table->dateTime('ppdb_jadwal_buka')->nullable()->after('jadwal_tutup');
            $table->dateTime('ppdb_jadwal_tutup')->nullable()->after('ppdb_jadwal_buka');
            $table->integer('ppdb_kuota_total')->nullable()->after('ppdb_jadwal_tutup');
            $table->dateTime('ppdb_jadwal_pengumuman')->nullable()->after('ppdb_kuota_total');
            $table->json('ppdb_kuota_jurusan')->nullable()->after('ppdb_jadwal_pengumuman');
            $table->json('ppdb_jalur')->nullable()->after('ppdb_kuota_jurusan');
            $table->text('ppdb_biaya_pendaftaran')->nullable()->after('ppdb_jalur');
            $table->text('ppdb_catatan_pengumuman')->nullable()->after('ppdb_biaya_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            $table->dropColumn([
                'ppdb_status',
                'ppdb_jadwal_buka',
                'ppdb_jadwal_tutup',
                'ppdb_kuota_total',
                'ppdb_jadwal_pengumuman',
                'ppdb_kuota_jurusan',
                'ppdb_jalur',
                'ppdb_biaya_pendaftaran',
                'ppdb_catatan_pengumuman'
            ]);
        });
    }
};
