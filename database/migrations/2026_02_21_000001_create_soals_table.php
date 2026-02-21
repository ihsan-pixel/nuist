<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            // materi reference (store slug for simplicity)
            $table->string('materi_slug')->index();
            // jenis: on_site, terstruktur, kelompok
            $table->string('jenis')->index();
            // optional subgroup / kelompok tag
            $table->string('kelompok')->nullable();
            // main question/instruction
            $table->text('pertanyaan');
            // optional extended instruksi
            $table->text('instruksi')->nullable();
            // ordering
            $table->integer('urut')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soals');
    }
};
