<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE siswa MODIFY nis VARCHAR(50) NULL');
        DB::statement('ALTER TABLE siswa MODIFY nama_lengkap VARCHAR(255) NULL');
        DB::statement('ALTER TABLE siswa MODIFY kelas VARCHAR(50) NULL');
        DB::statement('ALTER TABLE siswa MODIFY nama_madrasah VARCHAR(255) NULL');
        DB::statement('ALTER TABLE siswa MODIFY alamat TEXT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("UPDATE siswa SET nis = COALESCE(NULLIF(nis, ''), CONCAT('NIS-', id))");
        DB::statement("UPDATE siswa SET nama_lengkap = COALESCE(NULLIF(nama_lengkap, ''), CONCAT('SISWA ', id))");
        DB::statement("UPDATE siswa SET kelas = COALESCE(NULLIF(kelas, ''), '-')");
        DB::statement("UPDATE siswa SET nama_madrasah = COALESCE(NULLIF(nama_madrasah, ''), '-')");
        DB::statement("UPDATE siswa SET alamat = COALESCE(NULLIF(alamat, ''), '-')");

        DB::statement('ALTER TABLE siswa MODIFY nis VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE siswa MODIFY nama_lengkap VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE siswa MODIFY kelas VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE siswa MODIFY nama_madrasah VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE siswa MODIFY alamat TEXT NOT NULL');
    }
};
