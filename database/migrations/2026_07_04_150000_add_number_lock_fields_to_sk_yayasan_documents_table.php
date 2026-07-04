<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            $table->foreignId('number_locked_by')
                ->nullable()
                ->after('published_by')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('number_locked_at')->nullable()->after('published_at');
            $table->index('number_locked_at');
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_documents', function (Blueprint $table) {
            $table->dropForeign(['number_locked_by']);
            $table->dropColumn('number_locked_by');
            $table->dropIndex(['number_locked_at']);
            $table->dropColumn('number_locked_at');
        });
    }
};
