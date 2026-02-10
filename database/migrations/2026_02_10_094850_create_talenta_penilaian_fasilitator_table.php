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
        Schema::create('talenta_penilaian_fasilitator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talenta_fasilitator_id')->constrained('talenta_fasilitator')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // who is rating
            $table->tinyInteger('fasilitasi')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('pendampingan')->unsigned()->nullable();
            $table->tinyInteger('respons')->unsigned()->nullable();
            $table->tinyInteger('koordinasi')->unsigned()->nullable();
            $table->tinyInteger('monitoring')->unsigned()->nullable();
            $table->tinyInteger('waktu')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['talenta_fasilitator_id', 'user_id'], 'talenta_penilaian_fasilitator_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_penilaian_fasilitator');
    }
};
