# Implementasi Presensi Kegiatan BPPPMNU

Tanggal: 11 September 2026. Lingkup akhir: Laravel dan mobile web. Tidak ada perubahan Flutter.

Implementasi tersedia di source code; migration belum dijalankan pada database operasional. Database operasional hanya diperiksa secara read-only untuk memastikan kompatibilitas `users.id` dan `users.role`.

## 1. Fitur dan keputusan V1

- Role khusus `pengurus_bpppmnu`, login melalui halaman mobile existing, langsung ke Presensi.
- Tiga menu utama: Presensi, Riwayat Presensi, Profil. Detail/scanner berada di dalam alur Presensi; ganti password berada di Profil.
- Layout mobile dan ikon Boxicons existing digunakan kembali. Profil mengikuti gaya kartu tenaga pendidik; handler perubahan password existing digunakan kembali tanpa melonggarkan validasinya.
- Admin yang berwenang: **hanya `admin_yayasan`**. Admin sekolah, pengurus lama, tenaga pendidik, serta role lain tidak diberi akses modul ini.
- Pengelolaan akun memakai model `User`, hashing Laravel, kolom `ketugasan` untuk jabatan mengikuti pengelolaan pengurus existing, aktivasi/nonaktivasi, dan reset password. Role ditentukan backend, bukan input formulir.
- Agenda: draft → published, atau cancelled. Agenda dengan attendance atau histori yang telah selesai tidak dapat dibatalkan agar rekap tidak berubah menjadi tidak relevan.
- Seluruh agenda dan daftar undangan dikunci sejak waktu buka presensi atau ketika ada attendance. Tidak tersedia endpoint hard delete kegiatan.
- Satu scan berarti Hadir. Tidak ada face recognition, GPS, scan pulang, registrasi tamu mandiri, atau fitur Flutter.

## 2. File baru

- `app/Exports/BpppmnuAttendanceExport.php`
- `app/Http/Controllers/AdminYayasan/BpppmnuEventController.php`
- `app/Http/Controllers/AdminYayasan/BpppmnuMemberController.php`
- `app/Http/Controllers/Mobile/BpppmnuController.php`
- `app/Http/Middleware/BpppmnuRole.php`
- `app/Http/Middleware/RestrictBpppmnuAccount.php`
- `app/Models/BpppmnuEvent.php`
- `app/Models/BpppmnuEventAttendance.php`
- `app/Models/BpppmnuEventInvitation.php`
- `app/Models/BpppmnuEventQrToken.php`
- `app/Services/BpppmnuAttendanceService.php`
- `app/Services/BpppmnuReportService.php`
- `database/migrations/2026_09_11_000000_add_pengurus_bpppmnu_role.php`
- `database/migrations/2026_09_11_000001_create_bpppmnu_event_tables.php`
- `public/js/bpppmnu-scanner.js`
- `public/vendor/jsqr/LICENSE`
- `public/vendor/jsqr/jsQR.js`
- `resources/views/admin/bpppmnu/form.blade.php`
- `resources/views/admin/bpppmnu/index.blade.php`
- `resources/views/admin/bpppmnu/layout.blade.php`
- `resources/views/admin/bpppmnu/member-fields.blade.php`
- `resources/views/admin/bpppmnu/members.blade.php`
- `resources/views/admin/bpppmnu/qr.blade.php`
- `resources/views/admin/bpppmnu/show.blade.php`
- `resources/views/bpppmnu/event-details.blade.php`
- `resources/views/mobile/bpppmnu/history.blade.php`
- `resources/views/mobile/bpppmnu/index.blade.php`
- `resources/views/mobile/bpppmnu/layout.blade.php`
- `resources/views/mobile/bpppmnu/profile.blade.php`
- `resources/views/mobile/bpppmnu/show.blade.php`
- `resources/views/mobile/partials/password-fields.blade.php`
- `routes/bpppmnu.php`
- `tests/Feature/BpppmnuAttendanceTest.php`
- `tests/Feature/BpppmnuRoleMigrationTest.php`
- `tests/js/bpppmnu-scanner.test.cjs`

- `BPPPMNU_IMPLEMENTATION.md` — laporan ini.

## 3. File existing yang diubah

