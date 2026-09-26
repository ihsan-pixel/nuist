<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            $table->string('sk_verification_status', 30)->nullable()->after('review_notes');
            $table->text('sk_verification_notes')->nullable()->after('sk_verification_status');
            $table->foreignId('sk_verified_by')->nullable()->after('sk_verification_notes')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('sk_verified_at')->nullable()->after('sk_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            $table->dropForeign(['sk_verified_by']);
            $table->dropColumn([
                'sk_verification_status',
                'sk_verification_notes',
                'sk_verified_by',
                'sk_verified_at',
            ]);
        });
    }
};
