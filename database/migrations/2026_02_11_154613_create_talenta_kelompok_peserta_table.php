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
        Schema::create('talenta_kelompok_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talenta_kelompok_id')->constrained('talenta_kelompoks')->onDelete('cascade');
            $table->foreignId('talenta_peserta_id')->constrained('talenta_peserta')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_kelompok_peserta');
    }
};
