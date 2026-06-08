<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('sk_yayasan_requests', 'import_batch_id')) {
                $table->foreignId('import_batch_id')
                    ->nullable()
                    ->after('madrasah_id')
                    ->constrained('sk_yayasan_import_batches')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            if (Schema::hasColumn('sk_yayasan_requests', 'import_batch_id')) {
                $table->dropConstrainedForeignId('import_batch_id');
            }
        });
    }
};
