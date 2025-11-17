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
        // Add contact fields to ppdb_pendaftar table (nullable to avoid breaking existing rows)
        if (Schema::hasTable('ppdb_pendaftar')) {
            Schema::table('ppdb_pendaftar', function (Blueprint $table) {
                if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_siswa')) {
                    $table->string('ppdb_nomor_whatsapp_siswa')->nullable()->after('berkas_kk');
                }
                if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_wali')) {
                    $table->string('ppdb_nomor_whatsapp_wali')->nullable()->after('ppdb_nomor_whatsapp_siswa');
                }
                if (!Schema::hasColumn('ppdb_pendaftar', 'ppdb_email_siswa')) {
                    $table->string('ppdb_email_siswa')->nullable()->after('ppdb_nomor_whatsapp_wali');
                }
            });
        }

        // Remove contact fields from madrasahs table if present
        if (Schema::hasTable('madrasahs')) {
            Schema::table('madrasahs', function (Blueprint $table) {
                if (Schema::hasColumn('madrasahs', 'ppdb_nomor_whatsapp_siswa')) {
                    $table->dropColumn('ppdb_nomor_whatsapp_siswa');
                }
                if (Schema::hasColumn('madrasahs', 'ppdb_nomor_whatsapp_wali')) {
                    $table->dropColumn('ppdb_nomor_whatsapp_wali');
                }
                if (Schema::hasColumn('madrasahs', 'ppdb_email_siswa')) {
                    $table->dropColumn('ppdb_email_siswa');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add to madrasahs (nullable)
        if (Schema::hasTable('madrasahs')) {
            Schema::table('madrasahs', function (Blueprint $table) {
                if (!Schema::hasColumn('madrasahs', 'ppdb_nomor_whatsapp_siswa')) {
                    $table->string('ppdb_nomor_whatsapp_siswa')->nullable()->after('ppdb_catatan_pengumuman');
                }
                if (!Schema::hasColumn('madrasahs', 'ppdb_nomor_whatsapp_wali')) {
                    $table->string('ppdb_nomor_whatsapp_wali')->nullable()->after('ppdb_nomor_whatsapp_siswa');
                }
                if (!Schema::hasColumn('madrasahs', 'ppdb_email_siswa')) {
                    $table->string('ppdb_email_siswa')->nullable()->after('ppdb_nomor_whatsapp_wali');
                }
            });
        }

        // Remove from ppdb_pendaftar
        if (Schema::hasTable('ppdb_pendaftar')) {
            Schema::table('ppdb_pendaftar', function (Blueprint $table) {
                if (Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_siswa')) {
                    $table->dropColumn('ppdb_nomor_whatsapp_siswa');
                }
                if (Schema::hasColumn('ppdb_pendaftar', 'ppdb_nomor_whatsapp_wali')) {
                    $table->dropColumn('ppdb_nomor_whatsapp_wali');
                }
                if (Schema::hasColumn('ppdb_pendaftar', 'ppdb_email_siswa')) {
                    $table->dropColumn('ppdb_email_siswa');
                }
            });
        }
    }
};
