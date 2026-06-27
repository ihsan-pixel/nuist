<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('app_settings', 'sk_yayasan_school_year')) {
                $table->string('sk_yayasan_school_year', 50)->nullable()->after('bni_va_callback_token');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_number_start')) {
                $table->unsignedInteger('sk_yayasan_number_start')->nullable()->after('sk_yayasan_school_year');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_signer_name')) {
                $table->string('sk_yayasan_signer_name')->nullable()->after('sk_yayasan_number_start');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_signer_position')) {
                $table->string('sk_yayasan_signer_position')->nullable()->after('sk_yayasan_signer_name');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_established_at')) {
                $table->string('sk_yayasan_established_at')->nullable()->after('sk_yayasan_signer_position');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_issued_date')) {
                $table->date('sk_yayasan_issued_date')->nullable()->after('sk_yayasan_established_at');
            }

            if (!Schema::hasColumn('app_settings', 'sk_yayasan_number_format_suffix')) {
                $table->string('sk_yayasan_number_format_suffix')->nullable()->after('sk_yayasan_issued_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $columns = [
                'sk_yayasan_school_year',
                'sk_yayasan_number_start',
                'sk_yayasan_signer_name',
                'sk_yayasan_signer_position',
                'sk_yayasan_established_at',
                'sk_yayasan_issued_date',
                'sk_yayasan_number_format_suffix',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('app_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
