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
        Schema::create('teaching_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_schedule_id')->constrained('teaching_schedules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('status', ['hadir', 'alpha'])->default('alpha');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('lokasi')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate attendance for same schedule on same day
            $table->unique(['teaching_schedule_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_attendances');
    }
};
