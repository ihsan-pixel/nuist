<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sk_yayasan_requests')) {
            Schema::table('sk_yayasan_requests', function (Blueprint $table) {
                $columnsToDrop = [];

                if (Schema::hasColumn('sk_yayasan_requests', 'effective_start_date')) {
                    $columnsToDrop[] = 'effective_start_date';
                }

                if (Schema::hasColumn('sk_yayasan_requests', 'effective_end_date')) {
                    $columnsToDrop[] = 'effective_end_date';
                }

                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sk_yayasan_requests')) {
            Schema::table('sk_yayasan_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('sk_yayasan_requests', 'effective_start_date')) {
                    $table->date('effective_start_date')->nullable()->after('employment_category');
                }

                if (!Schema::hasColumn('sk_yayasan_requests', 'effective_end_date')) {
                    $table->date('effective_end_date')->nullable()->after('effective_start_date');
                }
            });
        }
    }
};
