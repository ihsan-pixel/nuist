<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academica_reset_update_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academica_reset_update_id')->index();
            $table->string('original_name');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->timestamps();

            $table->foreign('academica_reset_update_id')
                ->references('id')
                ->on('academica_reset_updates')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('academica_reset_update_files');
    }
};
