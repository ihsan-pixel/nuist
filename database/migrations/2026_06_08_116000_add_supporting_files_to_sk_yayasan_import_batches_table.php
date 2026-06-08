<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_import_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('sk_yayasan_import_batches', 'fakta_integritas_filename')) {
                $table->string('fakta_integritas_filename')->nullable()->after('stored_path');
            }

            if (!Schema::hasColumn('sk_yayasan_import_batches', 'fakta_integritas_path')) {
                $table->string('fakta_integritas_path')->nullable()->after('fakta_integritas_filename');
            }

            if (!Schema::hasColumn('sk_yayasan_import_batches', 'penilaian_perilaku_filename')) {
                $table->string('penilaian_perilaku_filename')->nullable()->after('fakta_integritas_path');
            }

            if (!Schema::hasColumn('sk_yayasan_import_batches', 'penilaian_perilaku_path')) {
                $table->string('penilaian_perilaku_path')->nullable()->after('penilaian_perilaku_filename');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_import_batches', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'fakta_integritas_filename',
                'fakta_integritas_path',
                'penilaian_perilaku_filename',
                'penilaian_perilaku_path',
            ] as $column) {
                if (Schema::hasColumn('sk_yayasan_import_batches', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
