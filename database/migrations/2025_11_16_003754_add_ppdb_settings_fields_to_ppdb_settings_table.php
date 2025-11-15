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
        Schema::table('ppdb_settings', function (Blueprint $table) {
            // Kuota pendaftar per jurusan (JSON format: {"jurusan1": 50, "jurusan2": 30})
            $table->json('kuota_jurusan')->nullable();

            // Periode presensi (untuk tracking kehadiran siswa)
            $table->dateTime('periode_presensi_mulai')->nullable();
            $table->dateTime('periode_presensi_selesai')->nullable();

            // Pengaturan tambahan
            $table->integer('kuota_total')->default(0); // Total kuota keseluruhan
            $table->boolean('wajib_unggah_foto')->default(false); // Wajib upload foto saat daftar
            $table->boolean('wajib_unggah_ijazah')->default(true); // Wajib upload ijazah
            $table->boolean('wajib_unggah_kk')->default(true); // Wajib upload KK
            $table->text('syarat_tambahan')->nullable(); // Syarat tambahan dalam teks

            // Kontak dan informasi
            $table->string('email_kontak')->nullable();
            $table->string('telepon_kontak')->nullable();
            $table->text('alamat_kontak')->nullable();

            // Pengumuman hasil
            $table->dateTime('jadwal_pengumuman')->nullable();
            $table->text('catatan_pengumuman')->nullable();

            // Informasi sekolah tambahan
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('fasilitas')->nullable();
            $table->text('prestasi')->nullable();
            $table->text('ekstrakurikuler')->nullable();
            $table->text('biaya_pendidikan')->nullable();
            $table->text('informasi_tambahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            $table->dropColumn([
                'kuota_jurusan',
                'periode_presensi_mulai',
                'periode_presensi_selesai',
                'kuota_total',
                'wajib_unggah_foto',
                'wajib_unggah_ijazah',
                'wajib_unggah_kk',
                'syarat_tambahan',
                'email_kontak',
                'telepon_kontak',
                'alamat_kontak',
                'jadwal_pengumuman',
                'catatan_pengumuman',
                'visi',
                'misi',
                'fasilitas',
                'prestasi',
                'ekstrakurikuler',
                'biaya_pendidikan',
                'informasi_tambahan'
            ]);
        });
    }
};
