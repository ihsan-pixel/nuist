<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->string('sk_awal_path')->nullable()->after('keterangan_sk');
            $table->string('sk_akhir_path')->nullable()->after('sk_awal_path');
        });
    }

    public function down(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->dropColumn(['sk_awal_path', 'sk_akhir_path']);
        });
    }
};
