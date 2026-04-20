<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dps_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id');
            $table->string('nama');
            $table->string('unsur');
            $table->string('periode', 50);
            $table->timestamps();

            $table->index(['madrasah_id']);

            // Match existing codebase pattern: some installs use 'madrasah' vs 'madrasahs'
            if (Schema::hasTable('madrasah')) {
                $table->foreign('madrasah_id')->references('id')->on('madrasah')->onDelete('cascade');
            } elseif (Schema::hasTable('madrasahs')) {
                $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('dps_members');
    }
};

