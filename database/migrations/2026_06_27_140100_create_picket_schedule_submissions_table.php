<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('picket_schedule_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picket_schedule_period_id')->constrained('picket_schedule_periods')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('selected_dates');
            $table->string('approval_status')->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();

            $table->unique(['picket_schedule_period_id', 'user_id'], 'picket_schedule_submissions_period_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('picket_schedule_submissions');
    }
};
