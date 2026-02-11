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
        Schema::create('talenta_layanan_teknis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_layanan_teknis')->unique();
            $table->string('nama_layanan_teknis');
            $table->text('tugas_layanan_teknis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talenta_layanan_teknis');
    }
};
