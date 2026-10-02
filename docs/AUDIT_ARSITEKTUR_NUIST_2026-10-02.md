# Audit Arsitektur NUIST

> **Status: digantikan oleh audit hosting.** Dokumen ini memakai database lokal dan tidak lagi menjadi sumber kebenaran untuk kondisi production. Gunakan `AUDIT_HOSTING_NUIST_2026-10-02.md`. Temuan source-code yang tidak bergantung pada schema tetap relevan, tetapi angka database, PK, FK, migration pending, dan kondisi AMI/CBT telah dikoreksi dalam dokumen baru.

Tanggal audit: 2 Oktober 2026  
Scope: repository utama Laravel, database lokal `nuist-local`, web/API route, autentikasi, authorization, aplikasi Flutter, migration, model, controller, middleware, view, dan test.  
Sifat audit: read-only. Tidak ada kode, migration, maupun data yang diubah.

## Ringkasan eksekutif

NUIST saat ini adalah modular monolith Laravel yang sudah memuat banyak domain nyata: data sekolah/GTK, presensi pegawai, jurnal mengajar, PPDB/SPMB, MGMP, Talenta, SIMFONI, UPPM, SPP siswa, SK Yayasan, AMI, agenda BPPPMNU, biometrik, notifikasi, dan aplikasi mobile. Fondasi ini layak dipertahankan, tetapi belum aman untuk langsung ditambah tugas, quiz, nilai, dan kelas sebagai modul besar.

Lima blocker fondasi adalah:

1. Riwayat schema tidak dapat direproduksi. Database mencatat 301 migration, repository hanya memiliki 129 file, dan 175 migration yang pernah dijalankan sudah tidak ada di repository. Tiga migration repository masih pending.
2. Authorization baru berbasis satu string `users.role` dan pemeriksaan manual di route/controller. Belum ada role/permission, policy menyeluruh, assignment multi-role, atau school-scope yang konsisten.
3. Identitas siswa terduplikasi. Data autentikasi berada di `siswa`, lalu saat login mobile disalin/disinkronkan menjadi record `users` ber-role `siswa` melalui link sintetis `nuist_id`.
4. Model akademik inti belum dinormalisasi. Kelas, mata pelajaran, tahun ajaran, semester, dan relasi guru-kelas/mapel sebagian besar berupa teks/JSON di `teaching_schedules` dan `siswa`.
5. Test baseline belum hijau: 72 backend test lulus, 30 gagal; satu Flutter test gagal. Mayoritas kegagalan backend berasal dari migration dasar yang hilang sehingga test database tidak bisa dibangun dari nol.

Keputusan arsitektur yang direkomendasikan: tetap gunakan satu modular monolith dan satu database pusat dahulu. Tambahkan lapisan identity/access dan tenant/school scope secara additive, stabilkan schema/test, lalu bangun academic core. Jangan memecah menjadi microservice atau memindahkan subdomain sekarang.

## 1. Current architecture

### Platform dan ukuran

| Komponen | Kondisi saat audit |
|---|---|
| Backend | Laravel 12.49.0, PHP 8.3.30 |
| Database | MySQL 8.0.44, schema aktif `nuist-local`, 129 tabel, sekitar 243 MB |
| Web auth | Session guard `web`, provider `users` |
| Student auth | Guard `siswa`/provider `siswas` tersedia, tetapi mobile login membuat/sinkron record bayangan ke `users` |
| API auth | Laravel Sanctum personal access token |
| Frontend | Blade/Livewire, Bootstrap/Skote, Vite; Capacitor dan Flutter juga ada |
| Route | 1.325 route: 1.241 web, 68 API; 1.102 memakai auth middleware |
| Source size | 111 controller, 102 model, 18 middleware, 129 file migration, 22 file test |
| Session/cache/queue lokal | file / file / sync |
| Broadcast | Reverb tersedia; driver audit lokal `log` |

### Struktur aplikasi

- `routes/web.php` adalah route monolitik sekitar 2.561 baris. `routes/api.php` memuat API mobile, student, teacher, pengurus, face/biometric, dan webhook. `routes/bpppmnu.php` ada tetapi registrasi utamanya tetap perlu dipahami bersama `web.php`.
- Controller sangat terkonsentrasi: `SkYayasanController` sekitar 7.721 baris, `Api/TeacherAppController` 4.747, `PresensiAdminController` 2.112, dan beberapa controller lain di atas 1.000 baris.
- Service layer sudah mulai baik pada area presensi, face/biometric, device/kiosk, SPP/BNI, dan provisioning akun. Pola ini seharusnya diteruskan untuk domain baru.
- Policy hanya tiga file. `AuthServiceProvider` bahkan memetakan model `Presensi` ke `IzinPolicy`; cakupan policy belum mewakili semua resource.
- View aktif bercampur dengan direktori template demo `resources/views/x-template`, sehingga inventaris UI terlihat jauh lebih besar daripada fitur bisnis aktual.

### Peta fitur existing

