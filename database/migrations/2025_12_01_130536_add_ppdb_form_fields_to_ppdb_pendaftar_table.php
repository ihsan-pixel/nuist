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
            // Data Diri Siswa - sesuai form daftar.blade.php
            if (!Schema::hasColumn('ppdb_pendaftar', 'nik')) {
                $table->string('nik')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            }

            // Data Kontak - sesuai form daftar.blade.php
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_siswa')) {
                $table->string('ppdb_nomor_whatsapp_siswa')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_wali')) {
                $table->string('ppdb_nomor_whatsapp_wali')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_email_siswa')) {
                $table->string('ppdb_email_siswa')->nullable();
            }

            // Opsi Pilihan Ke 2 - sesuai form daftar.blade.php
            if (!Schema::hasColumn('ppdb_pendaftar', 'use_opsi_ke_2')) {
                $table->boolean('use_opsi_ke_2')->default(false);
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_opsi_pilihan_ke_2')) {
                $table->unsignedBigInteger('ppdb_opsi_pilihan_ke_2')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_jurusan_pilihan_alt')) {
                $table->json('ppdb_jurusan_pilihan_alt')->nullable();
            }

            // Jalur PPDB - sesuai form daftar.blade.php
            if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_jalur_id')) {
                $table->unsignedBigInteger('ppdb_jalur_id')->nullable();
            }

            // Status dan ranking
            if (!Schema::hasColumn('ppdb_pendaftar', 'ranking')) {
                $table->integer('ranking')->nullable();
            }
            if (!Schema::hasColumn('ppdb_pendaftar', 'skor_total')) {
                $table->integer('skor_total')->default(0);
            }

            // Rencana setelah lulus - sesuai controller
            if (!Schema::hasColumn('ppdb_pendaftar', 'rencana_lulus')) {
                $table->enum('rencana_lulus', ['kuliah', 'kerja'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'ppdb_nomor_whatsapp_siswa', 'ppdb_nomor_whatsapp_wali', 'ppdb_email_siswa',
                'use_opsi_ke_2', 'ppdb_opsi_pilihan_ke_2', 'ppdb_jurusan_pilihan_alt',
                'ppdb_jalur_id', 'ranking', 'skor_total', 'rencana_lulus'
            ]);
        });
    }
};
