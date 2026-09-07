<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gtk_pendataan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nik', 32)->nullable();
            $table->string('gol_darah', 5)->nullable();
            $table->string('email_aktif')->nullable();
            $table->date('tmt_sk_pertama')->nullable();
            $table->date('tmt_sk_terakhir')->nullable();
            $table->string('nomor_sk_pertama', 100)->nullable();
            $table->unsignedSmallInteger('tahun_sk_pertama')->nullable();
            $table->decimal('gaji_satpen', 15, 2)->nullable();
            $table->string('nomor_sertifikasi_pendidik', 100)->nullable();
            $table->decimal('gaji_sertifikasi', 15, 2)->nullable();
            $table->decimal('tunjangan_rerata_bulanan', 15, 2)->nullable();
            $table->string('nama_mgmp')->nullable();
            $table->text('produk_kerja_kolaboratif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gtk_pendataan');
    }
};
