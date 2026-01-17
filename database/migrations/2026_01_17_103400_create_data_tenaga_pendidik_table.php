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
        Schema::create('data_tenaga_pendidik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->onDelete('cascade');
            $table->year('tahun');
            $table->foreignId('status_kepegawaian_id')->constrained('status_kepegawaian')->onDelete('cascade');
            $table->integer('jumlah')->default(0);
            $table->timestamps();

            // Index untuk performa
            $table->unique(['madrasah_id', 'tahun', 'status_kepegawaian_id']);
            $table->index(['tahun']);
            $table->index(['status_kepegawaian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tenaga_pendidik');
    }
};
