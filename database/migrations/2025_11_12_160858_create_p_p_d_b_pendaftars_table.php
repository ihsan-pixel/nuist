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
            $table->foreignId('ppdb_setting_id')->constrained('ppdb_settings')->onDelete('cascade');
            $table->string('jalur');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nisn')->unique();
            $table->string('asal_sekolah');
            $table->string('ppdb_nomor_whatsapp_siswa')->nullable();
            $table->string('ppdb_nomor_whatsapp_wali')->nullable();
            $table->string('ppdb_email_siswa')->nullable();
            $table->string('jurusan_pilihan');
            $table->string('berkas_kk');
            $table->string('berkas_ijazah');
            $table->enum('status', ['pending', 'verifikasi', 'lulus', 'tidak_lulus'])->default('pending');
            $table->string('nomor_pendaftaran')->unique();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->integer('ranking')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('diverifikasi_tanggal')->nullable();
            $table->foreignId('diseleksi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('diseleksi_tanggal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_d_b_pendaftars');
    }
};
