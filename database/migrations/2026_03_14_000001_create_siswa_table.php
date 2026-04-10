<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('nis', 50)->unique();
            $table->string('nama_lengkap');
            $table->string('nama_orang_tua_wali');
            $table->string('email')->unique();
            $table->string('email_orang_tua_wali');
            $table->string('no_hp', 25);
            $table->string('no_hp_orang_tua_wali', 25);
            $table->string('kelas', 50);
            $table->string('nama_madrasah');
            $table->text('alamat');
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->index(['madrasah_id', 'kelas']);
            $table->index(['madrasah_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
