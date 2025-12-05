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
        Schema::create('riwayat_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Riwayat Kerja
            $table->string('status_kerja_saat_ini')->nullable();
            $table->date('tanggal_sk_pertama')->nullable();
            $table->string('nomor_sk_pertama')->nullable();
            $table->string('nomor_sertifikasi_pendidik')->nullable();
            $table->longText('riwayat_kerja_sebelumnya')->nullable();
            
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kerja');
    }
};
