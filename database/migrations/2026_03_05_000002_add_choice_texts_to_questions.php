<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'choice_texts')) {
                $table->json('choice_texts')->nullable()->after('choice_scores');
            }
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'choice_texts')) {
                $table->dropColumn('choice_texts');
            }
        });
    }
};