| Domain | Kondisi nyata |
|---|---|
| Identitas staf | `users` menjadi sumber autentikasi utama; profil GTK banyak berada langsung di tabel ini |
| Sekolah/yayasan | `yayasans` → `madrasahs`; satu yayasan dan 36 sekolah terhubung di data lokal |
| Presensi staf | Matang dan berdata besar: 65.035 `presensis`, izin, geofence/polygon, anti-fake-location, face/liveness, kiosk/device, approval, export |
| Jurnal/jadwal mengajar | `teaching_schedules`, `teaching_attendances`, periode jadwal, jumlah siswa kelas, kalender akademik; mapel/kelas masih text/JSON |
| Siswa | Master Dapodik-like di `siswa`; 16 record lokal; autentikasi dan akun mobile sudah ada tetapi identitas ganda |
| Keuangan siswa | `spp_siswa_*` mendukung setting, tagihan, transaksi, BNI VA; data lokal mayoritas kosong |
| Keuangan sekolah/yayasan | `tagihans`, `payments`, `uppm_*`; ini berbeda konteks dari tagihan siswa |
| PPDB/SPMB | Pendaftaran, jalur, verifikasi, setting sekolah dan subdomain `spmb.nuist.id` |
| MGMP/Academica | Grup, anggota, presensi, laporan/proposal/update |
| Talenta | Materi, peserta, pemateri, fasilitator, tugas/nilai dan instrumen penilaian; bukan modul tugas/quiz sekolah umum |
| AMI | Struktur audit mutu internal cukup ternormalisasi dan memakai FK dengan baik; data lokal masih kosong |
| SK Yayasan | Workflow request/import/template/document/approval sangat luas dan controller terlalu besar |
| Chat | Pesan satu-ke-satu sederhana pada `chats`, saat ini diarahkan admin ↔ super admin; belum cocok untuk komunikasi resmi sekolah |
| Notifikasi | Tabel notifikasi + push device/FCM; 33.455 record lokal |
| Tugas akademik | Belum ada. `tugas_nilai` dan tugas Talenta adalah domain pelatihan, bukan assignment siswa |
| Quiz akademik | Belum ada. `questions`, `answers`, `school_scores`, dan `soals` adalah instrumen/Talenta, bukan quiz kelas |
| Nilai akademik | Belum ada gradebook siswa yang terhubung tahun ajaran-kelas-mapel-guru |

### Domain/subdomain aktual

| Host | Route | Rekomendasi saat ini |
|---|---:|---|
| host bebas / `nuist.id` | 747 | Aplikasi utama, auth web, landing, mobile web, API pada `/api` |
| `admin.nuist.id` | 260 | Tetap sebagai presentation boundary admin/pengurus di monolith |
| `sekolah.nuist.id` | 83 | Tetap frontend sekolah, bukan aplikasi/database terpisah |
| `presensi.nuist.id` | 83 | Tetap alias/modul operasional presensi |
| `keuangan.nuist.id` | 67 | Tetap frontend keuangan, domain datanya perlu dipisahkan jelas antara sekolah dan siswa |
| `spmb.nuist.id` | 48 | Tetap modul publik/operasional PPDB |
| `mgmp.nuist.id` | 37 | Tetap modul MGMP |
| `auth.nuist.id` | 0 ditemukan | Target identity entry point, belum diimplementasikan di repo ini |
| `api.nuist.id` | 0 ditemukan | Saat ini API berada di `nuist.id/api`; jangan pindah sebelum kontrak mobile dan CORS diuji |
| `cbt.nuist.id` | 0 ditemukan | Tidak berada di aplikasi utama yang diaudit; perlakukan sebagai integrasi eksternal/standalone sampai diaudit terpisah |
| `ami.nuist.id` | 0 domain route khusus | Modul AMI ada di monolith tetapi bukan host khusus dalam route aktif |

Session lokal memakai file. Berbagi session lintas subdomain hanya aman jika semua host menunjuk deployment yang sama dan konfigurasi cookie domain, secure, SameSite, serta session store konsisten. Untuk aplikasi yang benar-benar standalone, gunakan OIDC/OAuth2 authorization code + PKCE; jangan berbagi cookie/database session langsung. API tetap memakai token scoped dan berumur terbatas.

## 2. Authentication, role, permission, dan scope existing

### Role aktual di database

| Role | Jumlah | Makna aktual |
|---|---:|---|
| `tenaga_pendidik` | 811 | Guru dan pegawai bercampur; kepala sekolah juga ditandai lewat `ketugasan` text |
| `dps` | 115 | Modul DPS |
| `admin` | 35 | Admin sekolah |
| `mgmp` | 18 | Pengelola MGMP |
| `fasilitator` | 12 | Talenta |
| `pemateri` | 10 | Talenta |
| `pengurus` | 3 | Pengurus lintas sekolah |
| kosong | 1 | Data invalid/legacy |
| `super_admin` | 1 | Akses global |
| `siswa` | 1 | Shadow user dari siswa |
| `admin_yayasan` | 1 | Admin yayasan |
| `pengurus_bpppmnu` | 1 | Pengurus khusus agenda BPPPMNU |
| enum tetapi tidak ada data | `user`, `admin_spp` | Legacy/fitur tanpa user aktif lokal |

### Permission aktual

Tidak ditemukan tabel `roles`, `permissions`, `role_user`, atau `permission_user`. Permission aktual adalah gabungan:

- middleware `role:...` pada route;
- perbandingan string role di controller/service/view;
- pemeriksaan `madrasah_id` manual;
- string `ketugasan === 'kepala madrasah/sekolah'` untuk kepala sekolah;
- beberapa policy khusus;
- middleware khusus BPPPMNU.

Akibatnya, “permission” belum dapat didaftar sebagai entitas data. Terdapat ratusan pemeriksaan role hardcoded dan kombinasi route role yang berbeda-beda. Ini rawan drift antara web, API, mobile, sidebar, dan controller.

### Relasi user existing yang benar-benar ditemukan

```text
Yayasan 1 ── * Madrasah
Madrasah 1 ── * User
User(role=tenaga_pendidik) ── * Presensi
User(role=tenaga_pendidik) ── * TeachingSchedule ── * TeachingAttendance

Madrasah 1 ── * Siswa
Siswa ──(login-time sync, bukan FK)── User(role=siswa)
Siswa 1 ── * SppBill 1 ── * SppTransaction

TeachingSchedule:
school_id + teacher_id + subject(text) + class_name(text)/class_names(JSON)
  └── belum mempunyai Subject/Class/ClassEnrollment FK
```

Tidak ada relasi formal User → profile siswa. Link siswa menggunakan `users.nuist_id = 'S' + base36(siswa.id)` dan fallback email + `madrasah_id`. Ini kompatibilitas sementara, bukan identity model jangka panjang.

### Temuan authorization/security

