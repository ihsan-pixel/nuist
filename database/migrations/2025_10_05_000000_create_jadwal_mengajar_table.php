<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalMengajarTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_mengajar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenaga_pendidik_id');
            $table->unsignedBigInteger('madrasah_id');
            $table->string('hari', 20);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('mata_pelajaran');
            $table->timestamps();

            $table->foreign('tenaga_pendidik_id')->references('id')->on('tenaga_pendidiks')->onDelete('cascade');
            $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mengajar');
    }
}
