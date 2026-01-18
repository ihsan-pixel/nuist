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
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->json('response_midtrans')->nullable();
            $table->string('pdf_url')->nullable();
            $table->unsignedBigInteger('tagihan_id')->nullable();
            $table->json('payment_data')->nullable(); // Untuk menyimpan data dari Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('cascade');
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');
            $table->index(['madrasah_id', 'tahun_anggaran']);
            $table->index('order_id');
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
