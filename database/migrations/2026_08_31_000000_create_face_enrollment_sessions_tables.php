<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_enrollment_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('madrasahs')->nullOnDelete();
            $table->foreignId('operator_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 32)->default('draft');
            $table->string('active_phase', 32)->nullable();
            $table->json('face_descriptor')->nullable();
            $table->json('face_data')->nullable();
            $table->decimal('quality_score', 6, 4)->nullable();
            $table->decimal('liveness_score', 6, 4)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['school_id', 'status']);
        });

        Schema::create('face_enrollment_captures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('face_enrollment_sessions')->cascadeOnDelete();
            $table->string('phase_key', 32);
            $table->string('phase_label', 64);
            $table->unsignedTinyInteger('capture_index');
            $table->longText('captured_image');
            $table->json('face_descriptor')->nullable();
            $table->decimal('quality_score', 6, 4)->nullable();
            $table->decimal('liveness_score', 6, 4)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'phase_key']);
            $table->index(['session_id', 'capture_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_enrollment_captures');
        Schema::dropIfExists('face_enrollment_sessions');
    }
};