1. `RoleMiddleware` hanya memeriksa role, bukan action/resource/school; bahkan menulis log detail setiap request.
2. School scope tidak menjadi global/domain invariant. Beberapa controller melakukan check yang benar, tetapi banyak query lintas sekolah sengaja tersedia untuk role global dan pola manual mudah terlewat.
3. Endpoint API teacher/student/pengurus berada dalam satu grup `auth:sanctum`; role kemudian diperiksa di masing-masing controller. Satu method yang lupa memeriksa role dapat menjadi privilege escalation.
4. Token mobile dibuat tanpa abilities khusus. Kontrak token belum membedakan module/action/device.
5. `TrustHosts` tidak aktif pada global middleware. Validasi host bergantung pada server/proxy.
6. Web `mobile/login` dikecualikan dari CSRF untuk kompatibilitas WebView. Ini harus dipensiunkan setelah seluruh client memakai API token flow.
7. `users.nuist_id` dan `users.email` pada schema aktif tidak memiliki unique index yang terlihat, walaupun dipakai sebagai identifier. Generator `nuist_id` enam digit juga tidak melakukan retry berbasis unique constraint.
8. `UpdateLastSeen` memperbarui user dan memicu broadcast pada setiap web request; berisiko write amplification.
9. Face data lama masih berada di `users.face_data`, sementara `biometric_profiles` sudah ada. Masa transisi perlu retensi/enkripsi dan cutover yang eksplisit.
10. `APP_DEBUG` aktif pada environment lokal yang diaudit. Nilai production wajib diverifikasi terpisah; audit ini tidak mengklaim konfigurasi production sama.

## 3. Database architecture existing

### Kualitas schema

- 129 tabel aktif, tetapi hanya 92 foreign key constraint.
- Banyak relasi Eloquent mempunyai kolom ID tanpa FK database, termasuk core legacy.
- 41 tabel berkolom `id` tidak memiliki primary key pada metadata aktif; banyak juga tidak auto-increment. Ini adalah schema drift serius, bukan pola yang boleh diteruskan.
- `yayasans.id` bukan PK pada metadata aktif, sementara `madrasahs.yayasan_id` hanya index tanpa FK.
- `siswa.id`, `teaching_schedules.id`, tabel SPP utama, `tahun_pelajaran.id`, `tagihans.id`, dan banyak tabel Talenta tidak konsisten sebagai PK/auto-increment.
- Tabel dengan FK paling rapi adalah kelompok AMI, BPPPMNU baru, kiosk/device, face enrollment, dan SK Yayasan baru.
- Database memiliki dua schema bernama mirip pada server: `nuist-local` (aktif) dan `nuist_local` (legacy/duplikat). Keduanya tidak boleh dicampur atau dimigrasikan tanpa verifikasi backup dan connection target.

### Inventaris per tabel

Konvensi kolom penting: seluruh tabel memakai `id` sebagai identifier yang dimaksud kecuali `sessions`; label **PK drift** berarti kolom `id` ada tetapi metadata database aktif tidak menetapkannya sebagai primary key. FK yang disebut adalah constraint aktual, bukan hanya relasi Eloquent.

#### Identity, sekolah, sistem

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `users` | Akun pusat staf dan shadow account siswa; role, profil GTK, sekolah, face legacy | PK `id`; tidak ada FK untuk madrasah/status | Pertahankan sebagai identity root; pecah profile/assignment secara additive |
| `admins` | Tabel admin legacy kosong | PK `id`; tanpa FK | Bekukan, audit referensi, lalu deprecate |
| `yayasans` | Master yayasan | **PK drift**; tanpa FK | Pertahankan; perbaikan constraint hanya setelah data audit |
| `madrasahs` | Master sekolah, geofence, jadwal presensi, profil dasar | PK `id`; `yayasan_id` bukan FK | Pertahankan sebagai school root |
| `data_sekolah` | Rekap/agregat sekolah legacy | PK `id`; tanpa FK | Bedakan snapshot statistik vs master sekolah; jangan jadi identity source |
| `data_tenaga_pendidik` | Rekap jumlah GTK per sekolah/tahun/status | PK `id`; tanpa FK aktual | Pertahankan sebagai aggregate snapshot bila masih dipakai |
| `tenaga_pendidiks` | Master GTK legacy terpisah | **PK drift**; tanpa FK | Bekukan; `users` adalah sumber staf aktif |
| `status_kepegawaian` | Referensi status GTK | **PK drift** | Pertahankan dan normalisasi constraint |
| `tahun_pelajaran` | Master tahun pelajaran sederhana | **PK drift** | Konsolidasikan ke academic periods baru |
| `app_settings` | Konfigurasi global, payment/attendance | PK `id`; tanpa FK | Pertahankan, pisahkan secret dari DB bila perlu |
| `sessions` | Session database legacy/opsional | PK berupa string secara desain, tetapi metadata tidak menunjukkan PK | Saat ini driver file; tentukan satu shared store untuk deployment multi-node |
| `password_resets` | Token reset legacy | PK `id`; tanpa FK | Pertahankan sementara; evaluasi format Laravel 12 |
| `personal_access_tokens` | Token Sanctum | PK `id`; polymorphic, tanpa FK | Pertahankan; tambah abilities/expiry policy |
| `pending_registrations` | Registrasi akun menunggu approval | PK `id`; tanpa FK | Pertahankan bila provisioning mandiri tetap digunakan |
| `failed_jobs` | Queue failure | PK `id` | Pertahankan; queue saat audit masih sync |
| `commit_logs` | Log webhook GitHub | PK `id` | Pisahkan dari domain pendidikan |
| `development_histories` | Riwayat pengembangan | PK `id` | Pertahankan sebagai tooling/admin, bukan core domain |
| `landings` | Konten landing | PK `id` | Pertahankan |
| `customers` | Scaffold/legacy kosong | PK `id` | Kandidat hapus setelah usage audit |
| `broadcast_numbers` | Nomor broadcast | PK `id` | Pertahankan bila dipakai; tambahkan ownership/school bila diperlukan |

