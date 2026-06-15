<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            $table->string('submission_letter_number')->nullable()->after('request_number');
            $table->date('submission_letter_date')->nullable()->after('submission_letter_number');
        });
    }

    public function down(): void
    {
        Schema::table('sk_yayasan_requests', function (Blueprint $table) {
            $table->dropColumn([
                'submission_letter_number',
                'submission_letter_date',
            ]);
        });
    }
};
