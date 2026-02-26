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
        Schema::table('talenta_penilaian_teknis', function (Blueprint $table) {
            // Add nullable foreign key to link teknis penilaian to layanan teknis
            if (!Schema::hasColumn('talenta_penilaian_teknis', 'talenta_layanan_teknis_id')) {
                $table->foreignId('talenta_layanan_teknis_id')->nullable()->constrained('talenta_layanan_teknis')->onDelete('cascade')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_penilaian_teknis', function (Blueprint $table) {
            if (Schema::hasColumn('talenta_penilaian_teknis', 'talenta_layanan_teknis_id')) {
                $table->dropConstrainedForeignId('talenta_layanan_teknis_id');
            }
        });
    }
};
