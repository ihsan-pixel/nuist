<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('school_scores', function (Blueprint $table) {
            if (!Schema::hasColumn('school_scores', 'struktur')) {
                $table->integer('struktur')->default(0)->after('school_id');
            }
            if (!Schema::hasColumn('school_scores', 'kompetensi')) {
                $table->integer('kompetensi')->default(0)->after('struktur');
            }
            if (!Schema::hasColumn('school_scores', 'perilaku')) {
                $table->integer('perilaku')->default(0)->after('kompetensi');
            }
            if (!Schema::hasColumn('school_scores', 'keterpaduan')) {
                $table->integer('keterpaduan')->default(0)->after('perilaku');
            }
        });
    }

    public function down()
    {
        Schema::table('school_scores', function (Blueprint $table) {
            if (Schema::hasColumn('school_scores', 'struktur')) {
                $table->dropColumn('struktur');
            }
            if (Schema::hasColumn('school_scores', 'kompetensi')) {
                $table->dropColumn('kompetensi');
            }
            if (Schema::hasColumn('school_scores', 'perilaku')) {
                $table->dropColumn('perilaku');
            }
            if (Schema::hasColumn('school_scores', 'keterpaduan')) {
                $table->dropColumn('keterpaduan');
            }
        });
    }
};
