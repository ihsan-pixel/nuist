<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('school_scores', function (Blueprint $table) {
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete()->after('school_id');
        });
    }

    public function down()
    {
        Schema::table('school_scores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submitted_by');
        });
    }
};
