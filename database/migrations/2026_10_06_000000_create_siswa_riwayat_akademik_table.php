<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_riwayat_akademik', function (Blueprint $table) {
            $table->id();
            // The legacy siswa table has installations where `id` is not backed by
            // a MySQL-compatible FK index. Keep indexed application-level relations
            // so this additive migration works without altering the source tables.
            $table->unsignedBigInteger('siswa_id')->index();
            $table->unsignedBigInteger('madrasah_id')->index();
            $table->string('tahun_ajaran', 9);
            $table->unsignedTinyInteger('semester')->default(1);
            $table->string('jenjang_sekolah', 10)->nullable();
            $table->unsignedTinyInteger('tingkat')->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('status', 30)->default('aktif');
            $table->boolean('is_current')->default(true);
            $table->string('sumber_data', 30)->default('migrasi_kelas_awal');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->unique(
                ['siswa_id', 'tahun_ajaran', 'semester'],
                'siswa_riwayat_akademik_student_period_unique'
            );
            $table->index(
                ['madrasah_id', 'tahun_ajaran', 'semester', 'is_current'],
                'siswa_riwayat_akademik_scope_index'
            );
            $table->index(['jenjang_sekolah', 'tingkat', 'status'], 'siswa_riwayat_akademik_level_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_riwayat_akademik');
    }
};
