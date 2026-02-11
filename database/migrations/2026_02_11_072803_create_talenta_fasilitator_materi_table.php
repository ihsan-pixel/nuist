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
        Schema::create('talenta_fasilitator_materi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talenta_fasilitator_id');
            $table->unsignedBigInteger('talenta_materi_id');
            $table->timestamps();

            $table->foreign('talenta_fasilitator_id')->references('id')->on('talenta_fasilitator')->onDelete('cascade');
            $table->foreign('talenta_materi_id')->references('id')->on('talenta_materi')->onDelete('cascade');

            $table->unique(['talenta_fasilitator_id', 'talenta_materi_id'], 'fasilitator_materi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_fasilitator_materi');
    }
};
