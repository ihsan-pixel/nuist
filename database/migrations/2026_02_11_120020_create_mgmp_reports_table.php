<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mgmp_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mgmp_group_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('judul');
            $table->date('tanggal')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('materi')->nullable();
            $table->text('hasil')->nullable();
            $table->json('dokumentasi')->nullable();
            $table->json('peserta')->nullable();
            $table->unsignedInteger('jumlah_peserta')->nullable();
            $table->timestamps();

            $table->foreign('mgmp_group_id')->references('id')->on('mgmp_groups')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mgmp_reports');
    }
};
