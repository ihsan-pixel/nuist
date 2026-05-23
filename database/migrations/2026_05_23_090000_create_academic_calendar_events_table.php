<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('academic_calendar_events')) {
            Schema::create('academic_calendar_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('madrasahs')->cascadeOnDelete();
                $table->string('name');
                $table->string('event_type', 50);
                $table->string('custom_type_label')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->boolean('is_all_day')->default(true);
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['school_id', 'start_date', 'end_date'], 'academic_calendar_school_date_idx');
                $table->index(['school_id', 'is_active'], 'academic_calendar_school_active_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendar_events');
    }
};
