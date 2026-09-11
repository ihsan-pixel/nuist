<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'instansi_asal')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('instansi_asal')->nullable()->after('jabatan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'instansi_asal')) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn('instansi_asal'));
        }
    }
};
