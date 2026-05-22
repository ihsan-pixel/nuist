<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nisn', 50)->nullable()->after('nis');
            $table->string('nik', 32)->nullable()->after('nisn');
            $table->string('no_kk', 32)->nullable()->after('nik');
            $table->string('jenis_kelamin', 20)->nullable()->after('nama_lengkap');
            $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('agama', 50)->nullable()->after('tanggal_lahir');
            $table->string('tahun_masuk', 4)->nullable()->after('jurusan');
            $table->string('jenis_tinggal', 100)->nullable()->after('tahun_masuk');
            $table->string('alat_transportasi', 100)->nullable()->after('jenis_tinggal');
            $table->string('dusun', 150)->nullable()->after('alamat');
            $table->string('kelurahan', 150)->nullable()->after('dusun');
            $table->string('kecamatan', 150)->nullable()->after('kelurahan');
            $table->string('kode_pos', 20)->nullable()->after('kecamatan');
            $table->string('nama_ayah')->nullable()->after('kode_pos');
            $table->string('pendidikan_ayah', 100)->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah', 100)->nullable()->after('pendidikan_ayah');
            $table->string('penghasilan_ayah', 100)->nullable()->after('pekerjaan_ayah');
            $table->string('nama_ibu')->nullable()->after('penghasilan_ayah');
            $table->string('pendidikan_ibu', 100)->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu', 100)->nullable()->after('pendidikan_ibu');
            $table->string('penghasilan_ibu', 100)->nullable()->after('pekerjaan_ibu');
            $table->string('nama_wali')->nullable()->after('penghasilan_ibu');
            $table->string('pendidikan_wali', 100)->nullable()->after('nama_wali');
            $table->string('pekerjaan_wali', 100)->nullable()->after('pendidikan_wali');
            $table->string('penghasilan_wali', 100)->nullable()->after('pekerjaan_wali');

            $table->unique('nisn');
            $table->index(['madrasah_id', 'nisn']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE siswa MODIFY nama_orang_tua_wali VARCHAR(255) NULL');
            DB::statement('ALTER TABLE siswa MODIFY email_orang_tua_wali VARCHAR(255) NULL');
            DB::statement('ALTER TABLE siswa MODIFY no_hp VARCHAR(25) NULL');
            DB::statement('ALTER TABLE siswa MODIFY no_hp_orang_tua_wali VARCHAR(25) NULL');
            DB::statement('ALTER TABLE siswa MODIFY jurusan VARCHAR(100) NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE siswa SET nama_orang_tua_wali = COALESCE(NULLIF(nama_orang_tua_wali, ''), '-')");
            DB::statement("UPDATE siswa SET email_orang_tua_wali = COALESCE(NULLIF(email_orang_tua_wali, ''), CONCAT('wali-', id, '@nuist.local'))");
            DB::statement("UPDATE siswa SET no_hp = COALESCE(NULLIF(no_hp, ''), '-')");
            DB::statement("UPDATE siswa SET no_hp_orang_tua_wali = COALESCE(NULLIF(no_hp_orang_tua_wali, ''), '-')");
            DB::statement("UPDATE siswa SET jurusan = COALESCE(NULLIF(jurusan, ''), '-')");

            DB::statement('ALTER TABLE siswa MODIFY nama_orang_tua_wali VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY email_orang_tua_wali VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY no_hp VARCHAR(25) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY no_hp_orang_tua_wali VARCHAR(25) NOT NULL');
            DB::statement('ALTER TABLE siswa MODIFY jurusan VARCHAR(100) NOT NULL');
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex(['madrasah_id', 'nisn']);
            $table->dropUnique(['nisn']);

            $table->dropColumn([
                'nisn',
                'nik',
                'no_kk',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'tahun_masuk',
                'jenis_tinggal',
                'alat_transportasi',
                'dusun',
                'kelurahan',
                'kecamatan',
                'kode_pos',
                'nama_ayah',
                'pendidikan_ayah',
                'pekerjaan_ayah',
                'penghasilan_ayah',
                'nama_ibu',
                'pendidikan_ibu',
                'pekerjaan_ibu',
                'penghasilan_ibu',
                'nama_wali',
                'pendidikan_wali',
                'pekerjaan_wali',
                'penghasilan_wali',
            ]);
        });
    }
};
