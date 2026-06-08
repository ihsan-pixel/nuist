<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sk_yayasan_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('umum');
            $table->string('document_title');
            $table->string('document_number_format')->nullable();
            $table->text('description')->nullable();
            $table->longText('body');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sk_yayasan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('sk_yayasan_templates')->nullOnDelete();
            $table->string('request_number')->unique();
            $table->string('request_type')->default('perpanjangan');
            $table->string('employment_category')->nullable();
            $table->date('effective_start_date');
            $table->date('effective_end_date');
            $table->string('current_status')->default('submitted');
            $table->text('submission_notes')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sk_yayasan_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->unique()->constrained('sk_yayasan_requests')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('sk_yayasan_templates')->nullOnDelete();
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('document_number')->unique();
            $table->date('issued_date');
            $table->string('signer_name');
            $table->string('signer_position')->nullable();
            $table->text('publication_notes')->nullable();
            $table->longText('rendered_content');
            $table->string('status')->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_yayasan_documents');
        Schema::dropIfExists('sk_yayasan_requests');
        Schema::dropIfExists('sk_yayasan_templates');
    }
};
