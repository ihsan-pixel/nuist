<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('enrollment_uuid')->index();
            $table->string('engine', 64)->index();
            $table->string('model', 64)->index();
            $table->string('model_version', 32)->nullable();
            $table->unsignedSmallInteger('dimension')->nullable();
            $table->json('embedding')->nullable();
            $table->json('samples')->nullable();
            $table->decimal('quality_score', 5, 4)->nullable();
            $table->decimal('liveness_score', 5, 4)->nullable();
            $table->string('source', 32)->index();
            $table->string('status', 32)->default('active')->index();
            $table->json('metadata')->nullable();
            $table->timestamp('enrolled_at')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'engine', 'model', 'status'], 'biometric_profiles_user_engine_model_status_idx');
            $table->index(['user_id', 'status'], 'biometric_profiles_user_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_profiles');
    }
};
