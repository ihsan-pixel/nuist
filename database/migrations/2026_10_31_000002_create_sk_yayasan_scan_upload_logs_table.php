<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sk_yayasan_scan_upload_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('document_id')->nullable()->constrained('sk_yayasan_documents')->nullOnDelete();
            $table->string('original_name');
            $table->string('document_number')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('status', 20);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_yayasan_scan_upload_logs');
    }
};
