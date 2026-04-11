<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_siswa_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('tahun_ajaran', 20);
            $table->decimal('nominal_spp', 15, 2)->default(0);
            $table->unsignedTinyInteger('tanggal_jatuh_tempo')->default(10);
            $table->decimal('denda_harian', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['madrasah_id', 'tahun_ajaran'], 'spp_siswa_settings_unique');
        });

        Schema::create('spp_siswa_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->foreignId('setting_id')->nullable()->constrained('spp_siswa_settings')->nullOnDelete();
            $table->string('nomor_tagihan')->unique();
            $table->string('periode', 7);
            $table->date('jatuh_tempo');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->decimal('denda', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['madrasah_id', 'status']);
            $table->index(['siswa_id', 'periode']);
        });

        Schema::create('spp_siswa_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('spp_siswa_bills')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal_bayar');
            $table->decimal('nominal_bayar', 15, 2)->default(0);
            $table->string('metode_pembayaran', 100);
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['madrasah_id', 'status_verifikasi']);
            $table->index(['bill_id', 'tanggal_bayar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_siswa_transactions');
        Schema::dropIfExists('spp_siswa_bills');
        Schema::dropIfExists('spp_siswa_settings');
    }
};
