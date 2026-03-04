<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('school_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('madrasah')->nullOnDelete();
            $table->integer('layanan')->default(0);
            $table->integer('tata_kelola')->default(0);
            $table->integer('jumlah_siswa')->default(0);
            $table->integer('jumlah_penghasilan')->default(0);
            $table->integer('jumlah_prestasi')->default(0);
            $table->integer('jumlah_talenta')->default(0);
            $table->integer('total_skor')->default(0);
            $table->string('level_sekolah')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_scores');
    }
};
