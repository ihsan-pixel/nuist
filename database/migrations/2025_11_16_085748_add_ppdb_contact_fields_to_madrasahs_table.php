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
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->string('ppdb_nomor_whatsapp_siswa')->nullable()->after('ppdb_catatan_pengumuman');
            $table->string('ppdb_nomor_whatsapp_wali')->nullable()->after('ppdb_nomor_whatsapp_siswa');
            $table->string('ppdb_email_siswa')->nullable()->after('ppdb_nomor_whatsapp_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->dropColumn([
                'ppdb_nomor_whatsapp_siswa',
                'ppdb_nomor_whatsapp_wali',
                'ppdb_email_siswa'
            ]);
        });
    }
};
