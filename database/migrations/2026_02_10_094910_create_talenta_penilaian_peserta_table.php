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
        Schema::create('talenta_penilaian_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talenta_peserta_id')->constrained('talenta_peserta')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // who is rating
            $table->tinyInteger('kehadiran')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('partisipasi')->unsigned()->nullable();
            $table->tinyInteger('disiplin')->unsigned()->nullable();
            $table->tinyInteger('tugas')->unsigned()->nullable();
            $table->tinyInteger('pemahaman')->unsigned()->nullable();
            $table->tinyInteger('praktik')->unsigned()->nullable();
            $table->tinyInteger('sikap')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['talenta_peserta_id', 'user_id'], 'talenta_penilaian_peserta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_penilaian_peserta');
    }
};
