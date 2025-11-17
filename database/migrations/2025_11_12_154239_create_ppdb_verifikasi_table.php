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
        Schema::create('ppdb_verifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ppdb_pendaftar_id');
            $table->unsignedBigInteger('admin_id');
            $table->text('catatan')->nullable();
            $table->enum('hasil', ['diterima', 'ditolak'])->nullable();
            $table->timestamps();

            $table->foreign('ppdb_pendaftar_id')->references('id')->on('ppdb_pendaftar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_verifikasi');
    }
};
