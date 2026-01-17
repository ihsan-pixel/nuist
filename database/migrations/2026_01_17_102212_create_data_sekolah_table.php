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
        Schema::create('data_sekolah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->onDelete('cascade');
            $table->year('tahun');
            $table->integer('jumlah_siswa')->default(0);
            $table->integer('jumlah_pns_sertifikasi')->default(0);
            $table->integer('jumlah_pns_non_sertifikasi')->default(0);
            $table->integer('jumlah_gty_sertifikasi')->default(0);
            $table->integer('jumlah_gty_sertifikasi_inpassing')->default(0);
            $table->integer('jumlah_gty_non_sertifikasi')->default(0);
            $table->integer('jumlah_gtt')->default(0);
            $table->integer('jumlah_pty')->default(0);
            $table->integer('jumlah_ptt')->default(0);
            $table->timestamps();

            // Index untuk performa
            $table->unique(['madrasah_id', 'tahun']);
            $table->index(['tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sekolah');
    }
};
