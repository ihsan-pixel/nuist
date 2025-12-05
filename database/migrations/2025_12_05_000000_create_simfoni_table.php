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
        Schema::create('simfoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // A. DATA SK
            $table->string('nama_lengkap_gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('kartanu')->nullable();
            $table->string('nipm')->nullable();
            $table->string('nik')->nullable();
            $table->date('tmt')->nullable();
            $table->string('strata_pendidikan')->nullable();
            $table->string('pt_asal')->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->string('program_studi')->nullable();
            
            // B. RIWAYAT KERJA
            $table->string('status_kerja')->nullable();
            $table->date('tanggal_sk_pertama')->nullable();
            $table->string('nomor_sk_pertama')->nullable();
            $table->string('nomor_sertifikasi_pendidik')->nullable();
            $table->longText('riwayat_kerja_sebelumnya')->nullable();
            
            // C. KEAHLIAN DAN DATA LAIN
            $table->longText('keahlian')->nullable();
            $table->string('kedudukan_lpm')->nullable();
            $table->longText('prestasi')->nullable();
            $table->string('tahun_sertifikasi_impassing')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->longText('alamat_lengkap')->nullable();
            
            // D. DATA KEUANGAN/KESEJAHTERAAN
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->decimal('gaji_sertifikasi', 15, 2)->nullable();
            $table->decimal('gaji_pokok', 15, 2)->nullable();
            $table->decimal('honor_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_pasangan', 15, 2)->nullable();
            $table->decimal('total_penghasilan', 15, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simfoni');
    }
};
