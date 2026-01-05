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
        Schema::create('laporan_akhir_tahun_kepala_sekolah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->year('tahun_pelaporan');
            $table->string('nama_kepala_sekolah');
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nama_madrasah');
            $table->text('alamat_madrasah');
            $table->integer('jumlah_guru');
            $table->integer('jumlah_siswa');
            $table->integer('jumlah_kelas');
            $table->text('prestasi_madrasah');
            $table->text('kendala_utama');
            $table->text('program_kerja_tahun_depan');
            $table->decimal('anggaran_digunakan', 15, 2)->nullable();
            $table->text('saran_dan_masukan')->nullable();
            $table->date('tanggal_laporan');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'tahun_pelaporan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir_tahun_kepala_sekolah');
    }
};
