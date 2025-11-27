<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the columns to ppdb_settings if they don't exist
        Schema::table('ppdb_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('ppdb_settings', 'tagline')) {
                $table->string('tagline')->nullable()->after('nama_sekolah');
            }
            if (!Schema::hasColumn('ppdb_settings', 'akreditasi')) {
                $table->string('akreditasi')->nullable()->after('tagline');
            }
            if (!Schema::hasColumn('ppdb_settings', 'tahun_berdiri')) {
                $table->integer('tahun_berdiri')->nullable()->after('akreditasi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'telepon')) {
                $table->string('telepon')->nullable()->after('tahun_berdiri');
            }
            if (!Schema::hasColumn('ppdb_settings', 'email')) {
                $table->string('email')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('ppdb_settings', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('ppdb_settings', 'deskripsi_singkat')) {
                $table->text('deskripsi_singkat')->nullable()->after('website');
            }
            if (!Schema::hasColumn('ppdb_settings', 'sejarah')) {
                $table->text('sejarah')->nullable()->after('deskripsi_singkat');
            }
            if (!Schema::hasColumn('ppdb_settings', 'nilai_nilai')) {
                $table->text('nilai_nilai')->nullable()->after('sejarah');
            }
            if (!Schema::hasColumn('ppdb_settings', 'visi')) {
                $table->text('visi')->nullable()->after('nilai_nilai');
            }
            if (!Schema::hasColumn('ppdb_settings', 'misi')) {
                $table->json('misi')->nullable()->after('visi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'fasilitas')) {
                $table->json('fasilitas')->nullable()->after('misi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'keunggulan')) {
                $table->json('keunggulan')->nullable()->after('fasilitas');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jurusan')) {
                $table->json('jurusan')->nullable()->after('keunggulan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'prestasi')) {
                $table->json('prestasi')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'program_unggulan')) {
                $table->json('program_unggulan')->nullable()->after('prestasi');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ekstrakurikuler')) {
                $table->json('ekstrakurikuler')->nullable()->after('program_unggulan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'testimoni')) {
                $table->json('testimoni')->nullable()->after('ekstrakurikuler');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_nama')) {
                $table->string('kepala_sekolah_nama')->nullable()->after('testimoni');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_gelar')) {
                $table->string('kepala_sekolah_gelar')->nullable()->after('kepala_sekolah_nama');
            }
            if (!Schema::hasColumn('ppdb_settings', 'kepala_sekolah_sambutan')) {
                $table->text('kepala_sekolah_sambutan')->nullable()->after('kepala_sekolah_gelar');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_siswa')) {
                $table->integer('jumlah_siswa')->nullable()->after('kepala_sekolah_sambutan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_guru')) {
                $table->integer('jumlah_guru')->nullable()->after('jumlah_siswa');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_jurusan')) {
                $table->integer('jumlah_jurusan')->nullable()->after('jumlah_guru');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jumlah_sarana')) {
                $table->integer('jumlah_sarana')->nullable()->after('jumlah_jurusan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'video_profile')) {
                $table->string('video_profile')->nullable()->after('jumlah_sarana');
            }
            if (!Schema::hasColumn('ppdb_settings', 'brosur_pdf')) {
                $table->string('brosur_pdf')->nullable()->after('video_profile');
            }
            if (!Schema::hasColumn('ppdb_settings', 'galeri_foto')) {
                $table->json('galeri_foto')->nullable()->after('brosur_pdf');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jam_operasional_buka')) {
                $table->time('jam_operasional_buka')->nullable()->after('galeri_foto');
            }
            if (!Schema::hasColumn('ppdb_settings', 'jam_operasional_tutup')) {
                $table->time('jam_operasional_tutup')->nullable()->after('jam_operasional_buka');
            }
            if (!Schema::hasColumn('ppdb_settings', 'faq')) {
                $table->json('faq')->nullable()->after('jam_operasional_tutup');
            }
            if (!Schema::hasColumn('ppdb_settings', 'alur_pendaftaran')) {
                $table->json('alur_pendaftaran')->nullable()->after('faq');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_status')) {
                $table->enum('ppdb_status', ['buka', 'tutup'])->default('tutup')->after('alur_pendaftaran');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_jadwal_buka')) {
                $table->dateTime('ppdb_jadwal_buka')->nullable()->after('ppdb_status');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_jadwal_tutup')) {
                $table->dateTime('ppdb_jadwal_tutup')->nullable()->after('ppdb_jadwal_buka');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_kuota_total')) {
                $table->integer('ppdb_kuota_total')->nullable()->after('ppdb_jadwal_tutup');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_jadwal_pengumuman')) {
                $table->dateTime('ppdb_jadwal_pengumuman')->nullable()->after('ppdb_kuota_total');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_kuota_jurusan')) {
                $table->json('ppdb_kuota_jurusan')->nullable()->after('ppdb_jadwal_pengumuman');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_jalur')) {
                $table->json('ppdb_jalur')->nullable()->after('ppdb_kuota_jurusan');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_biaya_pendaftaran')) {
                $table->text('ppdb_biaya_pendaftaran')->nullable()->after('ppdb_jalur');
            }
            if (!Schema::hasColumn('ppdb_settings', 'ppdb_catatan_pengumuman')) {
                $table->text('ppdb_catatan_pengumuman')->nullable()->after('ppdb_biaya_pendaftaran');
            }
        });

        // Copy data from madrasahs to ppdb_settings
        DB::statement("
            INSERT INTO ppdb_settings (
                sekolah_id, slug, nama_sekolah, tahun, tagline, akreditasi, tahun_berdiri, telepon, email, website,
                deskripsi_singkat, sejarah, nilai_nilai, visi, misi, fasilitas, keunggulan, jurusan, prestasi,
                program_unggulan, ekstrakurikuler, testimoni, kepala_sekolah_nama, kepala_sekolah_gelar,
                kepala_sekolah_sambutan, jumlah_siswa, jumlah_guru, jumlah_jurusan, jumlah_sarana, video_profile,
                brosur_pdf, galeri_foto, jam_operasional_buka, jam_operasional_tutup, faq, alur_pendaftaran,
                ppdb_status, ppdb_jadwal_buka, ppdb_jadwal_tutup, ppdb_kuota_total, ppdb_jadwal_pengumuman,
                ppdb_kuota_jurusan, ppdb_jalur, ppdb_biaya_pendaftaran, ppdb_catatan_pengumuman, created_at, updated_at
            )
            SELECT
                id, CONCAT(REPLACE(LOWER(name), ' ', '-'), '-', id, '-', YEAR(NOW())),
                name, YEAR(NOW()), tagline, akreditasi, tahun_berdiri, telepon, email, website,
                deskripsi_singkat, sejarah, nilai_nilai, visi, misi, fasilitas, keunggulan, jurusan, prestasi,
                program_unggulan, ekstrakurikuler, testimoni, kepala_sekolah_nama, kepala_sekolah_gelar,
                kepala_sekolah_sambutan, jumlah_siswa, jumlah_guru, jumlah_jurusan, jumlah_sarana, video_profile,
                brosur_pdf, galeri_foto, jam_operasional_buka, jam_operasional_tutup, faq, alur_pendaftaran,
                ppdb_status, ppdb_jadwal_buka, ppdb_jadwal_tutup, ppdb_kuota_total, ppdb_jadwal_pengumuman,
                ppdb_kuota_jurusan, ppdb_jalur, ppdb_biaya_pendaftaran, ppdb_catatan_pengumuman, NOW(), NOW()
            FROM madrasahs
            ON DUPLICATE KEY UPDATE
                tagline = VALUES(tagline),
                akreditasi = VALUES(akreditasi),
                tahun_berdiri = VALUES(tahun_berdiri),
                telepon = VALUES(telepon),
                email = VALUES(email),
                website = VALUES(website),
                deskripsi_singkat = VALUES(deskripsi_singkat),
                sejarah = VALUES(sejarah),
                nilai_nilai = VALUES(nilai_nilai),
                visi = VALUES(visi),
                misi = VALUES(misi),
                fasilitas = VALUES(fasilitas),
                keunggulan = VALUES(keunggulan),
                jurusan = VALUES(jurusan),
                prestasi = VALUES(prestasi),
                program_unggulan = VALUES(program_unggulan),
                ekstrakurikuler = VALUES(ekstrakurikuler),
                testimoni = VALUES(testimoni),
                kepala_sekolah_nama = VALUES(kepala_sekolah_nama),
                kepala_sekolah_gelar = VALUES(kepala_sekolah_gelar),
                kepala_sekolah_sambutan = VALUES(kepala_sekolah_sambutan),
                jumlah_siswa = VALUES(jumlah_siswa),
                jumlah_guru = VALUES(jumlah_guru),
                jumlah_jurusan = VALUES(jumlah_jurusan),
                jumlah_sarana = VALUES(jumlah_sarana),
                video_profile = VALUES(video_profile),
                brosur_pdf = VALUES(brosur_pdf),
                galeri_foto = VALUES(galeri_foto),
                jam_operasional_buka = VALUES(jam_operasional_buka),
                jam_operasional_tutup = VALUES(jam_operasional_tutup),
                faq = VALUES(faq),
                alur_pendaftaran = VALUES(alur_pendaftaran),
                ppdb_status = VALUES(ppdb_status),
                ppdb_jadwal_buka = VALUES(ppdb_jadwal_buka),
                ppdb_jadwal_tutup = VALUES(ppdb_jadwal_tutup),
                ppdb_kuota_total = VALUES(ppdb_kuota_total),
                ppdb_jadwal_pengumuman = VALUES(ppdb_jadwal_pengumuman),
                ppdb_kuota_jurusan = VALUES(ppdb_kuota_jurusan),
                ppdb_jalur = VALUES(ppdb_jalur),
                ppdb_biaya_pendaftaran = VALUES(ppdb_biaya_pendaftaran),
                ppdb_catatan_pengumuman = VALUES(ppdb_catatan_pengumuman),
                updated_at = NOW()
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            //
        });
    }
};
