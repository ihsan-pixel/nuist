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
        Schema::create('data_sk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data SK
            $table->string('nama_lengkap_gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('kartanu')->nullable();
            $table->string('nipm')->nullable(); // NIP Ma'arif Baru
            $table->string('nik')->nullable(); // Nomor Induk Kependudukan
            $table->date('tmt_pertama')->nullable(); // TMT Pertama
            $table->string('masa_kerja')->nullable(); // Durasi
            $table->string('strata_pendidikan')->nullable();
            $table->string('pt_asal')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('program_studi')->nullable();
            
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sk');
    }
};
