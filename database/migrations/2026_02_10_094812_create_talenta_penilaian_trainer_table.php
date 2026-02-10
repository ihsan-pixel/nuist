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
        Schema::create('talenta_penilaian_trainer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talenta_pemateri_id')->constrained('talenta_pemateri')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // who is rating
            $table->tinyInteger('kualitas_materi')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('penyampaian')->unsigned()->nullable();
            $table->tinyInteger('integrasi_kasus')->unsigned()->nullable();
            $table->tinyInteger('penjelasan')->unsigned()->nullable();
            $table->tinyInteger('umpan_balik')->unsigned()->nullable();
            $table->tinyInteger('waktu')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['talenta_pemateri_id', 'user_id'], 'talenta_penilaian_trainer_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_penilaian_trainer');
    }
};
