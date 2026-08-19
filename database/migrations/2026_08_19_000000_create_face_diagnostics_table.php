<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 32);
            $table->string('outcome', 32);
            $table->string('stage', 64)->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('device', 191)->nullable();
            $table->string('android_version', 32)->nullable();
            $table->string('browser', 128)->nullable();
            $table->string('gpu', 191)->nullable();
            $table->string('webgl', 32)->nullable();
            $table->string('tf_backend', 32)->nullable();
            $table->string('video_size', 32)->nullable();
            $table->string('ready_state', 16)->nullable();
            $table->string('camera_state', 16)->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['source', 'outcome', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_diagnostics');
    }
};
