<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            $table->dropColumn([
                'va_expired_hours',
                'payment_notes',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            $table->unsignedInteger('va_expired_hours')->default(24)->after('payment_provider');
            $table->text('payment_notes')->nullable()->after('catatan');
        });
    }
};
