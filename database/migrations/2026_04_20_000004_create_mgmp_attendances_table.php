<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mgmp_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mgmp_report_id')->constrained('mgmp_reports')->cascadeOnDelete();
            $table->foreignId('mgmp_group_id')->nullable()->constrained('mgmp_groups')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('attended_at');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedInteger('distance_meters')->nullable();
            $table->string('selfie_path');
            $table->string('lokasi')->nullable();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->text('device_info')->nullable();
            $table->json('location_readings')->nullable();
            $table->timestamps();

            $table->unique(['mgmp_report_id', 'user_id']);
            $table->index(['mgmp_group_id', 'attended_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mgmp_attendances');
    }
};