- `app/Exceptions/Handler.php`
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Mobile/MobileAuthController.php`
- `app/Http/Controllers/Mobile/Profile/ProfileController.php`
- `app/Http/Kernel.php`
- `app/Http/Middleware/Authenticate.php`
- `app/Http/Middleware/RedirectIfAuthenticated.php`
- `app/Models/User.php`
- `resources/views/layouts/mobile.blade.php`
- `resources/views/layouts/sidebar.blade.php`
- `resources/views/layouts/vendor-scripts.blade.php`
- `routes/api.php`
- `routes/web.php`

Catatan: lima file compiled view dalam `storage/framework/views` sudah memiliki perubahan sebelum pekerjaan dimulai; perubahan tersebut bukan bagian implementasi BPPPMNU dan tidak disertakan dalam daftar di atas.

Perubahan kecil `layouts/vendor-scripts.blade.php` mengubah dua komentar HTML yang mengandung directive Blade menjadi komentar Blade. Sebelumnya `@push` dan `@section` di komentar ikut dieksekusi dan meninggalkan output buffer terbuka saat halaman dirender. Tidak ada perubahan daftar script atau perilaku bisnis.

## 4. Migration dan struktur database final

1. `2026_09_11_000000_add_pengurus_bpppmnu_role.php`
   - Database existing menggunakan MySQL ENUM untuk `users.role` dan BIGINT UNSIGNED untuk `users.id`, dikonfirmasi melalui metadata schema.
   - Menambahkan nilai ENUM `pengurus_bpppmnu` sambil mempertahankan nilai lama, default, nullability, serta collation.
   - Aman dipanggil ulang jika nilai sudah tersedia. Kolom string/SQLite tidak memerlukan perubahan.
   - Rollback sengaja mempertahankan nilai ENUM tambahan untuk melindungi akun yang sudah dibuat.
2. `2026_09_11_000001_create_bpppmnu_event_tables.php`

| Tabel | Kolom inti / constraint |
|---|---|
| `bpppmnu_events` | `id`, `name`, `type`, `description`, `organizer`, `person_in_charge`, `start_at`, `end_at`, `attendance_open_at`, `attendance_close_at`, `location_name`, `address`, `meeting_url`, `rundown`, `attachment`, `status`, `created_by`, timestamps; indeks status + start_at |
| `bpppmnu_event_invitations` | `id`, `event_id`, `user_id`, timestamps; UNIQUE(event_id,user_id) |
| `bpppmnu_event_attendances` | `id`, `event_id`, `user_id`, `attended_at`, `method=qr`, timestamps; UNIQUE(event_id,user_id) |
| `bpppmnu_event_qr_tokens` | `id`, `event_id`, `token_hash` SHA-256 unik, `expires_at`, `revoked_at`, timestamps |

Foreign key ke event/user memakai `restrictOnDelete`. Attendance juga memiliki foreign key gabungan ke pasangan event–user pada invitation, sehingga attendance tanpa undangan tidak dapat dimasukkan langsung melalui database. Tabel presensi tenaga pendidik tidak diubah.

## 5. Route baru

Seluruh route web tetap menggunakan middleware web dan CSRF existing. Nama route admin diawali `admin.bpppmnu.`, mobile `mobile.bpppmnu.`, API `api.bpppmnu.`.

### Admin: `/admin-yayasan/bpppmnu`

| Method | Path relatif | Fungsi |
|---|---|---|
| GET / POST | `/pengurus` | Daftar / tambah akun |
| PUT | `/pengurus/{member}` | Edit akun, status aktif, reset password |
| GET / POST | `/kegiatan` | Daftar / simpan draft |
| GET | `/kegiatan/create` | Form agenda |
| GET / PUT | `/kegiatan/{event}` | Detail & rekap / simpan perubahan |
| GET | `/kegiatan/{event}/edit` | Form edit sebelum terkunci |
| POST | `/kegiatan/{event}/publish` | Terbitkan |
| POST | `/kegiatan/{event}/cancel` | Batalkan |
| POST | `/kegiatan/{event}/qr` | Generate, cabut QR sebelumnya, tampilkan QR baru |
| POST | `/kegiatan/{event}/revoke` | Cabut token aktif |
| GET | `/kegiatan/{event}/attachment` | Unduh lampiran privat |
| GET | `/kegiatan/{event}/export` | Rekap XLSX |

### Mobile: `/mobile/bpppmnu`

| Method | Path relatif | Fungsi |
|---|---|---|
| GET | `/presensi` | Agenda pengguna |
| GET | `/riwayat-presensi` | Riwayat + filter month/year/status |
| GET | `/profil` | Profil dan form password |
| POST | `/profil/password` | Ganti password; throttle 5/menit |
| POST | `/logout` | Keluar dan hapus sesi |
| GET | `/kegiatan/{event}` | Detail kegiatan undangan |
| GET | `/kegiatan/{event}/attachment` | Lampiran privat untuk peserta |
| POST | `/kegiatan/{event}/scan` | Catat hadir; throttle 15/menit |

### API Laravel: `/api/mobile/app/bpppmnu`

GET `presensi`, `history`, `profile`, `events/{event}`, `events/{event}/attachment`;
POST `profile/password`, `events/{event}/scan`.

API dilindungi Sanctum dan role exact. `/api/mobile/login` menerima `login_as=pengurus_bpppmnu` dan mengembalikan mobile_route baru. Payload akun role baru dibatasi ke field identitas sehingga hash password tidak ikut dikembalikan. Perilaku role API existing dipertahankan. Tidak ada implementasi klien Flutter.

## 6. Authorization dan alur login

- `BpppmnuRole` memerlukan role persis sesuai route dan akun aktif; default akses ditolak dengan 403. Response modul memakai `Cache-Control: private, no-store`.
- `RestrictBpppmnuAccount` ditambahkan pada grup web/API, tetapi hanya memengaruhi role baru. Membatasi akun tersebut ke modul BPPPMNU dan endpoint akun bersama yang diperlukan; mencegah akses URL legacy yang tidak seluruhnya memiliki filter role.
- Halaman login dan mekanisme autentikasi Laravel dipertahankan. MobileAuthController menambahkan role baru ke whitelist dan redirect ke `/mobile/bpppmnu/presensi`.
- Login alias `presensi.nuist.id` menerima role baru. Route khusus tenaga pendidik di domain itu tetap memiliki pembatasannya sendiri.
- Pengguna BPPPMNU yang sudah login diarahkan ke Presensi saat membuka login/dashboard/root.
- Guest yang membuka modul mobile diarahkan ke `/mobile/login`.
- Handler ganti password existing menerima role baru; route legacy tenaga pendidik tetap diblokir untuk BPPPMNU oleh pembatas akses role baru.

## 7. Alur admin membuat kegiatan

1. Admin yayasan membuka Kegiatan BPPPMNU → Pengurus BPPPMNU dan membuat akun resmi dengan email serta password awal terverifikasi.
2. Buka Agenda Kegiatan → Buat Kegiatan, isi rincian, waktu WIB, periode presensi, tempat, susunan acara, lampiran opsional, dan pilih akun undangan.
3. Simpan Draft, periksa detail, lalu Terbitkan. Draft tidak terlihat pada mobile.
4. Generate & Tampilkan QR Baru. Setiap generate mencabut QR sebelumnya. QR baru dapat diunduh sebagai SVG.
5. Tampilkan QR kepada peserta, pantau rekap pada detail kegiatan; muat ulang untuk memperbarui statistik.
6. Unduh rekap Excel bila diperlukan.

Nama undangan dihubungkan ke `users.id`, bukan disimpan sebagai teks bebas. Akun baru tidak otomatis diundang ke semua kegiatan.

## 8. Alur QR sampai attendance

1. Peserta membuka agenda → Detail/Scan QR → Buka Kamera.
2. Kamera dibaca lokal oleh jsQR; payload berisi tipe BPPPMNU, event_id dan token acak 32-byte (64 karakter hex).
3. Browser mengirim **POST** `qr_token` dengan CSRF. Token tidak berada di query string.
4. Backend memeriksa role aktif, event, undangan, status terbit, hash token, revoke, waktu buka/tutup, expiry, dan attendance existing.
5. Transaksi mengunci baris event (`lockForUpdate`); generate/revoke/edit/cancel juga menggunakan urutan lock event yang sama.
6. Kehadiran dicatat dengan waktu server. UNIQUE(event_id,user_id) menjadi pengaman database terakhir.
7. Scan ulang mengembalikan pesan sudah melakukan presensi tanpa record tambahan. Browser menampilkan berhasil hanya setelah server mengonfirmasi.

Token mentah hanya tersedia saat QR baru ditampilkan; database menyimpan hash. Karena itu QR lama tidak bisa ditampilkan ulang dari database; unduh saat generate atau generate ulang. Token tidak dicatat secara eksplisit dalam log dan `qr_token` tidak di-flash saat validasi gagal.

Jika koneksi gagal, UI menyatakan presensi belum terkonfirmasi dan menyediakan percobaan ulang. Kamera dihentikan setelah scan, saat ditutup, atau ketika halaman ditinggalkan/disembunyikan.

## 9. Riwayat, rekap dan integritas

- Riwayat dan rekap berangkat dari invitation LEFT JOIN attendance pada event_id + user_id.
- Riwayat selesai mensyaratkan **end_at < waktu server DAN attendance_close_at < waktu server**.
- Undangan tanpa attendance menjadi Tidak Hadir hanya setelah dua syarat tersebut terpenuhi.
- Draft dan cancelled tidak masuk riwayat ketidakhadiran; rekap sebelum selesai menggunakan Belum Presensi.
- Filter riwayat: bulan, tahun, Hadir/Tidak Hadir; hasil dipaginasi.
- Rekap: jumlah undangan, hadir, sisa peserta, persentase, identitas, jabatan, status dan waktu hadir.
- Ekspor XLSX memakai StringValueBinder untuk mempertahankan nol depan ID NUIST serta mencegah data nama menjadi formula spreadsheet.
- Lampiran PDF/JPG/PNG maksimum 5 MB disimpan pada disk lokal privat; download diperiksa ulang berdasarkan role/undangan.

## 10. Cara pemasangan dan perintah cache/build

Jalankan dari root proyek pada environment deployment yang dituju:

```sh
php artisan migrate --path=database/migrations/2026_09_11_000000_add_pengurus_bpppmnu_role.php --path=database/migrations/2026_09_11_000001_create_bpppmnu_event_tables.php
php artisan route:clear
php artisan view:clear
```

Untuk environment production noninteraktif, tambahkan `--force` ke perintah migrate sesuai prosedur deployment yang berlaku. Dua path spesifik di atas menghindari menjalankan migration pending lain yang tidak terkait.

Tidak perlu `npm run build`, build Flutter, atau dependency Composer baru: JavaScript pemindai berada langsung di `public/`. Pastikan folder storage privat dapat ditulis aplikasi dan akses kamera menggunakan HTTPS. `route:clear` diperlukan karena workspace memiliki route cache existing yang belum memuat modul baru. Rebuild route/config cache mengikuti proses deployment proyek bila digunakan; tidak perlu mengubah .env untuk modul ini.

Migration operasional belum dijalankan dalam pekerjaan ini. ENUM rollback sengaja non-destructive; rollback tabel akan menghapus data modul sehingga bukan prosedur untuk membatalkan acara.

## 11. Dependency

- Existing: `bacon/bacon-qr-code` untuk QR SVG; `maatwebsite/excel` untuk XLSX; Laravel/Sanctum untuk auth.
- Tambahan aset browser: **jsQR 1.4.0**, disimpan lokal sebagai `public/vendor/jsqr/jsQR.js` beserta lisensi Apache-2.0. Tidak memerlukan CDN saat pemakaian. Sumber: https://github.com/cozmo/jsQR ; distribusi versi tetap dari npm/jsDelivr.
- Tidak ada perubahan composer.json, package.json, pubspec, atau dependency Flutter.

## 12. Hasil pengujian

- **41 test PHPUnit BPPPMNU, 150 assertion: lolos.** Meliputi login web/API/domain alias, role, IDOR, status publikasi, undangan, semua keadaan QR, waktu server, scan ganda, unique database, riwayat/filter, ganti password, CSRF, lampiran privat, CRUD admin, akun, ekspor, rendering seluruh halaman, serta ENUM migration.
- **5 test Node: lolos.** Meliputi kirim token+CSRF, penghentian kamera, kegagalan jaringan, penolakan server, penolakan kamera, dan QR dari BaconQrCode berhasil dibaca jsQR lokal.
- Seluruh file PHP baru/diubah di luar compiled views: syntax check lolos. `git diff --check` dan syntax JavaScript lolos. File PHP modul mengikuti Laravel Pint.
- Suite penuh: **72 test, 186 assertion; 19 error dan 1 failure** pada test existing. Tidak ada error/failure test BPPPMNU.
- Baseline kode HEAD sebelum perubahan juga diuji terpisah: **31 test, 36 assertion; 19 error dan 1 failure yang sama**.
- 19 error existing berasal dari migration `2025_01_01_000001_add_materi_id_to_talenta_penilaian_peserta.php` yang mengubah tabel `talenta_penilaian_peserta` sebelum tabel tersedia pada database SQLite kosong.
- Satu failure existing: `ExampleTest` mengharapkan GET `/` menghasilkan 200, sedangkan route aplikasi menghasilkan redirect 302.
- Peringatan deprecation konfigurasi PHPUnit existing masih ada.

Perintah reproduksi terisolasi (tidak menggunakan config/route cache operasional):

```sh
mkdir -p /tmp/nuist-bpppmnu-views
APP_ENV=testing BROADCAST_CONNECTION=log BROADCAST_DRIVER=log APP_CONFIG_CACHE=/tmp/nuist-bpppmnu-config.php APP_ROUTES_CACHE=/tmp/nuist-bpppmnu-routes.php VIEW_COMPILED_PATH=/tmp/nuist-bpppmnu-views vendor/bin/phpunit tests/Feature/BpppmnuAttendanceTest.php tests/Feature/BpppmnuRoleMigrationTest.php --no-progress
node --test tests/js/bpppmnu-scanner.test.cjs
```

Jalankan perintah PHPUnit tanpa argumen file test untuk suite penuh. Nama path cache `/tmp` di atas harus belum berisi cache aplikasi; test database menggunakan SQLite in-memory dan fixtures sendiri karena migration awal proyek tidak lengkap untuk fresh install.

## 13. Batas verifikasi dan risiko

- Browser tidak tersedia dalam sesi ini. Rendering server dan struktur tiga link navigasi telah diuji, tetapi screenshot/tampilan perangkat, izin kamera nyata Android/iOS, serta scan melalui kamera fisik masih perlu pengujian manual.
- MySQL schema operasional dibaca, tetapi ALTER ENUM belum dieksekusi pada database operasional; test ENUM memeriksa SQL yang mempertahankan nilai existing. Tabel modul diuji pada SQLite.
- Transaksi/lock event dan unique constraint sudah diimplementasikan. Beban scan serentak pada MySQL produksi belum diukur.
- QR statis dapat dibagikan lewat foto. V1 tidak memverifikasi lokasi fisik; admin dapat mencabut/generate ulang QR.
- Pengguna yang juga memiliki role tenaga pendidik tidak otomatis memperoleh dua role; model akun existing masih single-role.
- Suite regresi penuh belum hijau karena masalah baseline di atas. Alur wajah dan presensi sekolah tidak diubah.

## 14. Checklist manual sebelum digunakan

- [ ] Jalankan dua migration, clear route/view cache, lalu pastikan menu hanya terlihat pada admin yayasan.
- [ ] Buat dua akun BPPPMNU; pastikan login biasa dan `presensi.nuist.id` mengarah ke Presensi.
- [ ] Pastikan tiga menu mobile tampil baik pada layar kecil dan tidak ada menu monitoring pengurus lama.
- [ ] Buat draft, undang hanya akun pertama, cek akun kedua tidak melihat kegiatan atau lampiran.
- [ ] Terbitkan agenda; periksa tanggal WIB, lokasi, deskripsi, susunan acara, dan lampiran.
- [ ] Generate QR, unduh SVG, buka kamera Android/iOS, dan scan dari layar/cetakan.
- [ ] Ulangi scan: tetap satu attendance. Uji sebelum buka, setelah tutup, QR berbeda, revoke, dan generate ulang.
- [ ] Tolak izin kamera dan putuskan koneksi; pastikan tidak muncul keberhasilan palsu.
- [ ] Setelah kegiatan selesai dan presensi tertutup, cocokkan Hadir/Tidak Hadir di riwayat serta semua filter.
- [ ] Cocokkan rekap admin dan file Excel, termasuk ID NUIST dengan nol depan.
- [ ] Ganti password dengan password lama salah/benar dan konfirmasi berbeda; login ulang dengan password baru.
- [ ] Nonaktifkan akun dan pastikan akses ditolak; verifikasi logout dari Profil.
- [ ] Cek agenda/undangan terkunci saat presensi dibuka dan pembatalan tidak menghapus histori hadir.
- [ ] Smoke test login tenaga pendidik, pengurus lama, admin sekolah, admin yayasan, dan presensi wajah existing.
