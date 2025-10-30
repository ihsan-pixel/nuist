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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('NUIST');
            $table->string('app_version')->default('1.0.0');
            $table->string('banner_image')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('locale')->default('id');
            $table->boolean('debug_mode')->default(false);
            $table->boolean('cache_enabled')->default(true);
            $table->integer('session_lifetime')->default(120);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
