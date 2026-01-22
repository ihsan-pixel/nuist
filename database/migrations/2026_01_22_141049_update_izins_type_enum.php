<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIzinsTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('izins', function (Blueprint $table) {
            $table->enum('type', ['sakit', 'tidak_masuk', 'terlambat', 'tugas_luar', 'cuti'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('izins', function (Blueprint $table) {
            $table->enum('type', ['sakit', 'tidak_masuk', 'terlambat', 'tugas_luar'])->change();
        });
    }
}
