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
        Schema::create('talenta_kehadiran_peserta', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('talenta_peserta_id')->constrained('talenta_peserta')->cascadeOnDelete();
            $table->foreignId('materi_id')->constrained('talenta_materi')->cascadeOnDelete();
            $table->enum('status_kehadiran', ['hadir', 'telat', 'izin', 'sakit', 'tidak_hadir', 'lainnya']);
            $table->json('sesi');
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->unique(['tanggal', 'talenta_peserta_id', 'materi_id'], 'talenta_kehadiran_peserta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_kehadiran_peserta');
    }
};
