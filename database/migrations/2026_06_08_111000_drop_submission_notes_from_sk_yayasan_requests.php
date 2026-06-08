<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sk_yayasan_requests') && Schema::hasColumn('sk_yayasan_requests', 'submission_notes')) {
            Schema::table('sk_yayasan_requests', function (Blueprint $table) {
                $table->dropColumn('submission_notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sk_yayasan_requests') && !Schema::hasColumn('sk_yayasan_requests', 'submission_notes')) {
            Schema::table('sk_yayasan_requests', function (Blueprint $table) {
                $table->text('submission_notes')->nullable()->after('current_status');
            });
        }
    }
};