#### Siswa, PPDB, akademik, presensi

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `siswa` | Master siswa + kredensial + kelas text + data orang tua | **PK drift**; tanpa FK aktual | Pertahankan data; migrasikan auth ke identity link dan enrollment ternormalisasi |
| `ppdb_jalur` | Referensi jalur PPDB | PK `id`; tanpa FK | Pertahankan |
| `ppdb_pendaftar` | Calon siswa/pendaftaran | PK `id`; tanpa FK | Pertahankan; buat proses promote-to-student idempotent |
| `ppdb_settings` | Setting PPDB per sekolah | PK `id`; tanpa FK | Pertahankan; tambah FK/scope bertahap |
| `ppdb_verifikasi` | Verifikasi berkas/status PPDB | PK `id`; tanpa FK | Pertahankan; tambah FK setelah orphan audit |
| `teaching_schedule_periods` | Periode jadwal per sekolah/tahun/semester | PK `id`; FK school/creator/updater | Pertahankan dan jadikan bridge ke academic period baru |
| `teaching_schedules` | Jadwal guru; mapel/kelas text/JSON | **PK drift**; FK hanya period | Pertahankan, tambah nullable subject/class offering FK sebelum cutover |
| `teaching_attendances` | Jurnal/presensi mengajar + materi + rekap siswa | PK `id`; FK hanya kalender akademik | Pertahankan; tambah constraint setelah orphan audit |
| `teaching_class_student_counts` | Jumlah siswa per nama kelas/periode | **PK drift**; FK period | Jadikan snapshot; kelak derive dari enrollment |
| `teaching_class_activities` | Aktivitas kelas, kosong | **PK drift** | Jangan jadikan assignment tanpa desain ulang |
| `jadwal_mengajar` | Jadwal legacy tenaga_pendidik/mapel text | PK `id`; tanpa FK aktual | Bekukan dan map ke `teaching_schedules` |
| `academic_calendar_events` | Kalender sekolah | PK `id`; FK school | Pertahankan |
| `presensis` | Presensi staf, lokasi, face, izin, device | PK `id`; FK device/recorder; user/school bukan FK | Pertahankan kritis; jangan rewrite, tambah guard/index/FK bertahap |
| `presensi_settings` | Setting jam/geofence/status per sekolah | PK `id`; tanpa FK | Pertahankan; review uniqueness per scope |
| `izins` | Izin/cuti/tugas luar dan approval | PK `id`; tanpa FK aktual | Pertahankan; tambah school derivation dan policy |
| `holidays` | Hari libur | PK `id` | Pertahankan; kaitkan calendar/scope bila berbeda sekolah |
| `day_markers` | Penanda hari legacy | PK `id` | Audit overlap dengan holidays/calendar |
| `attendance_kiosk_logs` | Audit kiosk | PK `id`; FK school/operator/target/device | Pertahankan |
| `registered_attendance_devices` | Device kiosk terdaftar | PK `id`; FK school/registrar | Pertahankan |
| `biometric_profiles` | Template biometrik baru | PK `id`; FK user | Pertahankan sebagai target tunggal biometric identity |
| `face_diagnostics` | Audit diagnosis wajah | PK `id`; FK actor/user | Pertahankan dengan retention policy |
| `face_enrollment_sessions` | Sesi enrollment | PK `id`; FK user/operator/school | Pertahankan dengan retention policy |
| `face_enrollment_captures` | Capture enrollment besar | PK `id`; FK session | Pertahankan sementara; wajib lifecycle/retention karena 44+ MB |

#### Komunikasi dan perangkat

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `chats` | Pesan direct sender/receiver | PK `id`; tidak ada FK aktual | Jangan perluas langsung; target memakai conversation/participant/message |
| `notifications` | Notifikasi in-app dan payload FCM | PK `id`; tanpa FK aktual | Pertahankan; tambah category/scope dan retention |
| `push_device_tokens` | Token push per user/device | **PK drift**; tanpa FK aktual | Perbaiki integrity dan unique token/device setelah audit |

#### Keuangan siswa, sekolah, dan yayasan

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `spp_siswa_settings` | Setting SPP per sekolah/tahun ajaran | **PK drift**, tanpa FK | Pertahankan sebagai konfigurasi billing siswa |
| `spp_siswa_bills` | Tagihan siswa per periode/jenis | **PK drift**, tanpa FK | Pertahankan; tambah academic period dan FK additive |
| `spp_siswa_transactions` | Pembayaran/verifikasi tagihan siswa | **PK drift**, tanpa FK | Pertahankan; jangan campur dengan iuran sekolah/yayasan |
| `spp_siswa_virtual_accounts` | BNI VA siswa | PK `id`; tanpa FK aktual | Pertahankan; jaga idempotency dan callback security |
| `spp_operator_registrations` | Workflow operator SPP | **PK drift**, tanpa FK | Pertahankan jika aktif; hubungkan membership/permission |
| `tagihans` | Tagihan kepada sekolah/yayasan, bukan siswa | **PK drift**, tanpa FK | Pertahankan tetapi beri nama domain jelas |
| `payments` | Pembayaran tagihan sekolah/Midtrans | PK `id`; tanpa FK aktual | Pertahankan; rekonsiliasi dan unique order ID |
| `uppm_settings` | Setting iuran UPPM | **PK drift** | Pertahankan modul terpisah |
| `uppm_school_data` | Data dasar sekolah UPPM | **PK drift** | Pertahankan; sinkronkan school_id |
| `uppm_invoices` | Invoice UPPM | **PK drift** | Pertahankan |
| `uppm_payments` | Pembayaran UPPM | **PK drift** | Pertahankan |
| `uppm_payment_updates` | Update pembayaran UPPM | PK `id`; FK school | Pertahankan |

