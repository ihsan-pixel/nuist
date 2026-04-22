<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `izins` MODIFY `type` VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `izins` MODIFY `type` ENUM('sakit', 'tidak_masuk', 'terlambat', 'tugas_luar', 'cuti', 'mengajar_sekolah_lain') NOT NULL");
    }
};
