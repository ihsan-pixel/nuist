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
        Schema::create('ppdb_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sekolah_id')->nullable();
            $table->string('slug')->unique();
            $table->string('nama_sekolah');
            $table->string('tahun');
            $table->enum('status', ['buka', 'tutup'])->default('tutup');
            $table->dateTime('jadwal_buka')->nullable();
            $table->dateTime('jadwal_tutup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_settings');
    }
};
