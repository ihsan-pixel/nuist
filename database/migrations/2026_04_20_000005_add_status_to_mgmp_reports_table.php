<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mgmp_reports')) {
            return;
        }

        $statusAfterColumn = Schema::hasColumn('mgmp_reports', 'radius_meters')
            ? 'radius_meters'
            : 'waktu_selesai';

        Schema::table('mgmp_reports', function (Blueprint $table) use ($statusAfterColumn) {
            if (!Schema::hasColumn('mgmp_reports', 'status')) {
                $table->string('status')->default('scheduled')->after($statusAfterColumn);
            }

            if (!Schema::hasColumn('mgmp_reports', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('mgmp_reports')) {
            return;
        }

        Schema::table('mgmp_reports', function (Blueprint $table) {
            foreach (['cancelled_at', 'status'] as $column) {
                if (Schema::hasColumn('mgmp_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
