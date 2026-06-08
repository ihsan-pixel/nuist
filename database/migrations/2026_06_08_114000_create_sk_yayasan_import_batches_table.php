<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sk_yayasan_import_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending_review');
            $table->string('original_filename');
            $table->string('stored_path')->nullable();
            $table->string('fakta_integritas_filename')->nullable();
            $table->string('fakta_integritas_path')->nullable();
            $table->string('penilaian_perilaku_filename')->nullable();
            $table->string('penilaian_perilaku_path')->nullable();
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('valid_rows')->default(0);
            $table->unsignedInteger('invalid_rows')->default(0);
            $table->boolean('headings_valid')->default(false);
            $table->json('missing_headings')->nullable();
            $table->json('unexpected_headings')->nullable();
            $table->longText('payload_rows')->nullable();
            $table->json('matched_user_ids')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_yayasan_import_batches');
    }
};