#### MGMP, DPS, SIMFONI, laporan

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `mgmp_groups` | Grup MGMP | PK `id`; tanpa FK | Pertahankan; tambah ownership |
| `mgmp_members` | Anggota MGMP | PK `id`; tanpa FK | Pertahankan; link user belum constrained |
| `mgmp_reports` | Laporan MGMP | PK `id`; tanpa FK | Pertahankan |
| `mgmp_attendances` | Presensi MGMP | PK `id`; tanpa FK | Pertahankan |
| `academica_proposals` | Proposal Academica/MGMP | PK `id`; tanpa FK | Pertahankan domain MGMP |
| `academica_reset_updates` | Update reset/revisi Academica | PK `id`; tanpa FK | Pertahankan |
| `academica_reset_update_files` | Lampiran update | PK `id`; tanpa FK | Tambah FK additive setelah orphan audit |
| `dps_members` | Anggota DPS | PK `id`; tanpa FK | Pertahankan; identitas sebaiknya membership pada user |
| `dps_account_passwords` | Penyimpanan password/account DPS | PK `id`; tanpa FK | Risiko tinggi; audit apakah plaintext/reversible dan migrasikan ke reset workflow |
| `simfoni` | Form/data SIMFONI | **PK drift**; tanpa FK | Pertahankan sebagai module record |
| `laporan_akhir_tahun_kepala_sekolah` | Laporan kepala sekolah | PK `id`; tanpa FK | Pertahankan; kepala sekolah harus berupa assignment, bukan string |
| `riwayat_kerja` | Riwayat kerja legacy | **PK drift** | Konsolidasikan sebagai staff profile history |
| `gtk_pendataan` | Pendataan GTK terhubung user | PK `id`; FK user | Pertahankan |

#### Talenta/instrumen

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `talenta` | Program/batch Talenta | **PK drift** | Pertahankan sebagai bounded module |
| `talenta_materi` | Materi Talenta | **PK drift** | Pertahankan |
| `talenta_pemateri` | Pemateri | **PK drift** | Pertahankan; prefer user membership |
| `talenta_fasilitator` | Fasilitator | **PK drift** | Pertahankan; prefer user membership |
| `talenta_peserta` | Peserta | **PK drift** | Pertahankan |
| `talenta_kelompoks` | Kelompok | **PK drift** | Pertahankan |
| `talenta_kelompok_peserta` | Pivot kelompok-peserta | **PK drift** | Tambah composite unique/FK setelah audit |
| `talenta_pemateri_materi` | Pivot pemateri-materi | **PK drift** | Tambah composite unique/FK |
| `talenta_fasilitator_materi` | Pivot fasilitator-materi | **PK drift** | Tambah composite unique/FK |
| `talenta_layanan_teknis` | Penilaian layanan teknis | **PK drift** | Pertahankan |
| `talenta_kehadiran_peserta` | Kehadiran peserta | **PK drift** | Pertahankan |
| `talenta_penilaian_trainer` | Penilaian trainer | **PK drift** | Pertahankan |
| `talenta_penilaian_fasilitator` | Penilaian fasilitator | **PK drift** | Pertahankan |
| `talenta_penilaian_teknis` | Penilaian teknis | **PK drift** | Pertahankan |
| `talenta_penilaian_peserta` | Penilaian peserta | **PK drift** | Pertahankan |
| `talenta_tugas_level_1` | Tugas Talenta versi tabel baru | **PK drift** | Audit duplikasi dengan tabel berikut |
| `tugas_talenta_level1` | Tugas Talenta versi legacy berdata | **PK drift** | Pertahankan sebagai source sampai mapping selesai |
| `tugas_nilai` | Nilai tugas Talenta | **PK drift** | Bukan gradebook siswa |
| `soals` | Soal Talenta/legacy | **PK drift** | Jangan dipakai sebagai quiz sekolah tanpa bounded context |
| `questions` | Pertanyaan instrumen | **PK drift** | Bukan quiz sekolah |
| `answers` | Jawaban instrumen | PK `id`; tanpa FK | Bukan jawaban quiz sekolah |
| `school_scores` | Skor instrumen sekolah | **PK drift** | Bukan nilai siswa |

#### AMI

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `ami_periods` | Periode AMI | PK `id`, tanpa FK | Pertahankan |
| `ami_period_schools` | Scope sekolah per periode | PK `id`; FK period/school | Pertahankan |
| `ami_instruments` | Instrumen per periode | PK `id`; FK period | Pertahankan |
| `ami_components` | Komponen instrumen | PK `id`; FK instrument | Pertahankan |
| `ami_items` | Item komponen | PK `id`; FK component | Pertahankan |
| `ami_indicators` | Indikator item | PK `id`; FK item | Pertahankan |
| `ami_indicator_criteria` | Kriteria indikator | PK `id`; FK indicator | Pertahankan |
| `ami_recommended_evidences` | Bukti rekomendasi | PK `id`; FK indicator | Pertahankan |
| `ami_rubrics` | Rubrik indikator | PK `id`; FK indicator | Pertahankan |
| `ami_assignments` | Assignment auditor-sekolah-periode | PK `id`; FK period/school/auditor/assigner | Pertahankan; pola scope yang baik |
| `ami_school_responses` | Respons sekolah | PK `id`; FK period-school/indicator/user | Pertahankan |
| `ami_evidences` | Bukti respons | PK `id`; FK response | Pertahankan |
| `ami_auditor_scores` | Skor auditor | PK `id`; FK assignment/indicator/auditor | Pertahankan |
| `ami_verifications` | Verifikasi skor | PK `id`; FK auditor score | Pertahankan |
| `ami_clarifications` | Klarifikasi | PK `id`; FK assignment/indicator/requester/target | Pertahankan |
| `ami_clarification_responses` | Respons klarifikasi | PK `id`; FK clarification/responder | Pertahankan |
| `ami_findings` | Temuan AMI | PK `id`; FK assignment/component/item/indicator | Pertahankan |
| `ami_followups` | Tindak lanjut | PK `id`; FK finding/school user | Pertahankan |
| `ami_followup_evidences` | Bukti tindak lanjut | PK `id`; FK followup | Pertahankan |
| `ami_followup_reviews` | Review tindak lanjut | PK `id`; FK followup/reviewer | Pertahankan |
| `ami_activity_logs` | Audit trail AMI | PK `id`; FK user | Pertahankan |

