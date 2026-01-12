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
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            $table->string('nama_satpen')->nullable()->after('tahun_pelaporan');
            $table->text('alamat')->nullable()->after('nama_satpen');
            $table->string('nama_kepala_sekolah_madrasah')->nullable()->after('alamat');
            $table->string('gelar')->nullable()->after('nama_kepala_sekolah_madrasah');
            $table->date('tmt_ks_kamad_pertama')->nullable()->after('gelar');
            $table->date('tmt_ks_kamad_terakhir')->nullable()->after('tmt_ks_kamad_pertama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'nama_satpen',
                'alamat',
                'nama_kepala_sekolah_madrasah',
                'gelar',
                'tmt_ks_kamad_pertama',
                'tmt_ks_kamad_terakhir'
            ]);
        });
    }
};
