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
        Schema::create('ppdb_pendaftar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ppdb_setting_id');
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('jurusan_pilihan')->nullable();
            $table->string('berkas_kk')->nullable();
            $table->string('berkas_ijazah')->nullable();
            $table->enum('status', ['pending','verifikasi','lulus','tidak_lulus'])->default('pending');
            $table->timestamps();

            $table->foreign('ppdb_setting_id')->references('id')->on('ppdb_settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendaftar');
    }
};
