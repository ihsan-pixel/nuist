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
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('surat_izin_path')->nullable()->after('keterangan');
            $table->enum('status_izin', ['pending', 'approved', 'rejected'])->nullable()->after('surat_izin_path');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status_izin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['surat_izin_path', 'status_izin', 'approved_by']);
        });
    }
};
