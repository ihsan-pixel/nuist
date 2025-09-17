<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add columns if they don't exist to avoid duplicate errors
            if (!Schema::hasColumn('users', 'some_column')) {
                // Add your columns here as needed
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns if they exist
        });
    }
};
