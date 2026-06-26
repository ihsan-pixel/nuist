<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'scod')) {
                $table->string('scod', 50)->nullable()->after('madrasah_id');
                $table->index(['madrasah_id', 'scod']);
            }
        });

        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropUnique('siswa_email_unique');
            });
        } catch (\Throwable) {
        }

        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropUnique('siswa_nis_unique');
            });
        } catch (\Throwable) {
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('UPDATE siswa s LEFT JOIN madrasahs m ON m.id = s.madrasah_id SET s.scod = COALESCE(NULLIF(s.scod, \'\'), m.scod)');
            DB::statement('ALTER TABLE siswa MODIFY nis VARCHAR(50) NULL');
            DB::statement('ALTER TABLE siswa MODIFY nama_lengkap VARCHAR(255) NULL');
            DB::statement('ALTER TABLE siswa MODIFY kelas VARCHAR(50) NULL');
            DB::statement('ALTER TABLE siswa MODIFY nama_madrasah VARCHAR(255) NULL');
            DB::statement('ALTER TABLE siswa MODIFY alamat TEXT NULL');
            DB::statement('ALTER TABLE siswa MODIFY email VARCHAR(255) NULL');
            DB::statement('ALTER TABLE siswa MODIFY password VARCHAR(255) NULL');
        }

        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->unique(['madrasah_id', 'nis'], 'siswa_madrasah_id_nis_unique');
            });
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropUnique('siswa_madrasah_id_nis_unique');
            });
        } catch (\Throwable) {
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE siswa SET nis = COALESCE(NULLIF(nis, ''), CONCAT('NIS-', id))");
            DB::statement("UPDATE siswa SET nama_lengkap = COALESCE(NULLIF(nama_lengkap, ''), CONCAT('SISWA ', id))");
            DB::statement("UPDATE siswa SET kelas = COALESCE(NULLIF(kelas, ''), '-')");
            DB::statement("UPDATE siswa SET nama_madrasah = COALESCE(NULLIF(nama_madrasah, ''), '-')");
            DB::statement("UPDATE siswa SET alamat = COALESCE(NULLIF(alamat, ''), '-')");
            DB::statement("UPDATE siswa SET email = CONCAT('siswa-', id, '@nuist.local') WHERE email IS NULL OR email = ''");
            DB::statement("UPDATE siswa SET password = CONCAT('legacy-', id) WHERE password IS NULL OR password = ''");
            DB::statement('ALTER TABLE siswa MODIFY nis VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY nama_lengkap VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY kelas VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY nama_madrasah VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY alamat TEXT NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY email VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY password VARCHAR(255) NOT NULL');
        }

        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->unique('email');
            });
        } catch (\Throwable) {
        }

        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->unique('nis');
            });
        } catch (\Throwable) {
        }

        Schema::table('siswa', function (Blueprint $table) {
            try {
                $table->dropIndex(['madrasah_id', 'scod']);
            } catch (\Throwable) {
            }

            if (Schema::hasColumn('siswa', 'scod')) {
                $table->dropColumn('scod');
            }
        });
    }
};
