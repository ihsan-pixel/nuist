<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_class_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('class_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('activity_type', 50);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['school_id', 'class_name']);
            $table->index(['school_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_class_activities');
    }
};

