<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // store per-option numeric mappings, e.g. {"A":3,"B":4,"C":5,"D":2,"E":1}
            if (!Schema::hasColumn('questions', 'choice_scores')) {
                $table->json('choice_scores')->nullable()->after('skor_tidak');
            }
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'choice_scores')) {
                $table->dropColumn('choice_scores');
            }
        });
    }
};
