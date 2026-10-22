# Rencana Perapihan NUIST

Tanggal: 22 Oktober 2026  
Basis: audit hosting NUIST tanggal 2 Oktober 2026 dan source repository yang tersedia.

## Tujuan

Merapi­kan tampilan dan fitur tanpa mengganggu layanan produksi yang sudah berjalan. Pengerjaan dilakukan per alur pengguna, dengan perubahan schema secara additive dan validasi di staging.

## Kondisi yang perlu diingat

- NUIST utama adalah modular monolith Laravel; subdomain `admin`, `sekolah`, `presensi`, `keuangan`, `spmb`, dan `mgmp` membatasi tampilan/route dari aplikasi yang sama.
- AMI dan CBT adalah aplikasi terpisah dengan database dan autentikasi sendiri.
- Production memiliki 129 tabel dengan primary key konsisten dan 198 foreign key. Temuan schema lokal yang lebih lama tidak boleh dianggap sebagai kondisi production.
- Repository menyimpan 129 file migration, sedangkan production mencatat 304 migration. Sebanyak 175 migration yang sudah pernah dijalankan tidak tersedia di repository. Ini adalah blocker pemulihan dan instalasi environment baru.
- Audit menyebut regression test belum menjadi baseline yang stabil. Database lokal tidak digunakan untuk menyimpulkan keadaan production.
- Modul presensi, pembayaran, PPDB, dan identitas siswa berisiko tinggi; jangan rewrite atau ubah data produksi sebagai bagian dari perapihan UI.
- `docs/PHASE_0_SCHEMA_RECOVERY_PLAN.md` yang ada di konteks IDE tidak ditemukan di checkout ini. Dokumen ini memakai `docs/AUDIT_HOSTING_NUIST_2026-10-02.md` sebagai rujukan yang tersedia.

## Prioritas

| Tingkat | Fokus | Kriteria selesai |
|---|---|---|
| P0 — Kritis | Reproducibility schema dan akses lintas sekolah | Baseline schema terdokumentasi; restore staging berhasil; perubahan akses punya pemeriksaan negatif per sekolah |
| P1 — Menghambat | Alur utama pengguna dan regression baseline | Login, dashboard, data sekolah, presensi/jurnal, PPDB, serta pembayaran dapat ditelusuri dan diverifikasi end-to-end |
| P2 — Rapikan | Konsistensi UI dan responsif | Navigasi, istilah, tabel/form, empty/error/loading state, dan tampilan mobile konsisten per modul |
| P3 — Pengembangan | Akademik inti dan fitur baru | Academic period, kelas, mapel, enrollment, dan assignment memiliki relasi yang jelas sebelum kuis/nilai dibangun |

## Urutan kerja

### Tahap 0 — Baseline dan pemulihan schema

1. Inventaris file migration yang tersedia, ledger migration production, serta schema-only dump yang disimpan aman.
2. Pulihkan migration historis dari sumber terkontrol atau buat baseline schema resmi untuk instalasi baru; jangan mengarang ulang migration yang hilang.
3. Buat clone staging dari backup tersanitasi dan buktikan restore dari awal.
4. Catat koneksi/database yang dipakai setiap environment agar database lokal lama tidak disangka sebagai production.

**Selesai bila:** staging dapat dipulihkan, schema cocok dengan baseline, dan proses setup baru terdokumentasi. Tidak ada migration production dijalankan selama tahap audit ini.

### Tahap 1 — Regression dan peta alur

1. Tetapkan test gate untuk login/role/scope sekolah, presensi, jurnal mengajar, PPDB, SPP, dan kontrak API mobile.
2. Telusuri satu per satu alur utama untuk admin sekolah, guru/pegawai, pengurus, operator keuangan, dan siswa.
3. Catat masalah dengan reproduksi, dampak, role terdampak, halaman/API, serta prioritas.

**Selesai bila:** setiap alur kritis memiliki langkah reproduksi dan pemeriksaan regresi sebelum perubahan fitur.

### Tahap 2 — Identitas, hak akses, dan scope sekolah

1. Rancang link canonical antara akun siswa dan data siswa, lalu rencana backfill dengan exception report.
2. Rancang membership, role, permission, dan penugasan kepala sekolah yang memiliki periode berlaku.
3. Terapkan school context/policy secara bertahap; pertahankan role dan payload lama selama masa transisi.
4. Jangan menghubungkan AMI/CBT dengan berbagi tabel user atau password; siapkan kontrak OIDC dan account linking.

**Selesai bila:** akses di sekolah sendiri tetap berfungsi, akses lintas sekolah ditolak, dan perubahan identitas dapat di-rollback.

### Tahap 3 — Perapihan UI berdasarkan alur

Urutan halaman: login dan dashboard per role → master sekolah/GTK/siswa → jadwal dan jurnal → presensi/izin → PPDB → keuangan/SPP → modul lain.

Untuk setiap halaman, rapikan:

- navigasi dan label menu;
- hierarki judul, ringkasan, aksi utama, dan informasi status;
- form: label, validasi, pesan berhasil/gagal, dan pencegahan input ganda;
- tabel: pencarian, filter, pagination, aksi baris, serta tampilan layar kecil;
- state kosong, memuat, error, dan berhasil;
- kontras, fokus keyboard, dan ukuran target sentuh.

UI tidak dianggap selesai sebelum alur di balik tombol diuji untuk role yang sesuai.

### Tahap 4 — Academic core

Rancang periode akademik, kelas/rombel, mapel, enrollment siswa, profil staf, dan teaching assignment. Backfill kolom teks/JSON jadwal secara bertahap dengan laporan nilai yang tidak dapat dipetakan. Gunakan dual-read/dual-write dan feature flag sebelum cutover.

Setelah fondasi ini stabil, baru bangun tugas, pengumpulan, kuis, percobaan, dan buku nilai. Jangan memakai ulang `tugas_nilai`, `questions`, atau tabel Talenta untuk akademik siswa.

## Cara memilih pekerjaan berikutnya

Ambil satu temuan dengan urutan: risiko keselamatan data/akses → alur yang memblokir tugas pengguna → konsistensi UI. Satu pekerjaan harus menyebut halaman dan role, perilaku yang diharapkan, dampak pada data/API, serta cara verifikasi. Perubahan yang menyentuh presensi, pembayaran, identity, atau migration diuji di staging sebelum rilis.

## Catatan status source

Saat rencana ini dibuat, `public/.user.ini` sudah memiliki perubahan lokal yang belum di-commit. Perubahan tersebut tidak termasuk rencana perapihan ini dan harus tetap dipertahankan.
