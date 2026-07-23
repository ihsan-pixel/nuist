<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uppm_payment_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->unsignedInteger('tahun_anggaran');
            $table->string('payment_period', 20);
            $table->date('transfer_date');
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['madrasah_id', 'tahun_anggaran']);
            $table->index(['tahun_anggaran', 'payment_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uppm_payment_updates');
    }
};
