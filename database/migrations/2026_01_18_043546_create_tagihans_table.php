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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id');
            $table->integer('tahun_anggaran');
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['belum_lunas', 'pending', 'lunas'])->default('belum_lunas');
            $table->date('jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('tagihans');
    }
};
