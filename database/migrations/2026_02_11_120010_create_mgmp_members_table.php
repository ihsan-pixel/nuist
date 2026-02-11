<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mgmp_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('mgmp_group_id')->nullable();
            $table->string('name');
            $table->string('sekolah')->nullable();
            $table->unsignedBigInteger('madrasah_id')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('mgmp_group_id')->references('id')->on('mgmp_groups')->onDelete('set null');
            // note: madrasah table exists as 'madrasah' or 'madrasahs' depending on your migration; adjust if needed
            if (Schema::hasTable('madrasah')) {
                $table->foreign('madrasah_id')->references('id')->on('madrasah')->onDelete('set null');
            } elseif (Schema::hasTable('madrasahs')) {
                $table->foreign('madrasah_id')->references('id')->on('madrasahs')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('mgmp_members');
    }
};
