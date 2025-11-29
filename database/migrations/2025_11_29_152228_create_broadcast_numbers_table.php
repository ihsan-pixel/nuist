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
        Schema::create('broadcast_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id');
            $table->string('whatsapp_number');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('cascade');
            $table->unique(['madrasah_id', 'whatsapp_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_numbers');
    }
};
