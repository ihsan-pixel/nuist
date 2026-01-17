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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id');
            $table->integer('tahun_anggaran');
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_pembayaran', ['cash', 'midtrans']);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->json('payment_data')->nullable(); // Untuk menyimpan data dari Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('cascade');
            $table->index(['madrasah_id', 'tahun_anggaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
