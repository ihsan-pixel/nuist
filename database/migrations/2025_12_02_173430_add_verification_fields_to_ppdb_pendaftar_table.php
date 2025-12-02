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
            if (!Schema::hasColumn('ppdb_pendaftar', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'diverifikasi_oleh')) {
                $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'diverifikasi_tanggal')) {
                $table->timestamp('diverifikasi_tanggal')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'diseleksi_oleh')) {
                $table->unsignedBigInteger('diseleksi_oleh')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'diseleksi_tanggal')) {
                $table->timestamp('diseleksi_tanggal')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('ppdb_pendaftar', 'catatan_verifikasi')) {
                $table->dropColumn('catatan_verifikasi');
            }
            if (Schema::hasColumn('ppdb_pendaftar', 'diverifikasi_oleh')) {
                $table->dropColumn('diverifikasi_oleh');
            }
            if (Schema::hasColumn('ppdb_pendaftar', 'diverifikasi_tanggal')) {
                $table->dropColumn('diverifikasi_tanggal');
            }
            if (Schema::hasColumn('ppdb_pendaftar', 'diseleksi_oleh')) {
                $table->dropColumn('diseleksi_oleh');
            }
            if (Schema::hasColumn('ppdb_pendaftar', 'diseleksi_tanggal')) {
                $table->dropColumn('diseleksi_tanggal');
            }
        });
    }
};
