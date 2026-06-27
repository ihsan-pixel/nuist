<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('sk_yayasan_documents', 'meta_payload')) {
                $table->json('meta_payload')->nullable()->after('publication_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            if (Schema::hasColumn('sk_yayasan_documents', 'meta_payload')) {
                $table->dropColumn('meta_payload');
            }
        });
    }
};
