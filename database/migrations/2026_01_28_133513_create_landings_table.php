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
        Schema::create('landings', function (Blueprint $table) {
            $table->id();
            $table->string('image_1_hero')->nullable();
            $table->string('title_hero')->nullable();
            $table->string('sub_title_hero')->nullable();
            $table->text('content_hero')->nullable();
            $table->string('image_2_hero')->nullable();
            $table->string('title_profile')->nullable();
            $table->text('content_1_profile')->nullable();
            $table->string('image_1_profile')->nullable();
            $table->string('image_2_profile')->nullable();
            $table->text('content_2_profile')->nullable();
            $table->text('content_3_profile')->nullable();
            $table->string('title_features')->nullable();
            $table->text('content_features')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landings');
    }
};
