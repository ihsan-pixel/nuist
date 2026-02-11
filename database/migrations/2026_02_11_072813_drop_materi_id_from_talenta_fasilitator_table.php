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
        Schema::table('talenta_fasilitator', function (Blueprint $table) {
            $table->dropForeign(['materi_id']);
            $table->dropColumn('materi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_fasilitator', function (Blueprint $table) {
            $table->unsignedBigInteger('materi_id')->nullable();
            $table->foreign('materi_id')->references('id')->on('talenta_materi');
        });
    }
};