#### SK Yayasan, BPPPMNU, lain-lain

| Tabel | Fungsi dan data penting | PK/FK aktual | Keputusan/masalah |
|---|---|---|---|
| `sk_yayasan_templates` | Template SK | PK `id`; tanpa FK | Pertahankan |
| `sk_yayasan_requests` | Pengajuan/approval SK | PK `id`; FK employee/submitter/reviewer/school/template/import | Pertahankan |
| `sk_yayasan_documents` | Dokumen hasil dan numbering | PK `id`; FK request/template/generator/publisher/locker | Pertahankan |
| `sk_yayasan_employee_data` | Snapshot data pegawai | PK `id`; FK user | Pertahankan sebagai snapshot, jangan identity duplikat |
| `sk_yayasan_import_batches` | Batch import sekolah | PK `id`; FK school/uploader/reviewer | Pertahankan |
| `sk_yayasan_import_rows` | Baris staging/matching import | PK `id`; FK batch/matched user | Pertahankan |
| `picket_schedule_periods` | Periode jadwal piket | PK `id`; FK school/creator/updater | Pertahankan |
| `picket_schedule_submissions` | Pengajuan/approval piket | PK `id`; FK period/user/approver | Pertahankan |
| `bpppmnu_members` | Membership pengurus/anggota | PK `id`; FK user | Pertahankan; pola membership lebih baik daripada role tunggal |
| `bpppmnu_events` | Agenda kegiatan | PK `id`; FK creator | Pertahankan |
| `bpppmnu_event_invitations` | Undangan user-event | PK `id`; FK event/user | Pertahankan |
| `bpppmnu_event_qr_tokens` | QR token hashed | PK `id`; FK event | Pertahankan |
| `bpppmnu_event_attendances` | Kehadiran event | PK `id`; FK event/user dan composite invitation | Pertahankan |
| `data_sk` | Data SK legacy kosong | PK `id` | Bekukan; jangan gabung otomatis dengan modul SK baru |

## 4. Masalah arsitektur dan prioritas

### P0 — harus selesai sebelum modul akademik baru

1. Buat baseline migration yang dapat membangun schema baru secara deterministik; jangan menghapus histori production.
2. Backup dan schema/data profiling untuk 41 PK drift, orphan relation, duplicate identifier, dan dua schema bernama mirip.
3. Buat test database bootstrap hijau dan pisahkan failure konfigurasi dari regression bisnis.
4. Tambahkan authorization contract terpusat dan school context; semua endpoint resource memakai policy/ability.
5. Definisikan canonical identity link siswa tanpa membuat account baru saat setiap login.

### P1 — fondasi akademik

1. Organization/school membership dan multi-role assignments.
2. Academic years/terms, classes/rombels, subjects, staff profiles, student profiles, enrollments, teaching assignments.
3. Bridge additive dari kolom text/JSON existing ke FK baru; dual-read lalu dual-write terukur.

### P2 — maintainability dan operasi

1. Pecah controller besar per use case/domain tanpa mengubah route contract.
2. Pecah `web.php` menjadi route module files.
3. Tambah audit log authorization-sensitive, query scope tests, API contract tests, dan observability.
4. Pindahkan queue dari sync dan session dari file bila deployment lebih dari satu instance.

## 5. Target architecture

```text
Clients: Web Blade | Flutter | Capacitor | standalone modules
                         │
              NUIST Identity / SSO boundary
         session (same monolith) + OAuth/OIDC (external apps)
                         │
            Authorization + Tenant Context
      user → memberships → roles → permissions → school scope
                         │
      ┌──────────────────┼─────────────────────┐
      │                  │                     │
 Identity/Org       Academic Core         Existing Modules
 users              academic_periods      attendance
 people/profiles    class_groups          teaching journal
 organizations      subjects              PPDB/MGMP/Talenta
 schools            enrollments           SPP/UPPM/SK/AMI
 memberships        teaching_assignments  biometrics
      │                  │                     │
      └──────────────────┼─────────────────────┘
                         │
              Assignments / Quiz / Grades
                         │
              Billing / Communication
                         │
                   One MySQL database
```

Target tetap modular monolith. “Satu database” tidak berarti semua tabel boleh saling query tanpa boundary. Setiap module memiliki service/policy/repository/query object yang selalu menerima actor + school context.

### Target identity dan access

```text
users (credential/account)
  1 ── 0..1 person
person
  1 ── * organization_memberships
organization_membership
  * ── 1 organization/school
  * ── * roles (via role_assignments, scoped)
roles * ── * permissions

student_profiles.person_id
staff_profiles.person_id
guardian_profiles.person_id
student_guardians(student_id, guardian_id, relationship)
```

Role bukan pengganti scope. Contoh: user dapat menjadi `teacher` di sekolah A dan `foundation_viewer` di yayasan yang menaungi A/B. Permission dinilai dari action + resource + scope + status membership.

### Target academic ERD tekstual

```text
organizations 1─* schools
schools 1─* academic_years 1─* academic_terms
schools 1─* class_groups
subjects 1─* school_subjects

student_profiles 1─* student_enrollments *─1 class_groups
staff_profiles 1─* teaching_assignments
teaching_assignments *─1 class_groups
teaching_assignments *─1 school_subjects
teaching_assignments *─1 academic_term

teaching_assignments 1─* schedules 1─* teaching_journals
student_enrollments 1─* student_attendances

teaching_assignments 1─* assignments
assignments 1─* assignment_submissions *─1 student_enrollment

teaching_assignments 1─* quizzes 1─* quiz_questions
quiz_questions 1─* question_options
quizzes 1─* quiz_attempts 1─* quiz_answers

gradebooks 1─* grade_components
grade_entries *─1 student_enrollment
grade_entries → nullable source(type/id: assignment, quiz, exam, manual)

students 1─* student_bills 1─* student_payments
conversations 1─* conversation_participants
conversations 1─* messages 1─* message_attachments
```

