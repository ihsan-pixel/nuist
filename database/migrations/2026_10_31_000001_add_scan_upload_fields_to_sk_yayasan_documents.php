<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            $table->string('scan_pdf_path')->nullable()->after('rendered_content');
            $table->string('scan_pdf_disk')->nullable()->after('scan_pdf_path');
            $table->string('scan_pdf_original_name')->nullable()->after('scan_pdf_disk');
            $table->timestamp('scan_pdf_uploaded_at')->nullable()->after('scan_pdf_original_name');
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            $table->dropColumn(['scan_pdf_path', 'scan_pdf_disk', 'scan_pdf_original_name', 'scan_pdf_uploaded_at']);
        });
    }
};
