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
        Schema::create('simfoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // A. DATA SK
            $table->string('nama_lengkap_gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('kartanu')->nullable();
            $table->string('nipm')->nullable();
            $table->string('nik')->nullable();
            $table->date('tmt')->nullable();
            $table->string('strata_pendidikan')->nullable();
            $table->string('pt_asal')->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->string('program_studi')->nullable();

            // B. RIWAYAT KERJA
            $table->string('status_kerja')->nullable();
            $table->date('tanggal_sk_pertama')->nullable();
            $table->string('nomor_sk_pertama')->nullable();
            $table->string('nomor_sertifikasi_pendidik')->nullable();
            $table->longText('riwayat_kerja_sebelumnya')->nullable();

            // C. KEAHLIAN DAN DATA LAIN
            $table->longText('keahlian')->nullable();
            $table->string('kedudukan_lpm')->nullable();
            $table->longText('prestasi')->nullable();
            $table->string('tahun_sertifikasi_impassing')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->longText('alamat_lengkap')->nullable();

            // D. DATA KEUANGAN/KESEJAHTERAAN
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->decimal('gaji_sertifikasi', 15, 2)->nullable();
            $table->decimal('gaji_pokok', 15, 2)->nullable();
            $table->decimal('honor_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_lain', 15, 2)->nullable();
            $table->decimal('penghasilan_pasangan', 15, 2)->nullable();
            $table->decimal('total_penghasilan', 15, 2)->nullable();
            $table->string('masa_kerja')->nullable();
            $table->string('kategori_penghasilan')->nullable();

            // E. STATUS KEKADERAN
            $table->string('status_kader_diri')->nullable();
            $table->string('pendidikan_kader')->nullable();
            $table->string('status_kader_ayah')->nullable();
            $table->string('status_kader_ibu')->nullable();
            $table->string('status_kader_pasangan')->nullable();
            $table->string('pilihan_status_kader')->nullable();

            // F. DATA KELUARGA
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->integer('jumlah_anak')->nullable();

            // G. PROYEKSI KE DEPAN
            $table->string('proyeksi_1')->nullable(); // Akan kuliah S2
            $table->string('proyeksi_2')->nullable(); // Akan mendaftar PNS
            $table->string('proyeksi_3')->nullable(); // Akan mendaftar PPPK
            $table->string('proyeksi_4')->nullable(); // Akan mengikuti PPG
            $table->string('proyeksi_5')->nullable(); // Akan menulis buku/modul/riset
            $table->string('proyeksi_6')->nullable(); // Akan mengikuti Seleksi Diklat Cakep
            $table->string('proyeksi_7')->nullable(); // Akan membimbing riset & prestasi siswa
            $table->string('proyeksi_8')->nullable(); // Akan masuk tim unggulan sekolah/madrasah
            $table->string('proyeksi_9')->nullable(); // Akan kompetisi Pimpinan Level II
            $table->string('proyeksi_10')->nullable(); // Akan aktif mengikuti pelatihan-pelatihan
            $table->string('proyeksi_11')->nullable(); // Akan aktif di MGMP dan MKKSM
            $table->string('proyeksi_12')->nullable(); // Akan mengikuti Pendidikan Kader NU
            $table->string('proyeksi_13')->nullable(); // Akan aktif membantu kegiatan lembaga
            $table->string('proyeksi_14')->nullable(); // Akan aktif mengikuti kegiatan ke-NU-an
            $table->string('proyeksi_15')->nullable(); // Akan aktif ikut ZIS & kegiatan sosial
            $table->string('proyeksi_16')->nullable(); // Akan mengembangkan unit usaha satpen
            $table->string('proyeksi_17')->nullable(); // Akan bekerja dengan disiplin & produktif
            $table->string('proyeksi_18')->nullable(); // Akan loyal pada NU & aktif di masyarakat
            $table->string('proyeksi_19')->nullable(); // Bersedia dipindah ke satpen lain

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simfoni');
    }
};
