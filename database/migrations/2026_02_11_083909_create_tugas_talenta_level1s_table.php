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
        Schema::create('tugas_talenta_level1s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('area'); // ideologi_organisasi, tata_kelola, layanan_pendidikan, kepemimpinan
            $table->string('jenis_tugas'); // on_site, terstruktur, kelompok
            $table->json('data'); // Store form data as JSON
            $table->string('file_path')->nullable(); // For uploaded files
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'area', 'jenis_tugas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_talenta_level1s');
    }
};