Tidak disarankan polymorphic FK bebas untuk semua data inti. Untuk nilai, source type dapat dipakai sebagai bridge, tetapi `grade_entries` tetap menyimpan school/term/enrollment/subject yang tervalidasi.

## 6. Role/permission matrix target

Legenda: `V` view, `C` create, `U` update, `D` delete, `A` approve, `E` export, `M` monitoring. Semua hak dibatasi scope; `—` tidak diizinkan secara default.

| Fitur | Pengurus | Kepala Sekolah | Guru | Pegawai | Siswa |
|---|---|---|---|---|---|
| Dashboard agregat | V/M/E seluruh sekolah dalam yayasan | V/M sekolah sendiri | V data sendiri/kelas ajar | V data sendiri | V data sendiri |
| Master sekolah | V/M; U hanya permission khusus | V/U profil sekolah | V | V terbatas | V publik |
| User & membership | V/M; C/U/A sesuai mandat | V/C/U sekolah sendiri | V diri | V diri | V diri |
| GTK/pegawai | V/M/E | V/M/E sekolah sendiri | V kolega terbatas | V kolega terbatas | — |
| Siswa | V/M agregat sesuai kebijakan | V/M/E sekolah sendiri | V kelas ajar | V operasional terbatas | V/U profil terbatas |
| Academic period/class/subject | V/M | V/C/U/A sekolah sendiri | V assignment | V terbatas | V enrollment |
| Jadwal | V/M/E | V/C/U/A sekolah sendiri | V/C/U jadwal sendiri bila diberi | V jadwal sendiri | V kelas sendiri |
| Presensi staf | V/M/E | V/M/A/E sekolah sendiri | C/U/V data sendiri | C/U/V data sendiri | — |
| Izin staf | V/M/E | V/M/A sekolah sendiri | C/U/V sendiri | C/U/V sendiri | — |
| Jurnal mengajar | V/M/E | V/M/A/E sekolah sendiri | C/U/V jurnal sendiri | — | V ringkasan yang diizinkan |
| Presensi siswa | V/M/E agregat | V/M/A/E sekolah sendiri | C/U/V kelas ajar | C/U jika petugas | V sendiri |
| Tugas | V/M agregat | V/M sekolah sendiri | C/U/D/V kelas ajar | — | V/submit milik sendiri |
| Quiz | V/M agregat | V/M sekolah sendiri | C/U/D/V/publish kelas ajar | — | V/attempt milik sendiri |
| Nilai | V/M/E agregat sesuai kebijakan | V/M/A/E sekolah sendiri | C/U/V kelas ajar | — | V sendiri jika released |
| Tagihan siswa | V/M/E agregat terbatas | V/M/E sekolah sendiri | — | C/U/A/V sesuai tugas keuangan | V sendiri |
| Pembayaran | V/M/E agregat terbatas | V/M/E sekolah sendiri | — | C/U/A/V sesuai tugas keuangan | V riwayat sendiri |
| Pengumuman | V/C/U/A sesuai mandat | V/C/U/A sekolah sendiri | C kelas ajar jika diberi | C jika diberi | V target sendiri |
| Chat resmi | V audit hanya permission khusus | V/M percakapan sekolah sesuai policy | C/V thread terkait | C/V thread terkait | C/V thread sendiri |
| Konfigurasi/policy | V; U/A sangat terbatas | U setting sekolah yang didelegasikan | — | — | — |

Delete untuk record transaksi, presensi, nilai released, dan audit log sebaiknya diganti void/archive/correction workflow, bukan hard delete.

## 7. Migration plan yang direkomendasikan

### Phase 0 — Freeze, inventory, backup, dan reproducible schema

- Tujuan: membuat baseline aman sebelum feature work.
- Perubahan: dokumentasi schema canonical, dump terverifikasi, baseline migration/squash untuk instalasi baru, katalog 175 migration hilang, penyelesaian tiga migration pending secara terencana.
- Risiko: salah memilih schema `nuist-local` vs `nuist_local`; lock pada tabel besar.
- Exit gate: fresh test database dapat dibangun; backup restore drill berhasil; tidak ada mutation production.

### Phase 1 — Regression and security baseline

- Stabilkan 30 backend failure dan Flutter base URL test.
- Tambah characterization tests untuk login, mobile token, presensi, jurnal, PPDB, SPP, dan school isolation.
- Verifikasi env production: debug off, secure cookie, trusted hosts/proxies, Sanctum expiry, callback secret.
- Exit gate: suite hijau dan route/API contract snapshot tersedia.

### Phase 2 — Central identity bridge

- Tambah `people`/profile link atau minimal `student_profiles.user_id` nullable unique.
- Backfill link siswa ↔ user secara idempotent; hentikan pembuatan identitas bayangan saat login setelah coverage penuh.
- Pertahankan guard/login lama selama transisi.
- Exit gate: setiap siswa aktif mempunyai satu canonical user link, tanpa menghapus password legacy.

### Phase 3 — Role, permission, membership, school scope

- Tambah tabel additive roles/permissions/memberships/assignments dengan scope organization/school.
- Seed mapping dari `users.role`, `ketugasan`, membership BPPPMNU/MGMP/DPS.
- Tambah policy dan `SchoolContext`; legacy role tetap fallback selama dual authorization.
- Exit gate: test lintas sekolah negatif untuk setiap resource sensitif.

### Phase 4 — Academic core

- Tambah academic years/terms, subjects, class groups/rombels, student enrollments, staff profiles, teaching assignments.
- Backfill dari `tahun_pelajaran`, `siswa.kelas/jurusan`, `teaching_schedules.subject/class_names` memakai staging dan exception report.
- Jangan menghapus text lama; tambah FK nullable, dual-read/dual-write.

### Phase 5 — Stabilize attendance and teaching journal integration

