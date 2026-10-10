<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bpppmnu_event_qr_tokens', function (Blueprint $table) {
            $table->text('raw_token')->nullable()->after('token_hash');
            $table->dateTime('claimed_at')->nullable()->after('expires_at');
            $table->index(['event_id', 'claimed_at', 'revoked_at'], 'bpppmnu_qr_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('bpppmnu_event_qr_tokens', function (Blueprint $table) {
            $table->dropIndex('bpppmnu_qr_active_index');
            $table->dropColumn(['raw_token', 'claimed_at']);
        });
    }
};
