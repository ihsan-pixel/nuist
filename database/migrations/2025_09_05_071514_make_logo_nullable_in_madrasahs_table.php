<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('madrasahs', function (Blueprint $table) {
            $table->string('logo')->nullable(false)->change();
        });
    }
};
