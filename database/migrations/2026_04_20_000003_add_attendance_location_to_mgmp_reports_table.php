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

        Schema::table('mgmp_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('mgmp_reports', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('lokasi');
            }

            if (!Schema::hasColumn('mgmp_reports', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('mgmp_reports', 'radius_meters')) {
                $table->unsignedInteger('radius_meters')->default(100)->after('longitude');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('mgmp_reports')) {
            return;
        }

        Schema::table('mgmp_reports', function (Blueprint $table) {
            foreach (['radius_meters', 'longitude', 'latitude'] as $column) {
                if (Schema::hasColumn('mgmp_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
