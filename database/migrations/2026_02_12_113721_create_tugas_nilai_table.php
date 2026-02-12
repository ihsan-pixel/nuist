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
            $table->unsignedBigInteger('tugas_talenta_level1_id');
            $table->unsignedBigInteger('penilai_id');
            $table->integer('nilai');
            $table->timestamps();

            $table->foreign('tugas_talenta_level1_id')->references('id')->on('tugas_talenta_level1')->onDelete('cascade');
            $table->foreign('penilai_id')->references('id')->on('users')->onDelete('cascade');
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
