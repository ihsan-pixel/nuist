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
        Schema::create('talenta_pemateri_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talenta_pemateri_id')
                  ->constrained('talenta_pemateri')
                  ->onDelete('cascade');

            $table->foreignId('talenta_materi_id')
                  ->constrained('talenta_materi')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_pemateri_materi');
    }
};