- Kaitkan schedule/journal ke teaching assignment dan term.
- Presensi existing tetap source of truth; tambah school scope/FK/index secara online dan bertahap.
- Pisahkan staff attendance, teaching attendance, dan future student attendance secara eksplisit.

### Phase 6 — Assignment

- Bangun assignment, target, attachment, submission, grade/comment.
- Target selalu melalui teaching assignment/class enrollment, bukan input nama kelas.
- Object storage + antivirus/size/type policy untuk lampiran.

### Phase 7 — Quiz/question bank

- Quiz, question bank/version, question types, options, attempts, answers, timing, publish/result policy.
- Jangan reuse tabel `questions/answers/soals` existing karena bounded context berbeda.
- Autosave/idempotent submit dan server-authoritative clock wajib.

### Phase 8 — Gradebook

- Gradebook, component, weighting, entries, release/lock/correction/audit.
- Assignment/quiz menjadi source; nilai manual tetap didukung.

### Phase 9 — Student attendance and guardian access

- Enrollment-based attendance; guardian identity/membership; read-only student/guardian access dahulu.
- Jangan mencampur record siswa ke `presensis` staf tanpa discriminator dan contract yang jelas.

### Phase 10 — Billing consolidation facade

- Pertahankan tabel SPP existing, tambahkan academic period/reference dan service facade.
- `tagihans/payments` sekolah/yayasan tetap bounded context berbeda.
- Payment gateway baru bukan scope sampai ledger/reconciliation matang.

### Phase 11 — Official communication

- Conversation, participants, messages, attachments, read receipts, announcement channels, retention/moderation.
- Migrasikan `chats` sederhana jika mapping actor valid; jangan membuka chat bebas secara default.

### Phase 12 — Subdomain/SSO modernization and optional extraction

- Same monolith: shared central login/session dengan cookie yang benar.
- Standalone (`cbt` atau aplikasi lain): OIDC authorization code + PKCE, short-lived access token, central permission claims/minimal introspection.
- Ekstraksi service hanya bila scaling/ownership memerlukan, bukan untuk kosmetik arsitektur.

## 8. Risk analysis

| Risiko | Level | Mitigasi wajib |
|---|---|---|
| Login siswa putus saat deduplikasi | Kritis | Link table, dual-read, token/session compatibility, rollback flag |
| Kebocoran data antar sekolah | Kritis | Deny-by-default policy, school context, negative tests, query review |
| Migration tidak reproducible | Kritis | Baseline schema, restore drill, migration ledger, fresh install CI |
| PK/FK diperbaiki pada data orphan/duplikat | Kritis | Profiling, staging, online index, exception table, bukan langsung constraint |
| Presensi 65k+ record terganggu | Tinggi | Tidak rewrite; additive index/FK, off-peak deploy, query plan |
| Face/biometric privacy | Tinggi | Encryption, access audit, retention/delete policy, minimisasi capture |
| Session lintas subdomain gagal | Tinggi | Shared store, cookie domain/security test, fallback login |
| API mobile lama rusak | Tinggi | Versioning, contract tests, deprecation window, remote config |
| Role lama berubah makna | Tinggi | Seed mapping + dual authorization + per-role canary |
| Nilai/quiz kehilangan integritas | Tinggi | Versioning, lock/release, audit trail, idempotent submission |
| Payment double charge/reconcile | Tinggi | Unique provider reference, idempotency key, callback verification |
| Controller refactor menimbulkan regression | Sedang-tinggi | Characterization test dan strangler pattern per use case |
| File session/queue sync tidak scalable | Sedang | Redis/database session dan queue worker setelah readiness audit |

## 9. Backward compatibility strategy

1. Semua perubahan schema awal bersifat additive dan nullable; tidak rename/drop kolom legacy.
2. Legacy route name, URI, request/response JSON, dan mobile deep link dipertahankan.
3. Gunakan expand → backfill → dual-read → dual-write → verify → cutover → contract.
4. Tambah adapter yang menerjemahkan `users.role` ke permission baru; jangan mengganti seluruh check sekaligus.
5. School scope baru dimulai dalam report-only/audit mode untuk role global, lalu enforce per module setelah hasil dibandingkan.
6. Backfill memakai batch, checkpoint, idempotency key, dan exception report.
7. Feature flag per sekolah/module untuk assignment, quiz, grade, dan identity cutover.
8. Setiap phase memiliki backup, rollback aplikasi, dan data rollback/forward-fix plan.
9. Tidak hard-delete data legacy sampai minimal satu siklus akademik dan rekonsiliasi selesai.
10. Contract test untuk Flutter/Capacitor harus menjadi deployment gate.

## 10. Hasil verifikasi audit

- `php artisan route:list --json`: berhasil, 1.325 route, tanpa duplicate route name yang terdeteksi.
- Schema read-only: 129 tabel aktif, 92 FK constraint, 301 migration tercatat.
- Repository migration: 129 file; 175 applied migration hilang dari repo; 3 pending.
- Backend test: **72 passed, 30 failed**. Penyebab dominan adalah migration pertama mencoba mengubah tabel Talenta yang creation migration-nya hilang; ada juga drift fixture/schema BPPPMNU dan satu expectation UI.
- Flutter test: **1 failed** karena expected production API URL tetapi konfigurasi aktual memakai `http://10.219.186.244:8000/api`.
- Git worktree tetap tidak diubah selain dokumen audit ini; tidak ada migration/data mutation.

## 11. Keputusan sebelum implementasi

Urutan yang aman bukan langsung “Phase 1 Identity + Role + School” sebagaimana contoh awal. Phase 0 wajib mendahului semuanya karena schema saat ini tidak reproducible dan test belum hijau. Setelah Phase 0–3 selesai, barulah kelas/mapel/enrollment menjadi fondasi tugas, quiz, dan nilai.

Implementasi pertama yang direkomendasikan setelah persetujuan audit adalah **Phase 0: reproducible schema dan regression baseline**, bukan pembuatan tabel tugas/quiz.
