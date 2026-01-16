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
        Schema::create('uppm_settings', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_anggaran');
            $table->decimal('nominal_siswa', 15, 2); // per bulan
            $table->decimal('nominal_guru_tetap', 15, 2);
            $table->decimal('nominal_guru_tidak_tetap', 15, 2);
            $table->decimal('nominal_guru_pns', 15, 2);
            $table->decimal('nominal_guru_pppk', 15, 2);
            $table->decimal('nominal_karyawan_tetap', 15, 2);
            $table->decimal('nominal_karyawan_tidak_tetap', 15, 2);
            $table->date('jatuh_tempo');
            $table->enum('skema_pembayaran', ['lunas', 'cicilan']);
            $table->boolean('aktif')->default(false);
            $table->text('catatan')->nullable();
            $table->string('logo')->nullable();
            $table->string('format_invoice')->default('UPPM-{tahun}-{no}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uppm_settings');
    }
};
