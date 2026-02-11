<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talenta_pemateri', function (Blueprint $table) {

            // Hapus foreign key dulu
            $table->dropForeign(['materi_id']);

            // Lalu hapus kolomnya
            $table->dropColumn('materi_id');
        });
    }

    public function down(): void
    {
        Schema::table('talenta_pemateri', function (Blueprint $table) {

            // Balikkan jika rollback
            $table->unsignedBigInteger('materi_id');

            $table->foreign('materi_id')
                  ->references('id')
                  ->on('talenta_materi')
                  ->onDelete('cascade');
        });
    }
};

