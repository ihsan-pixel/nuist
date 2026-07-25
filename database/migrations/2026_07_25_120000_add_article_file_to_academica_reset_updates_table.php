<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('academica_reset_updates', function (Blueprint $table) {
            $table->string('article_filename')->nullable()->after('progress_note');
            $table->string('article_path')->nullable()->after('article_filename');
            $table->string('article_mime')->nullable()->after('article_path');
        });
    }

    public function down()
    {
        Schema::table('academica_reset_updates', function (Blueprint $table) {
            $table->dropColumn(['article_filename', 'article_path', 'article_mime']);
        });
    }
};
