<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'bpppmnu_jabatan')) $table->string('bpppmnu_jabatan')->nullable()->after('is_bpppmnu_member');
            if (! Schema::hasColumn('users', 'bpppmnu_instansi_asal')) $table->string('bpppmnu_instansi_asal')->nullable()->after('bpppmnu_jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bpppmnu_instansi_asal')) $table->dropColumn('bpppmnu_instansi_asal');
            if (Schema::hasColumn('users', 'bpppmnu_jabatan')) $table->dropColumn('bpppmnu_jabatan');
        });
    }
};
