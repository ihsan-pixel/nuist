<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->index(['tanggal', 'user_id'], 'presensis_tanggal_user_idx');
        });
        Schema::table('spp_siswa_bills', function (Blueprint $table) {
            $table->index('status', 'spp_siswa_bills_status_idx');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_active'], 'users_role_active_idx');
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->index('is_active', 'siswa_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('presensis', fn (Blueprint $table) => $table->dropIndex('presensis_tanggal_user_idx'));
        Schema::table('spp_siswa_bills', fn (Blueprint $table) => $table->dropIndex('spp_siswa_bills_status_idx'));
        Schema::table('users', fn (Blueprint $table) => $table->dropIndex('users_role_active_idx'));
        Schema::table('siswa', fn (Blueprint $table) => $table->dropIndex('siswa_active_idx'));
    }
};
