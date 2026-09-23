<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->string('foto_bebas_path')->nullable()->after('ktp_path');
        });
    }

    public function down(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->dropColumn('foto_bebas_path');
        });
    }
};
