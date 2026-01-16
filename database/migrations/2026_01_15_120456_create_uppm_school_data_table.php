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
        Schema::create('uppm_school_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->onDelete('cascade');
            $table->year('tahun_anggaran');
            $table->integer('jumlah_siswa');
            $table->integer('jumlah_guru_tetap');
            $table->integer('jumlah_guru_tidak_tetap');
            $table->integer('jumlah_guru_pns');
            $table->integer('jumlah_guru_pppk');
            $table->integer('jumlah_karyawan_tetap');
            $table->integer('jumlah_karyawan_tidak_tetap');
            $table->decimal('total_nominal', 15, 2);
            $table->enum('status_pembayaran', ['belum_lunas', 'lunas', 'sebagian'])->default('belum_lunas');
            $table->decimal('nominal_dibayar', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['madrasah_id', 'tahun_anggaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uppm_school_data');
    }
};
