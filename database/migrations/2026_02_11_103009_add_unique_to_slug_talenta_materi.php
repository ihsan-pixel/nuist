<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('slug_talenta_materi', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_materi', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropUnique(['slug']);
            $table->string('slug')->nullable()->change();
        });
    }
};
