<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spp_siswa_bills', function (Blueprint $table) {
            if (!Schema::hasColumn('spp_siswa_bills', 'jenis_tagihan')) {
                $table->string('jenis_tagihan', 100)->default('SPP')->after('setting_id');
                $table->index(['madrasah_id', 'jenis_tagihan'], 'spp_siswa_bills_madrasah_jenis_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spp_siswa_bills', function (Blueprint $table) {
            if (Schema::hasColumn('spp_siswa_bills', 'jenis_tagihan')) {
                $table->dropIndex('spp_siswa_bills_madrasah_jenis_idx');
                $table->dropColumn('jenis_tagihan');
            }
        });
    }
};
