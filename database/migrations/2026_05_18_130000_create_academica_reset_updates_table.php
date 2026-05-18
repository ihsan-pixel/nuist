<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academica_reset_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academica_proposal_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('title');
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->text('progress_note')->nullable();
            $table->timestamps();

            $table->foreign('academica_proposal_id')
                ->references('id')
                ->on('academica_proposals')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academica_reset_updates');
    }
};
