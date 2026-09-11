<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_bpppmnu_member')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_bpppmnu_member')->default(false)->after('instansi_asal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_bpppmnu_member')) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_bpppmnu_member'));
        }
    }
};
