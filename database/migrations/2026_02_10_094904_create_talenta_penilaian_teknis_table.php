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
        Schema::create('talenta_penilaian_teknis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // who is rating
            $table->tinyInteger('kehadiran')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('partisipasi')->unsigned()->nullable();
            $table->tinyInteger('disiplin')->unsigned()->nullable();
            $table->tinyInteger('kualitas_tugas')->unsigned()->nullable();
            $table->tinyInteger('pemahaman_materi')->unsigned()->nullable();
            $table->tinyInteger('implementasi_praktik')->unsigned()->nullable();
            $table->timestamps();

            $table->unique('user_id'); // one rating per user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_penilaian_teknis');
    }
};
