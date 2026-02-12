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
        Schema::create('tugas_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_talenta_level1_id')->constrained('tugas_talenta_level1s')->onDelete('cascade');
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_nilai');
    }
};
