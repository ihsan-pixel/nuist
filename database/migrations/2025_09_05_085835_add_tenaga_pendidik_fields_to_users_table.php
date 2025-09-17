<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable();
            }
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable();
            }
            if (!Schema::hasColumn('users', 'kartanu')) {
                $table->string('kartanu')->nullable();
            }
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable();
            }
            if (!Schema::hasColumn('users', 'nuptk')) {
                $table->string('nuptk')->nullable();
            }
            if (!Schema::hasColumn('users', 'npk')) {
                $table->string('npk')->nullable();
            }
            if (!Schema::hasColumn('users', 'madrasah_id')) {
                $table->foreignId('madrasah_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'pendidikan_terakhir')) {
                $table->string('pendidikan_terakhir')->nullable();
            }
            if (!Schema::hasColumn('users', 'tahun_lulus')) {
                $table->integer('tahun_lulus')->nullable();
            }
            if (!Schema::hasColumn('users', 'program_studi')) {
                $table->string('program_studi')->nullable();
            }
            if (!Schema::hasColumn('users', 'status_kepegawaian')) {
                $table->string('status_kepegawaian', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'tmt')) {
                $table->date('tmt')->nullable();
            }
            if (!Schema::hasColumn('users', 'ketugasan_id')) {
                $table->unsignedBigInteger('ketugasan_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir','tanggal_lahir','no_hp','kartanu','nip','nuptk','npk','madrasah_id',
                'pendidikan_terakhir','tahun_lulus','program_studi','status_kepegawaian','tmt',
                'ketugasan_id','avatar','alamat'
            ]);
        });
    }
};
