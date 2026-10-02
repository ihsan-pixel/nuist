# Audit Ulang NUIST Berdasarkan Hosting

Tanggal: 2 Oktober 2026  
Sumber kebenaran: deployment hosting melalui SSH dan database production `u167292830_nuist`.  
Metode: read-only; tidak menjalankan migration, test, cache clear, deploy, atau perubahan data/file di hosting.

## Kesimpulan utama

Production lebih sehat daripada database lokal yang diaudit sebelumnya. Semua 129 tabel production mempunyai struktur primary key yang konsisten dan terdapat 198 foreign key. Temuan lama tentang 41 tabel tanpa primary key **tidak berlaku untuk production**.

Namun, source repository masih tidak dapat membangun database production dari nol: production mencatat 304 migration, deployment hanya menyimpan 129 file migration, dan 175 migration yang telah dijalankan tidak lagi tersedia. Ini tetap blocker pertama karena disaster recovery, CI, onboarding environment, dan deployment baru tidak reproducible.

Arsitektur yang direkomendasikan tetap modular monolith untuk aplikasi utama, dengan AMI dan CBT sebagai aplikasi standalone yang diintegrasikan melalui central identity/SSO. Jangan menggabungkan database AMI/CBT ke database utama secara langsung sebelum identity dan authorization contract tersedia.

## 1. Fakta deployment hosting

| Aplikasi | Lokasi deployment | Framework | Database | Tabel | User lokal | Session | Kondisi integrasi |
|---|---|---|---|---:|---:|---|---|
| NUIST utama | `domains/nuist.id/nuist` | Laravel 12.49, PHP 8.3.33 | `u167292830_nuist` | 129 | 1.072 | file | Web/session + Sanctum API |
| AMI | `domains/ami.nuist.id/nuist-ami` | Laravel 13.26, PHP 8.3.33 | `u167292830_nuist_ami` | 23 | 4 | database | Auth/database mandiri; belum ditemukan SSO |
| CBT | `domains/cbt.nuist.id/nuist-cbt` | Laravel 13.29, PHP 8.3.33 | `u167292830_nuist_cbt` | 17 | 14 | database | Auth/database mandiri; belum ditemukan SSO |

Aplikasi utama production berada pada commit yang sama dengan workspace lokal: `aca274ca93861452d66e7fdc40f7c54a963f316f`. Deployment memiliki working tree kotor pada file runtime/binary dan compiled views. Audit tidak mengubah atau membersihkannya karena ownership perubahan tersebut belum diketahui.

### Koreksi terhadap audit lokal

| Area | Audit database lokal | Fakta hosting | Kesimpulan baru |
|---|---:|---:|---|
| Tabel utama | 129 | 129 | Nama tabel sinkron |
| Foreign key | 92 | 198 | Lokal tertinggal/rusak; production jauh lebih lengkap |
| Tabel dengan `id` bukan PK | 41 | 0 | Temuan PK drift dicabut untuk production |
| Migration tercatat | 301 | 304 | Tiga migration lokal yang pending sudah applied di hosting |
| File migration | 129 | 129 | Sama, tetapi histori lama hilang |
| Applied migration tanpa file | 175 | 175 | Masalah reproducibility tetap nyata |
| Pending migration | 3 | 0 | Production up-to-date terhadap file yang tersedia |
| AMI/CBT | Tidak tampak di repo utama | Aplikasi dan DB standalone | Perlu SSO, bukan asumsi satu codebase |

## 2. Data production aktual

Angka berikut memakai `COUNT(*)`, bukan estimasi `information_schema`:

| Data | Jumlah |
|---|---:|
| Yayasan | 1 |
| Sekolah | 36 |
| User utama | 1.072 |
| Siswa | 3.795 |
| Presensi | sekitar 105.080 |
| Jurnal/presensi mengajar | sekitar 26.202 |
| Jadwal mengajar | sekitar 4.339 |
| Izin | sekitar 1.841 |
| Biometric profile | sekitar 4.435 |
| Notifikasi | sekitar 72.743 |

Angka bertanda “sekitar” berasal dari statistik engine MySQL dan harus dihitung ulang dengan `COUNT(*)` bila dipakai untuk rekonsiliasi bisnis.

### Role production

| Role | Jumlah | Tanpa `madrasah_id` | Interpretasi |
|---|---:|---:|---|
| `tenaga_pendidik` | 852 | 48 | Guru dan pegawai masih tercampur |
| `dps` | 112 | 0 | Role modul DPS |
| `admin` | 36 | 1 | Hampir satu admin per sekolah; satu record perlu audit |
| `pengurus_bpppmnu` | 21 | 18 | Banyak yang memang berscope organisasi, bukan sekolah |
| `mgmp` | 18 | 18 | Scope domain MGMP |
| `fasilitator` | 11 | 11 | Scope Talenta |
| `pemateri` | 9 | 9 | Scope Talenta |
| `pengurus` | 6 | 5 | Scope yayasan/lintas sekolah |
| `admin_yayasan` | 2 | 1 | Scope yayasan |
| role kosong | 2 | 0 | Data invalid yang harus diklasifikasi |
| `siswa` | 2 | 0 | Shadow account siswa yang sudah pernah login/sync |
| `super_admin` | 1 | 1 | Global |

Ada 39 user bertanda `ketugasan = kepala madrasah/sekolah` pada 35 sekolah. Ini menunjukkan kepala sekolah belum berupa assignment yang unik dan berperiode; sedikitnya ada satu sekolah tanpa kepala yang cocok atau beberapa sekolah mempunyai lebih dari satu record aktif. Jangan langsung menghapus duplikat—perlu cek periode/jabatan aktif.

### Kualitas identity production

- Tidak ditemukan duplicate `users.email`.
- Tidak ditemukan duplicate `users.nuist_id`.
- Tidak ada user tanpa `nuist_id`.
- Tidak ditemukan duplicate `siswa.nisn` untuk nilai yang terisi.
- Ada 30 siswa tanpa NISN.
- 3.764 siswa memiliki password; 3.765 memiliki email NUIST.
- Hanya dua shadow user ber-role `siswa`, sehingga sinkronisasi `siswa → users` terjadi saat login, bukan backfill menyeluruh.

Unique constraint production sudah benar untuk `users.email`, `users.nuist_id`, `siswa.nisn`, dan `siswa.email_nuist`. Risiko identity bukan lagi duplicate identifier massal, melainkan dua sumber credential/profile dan pembuatan shadow user saat login.

## 3. Database production

### Integrity yang sudah baik

- Semua tabel dengan identifier `id` memiliki primary key dan auto-increment yang sesuai.
- `users.madrasah_id`, `madrasah_id_tambahan`, dan `status_kepegawaian_id` memiliki FK.
- `madrasahs.yayasan_id` memiliki FK.
- `siswa.madrasah_id` memiliki FK.
- Jadwal memiliki FK ke sekolah, guru, creator, dan periode.
- Jurnal mengajar memiliki FK ke jadwal, user, dan kalender akademik.
- Presensi memiliki FK ke user, sekolah, approver, status kepegawaian, device, dan recorder.
- Izin memiliki FK ke user dan approver.
- Chat dan notifikasi memiliki FK ke user.
- Tagihan siswa dan transaksi memiliki FK lengkap ke siswa, sekolah, setting, bill, dan creator.
- Tagihan sekolah/payment mempunyai FK ke sekolah dan tagihan.

### Tabel tanpa foreign key

Ada 32 tabel tanpa FK. Sebagian memang root/system table dan tidak bermasalah (`yayasans`, `migrations`, `failed_jobs`, `sessions`, `app_settings`, `status_kepegawaian`). Yang tetap perlu diperiksa adalah:

- PPDB: `ppdb_pendaftar`, `ppdb_settings`, dan `ppdb_jalur` belum seluruhnya constrained.
- Akademik legacy: `tahun_pelajaran` masih berdiri sendiri.
- SPP: `spp_siswa_virtual_accounts` belum memiliki FK database walaupun membawa ID relasi.
- Kepala sekolah: `laporan_akhir_tahun_kepala_sekolah` belum constrained.
- Talenta legacy: beberapa master/pivot masih tanpa FK.
- `questions` dan `soals` adalah domain instrumen/Talenta, bukan question bank quiz siswa.

### Inventaris domain dan keputusan

| Domain | Tabel utama production | Keputusan |
|---|---|---|
| Identity/organization | `users`, `yayasans`, `madrasahs`, `status_kepegawaian` | Pertahankan; tambah membership, multi-role, dan scoped permission secara additive |
| Profil GTK | `users`, `gtk_pendataan`, `sk_yayasan_employee_data`, legacy `tenaga_pendidiks` | `users` tetap account root; pisahkan profile dan snapshot, bekukan legacy |
| Siswa | `siswa` | Pertahankan master data; buat FK langsung ke canonical user/person |
| Presensi | `presensis`, `izins`, settings, kiosk/device, biometric/face tables | Pertahankan tanpa rewrite; ini domain production kritis |
| Jurnal/jadwal | `teaching_schedule_periods`, `teaching_schedules`, `teaching_attendances`, calendar | Pertahankan; bridge text mapel/kelas ke academic core |
| PPDB | `ppdb_*` | Pertahankan sebagai admission bounded context |
| SPP siswa | `spp_siswa_*` | Pertahankan; jangan gabung dengan tagihan yayasan |
| Keuangan sekolah/yayasan | `tagihans`, `payments`, `uppm_*` | Pertahankan sebagai bounded context berbeda |
| MGMP/Academica | `mgmp_*`, `academica_*` | Pertahankan |
| Talenta | `talenta*`, `tugas_talenta*`, `tugas_nilai`, `soals` | Pertahankan; jangan reuse untuk assignment/quiz siswa |
| AMI di database utama | `ami_*` | Struktur ada tetapi hosting juga memiliki aplikasi/database AMI standalone; tentukan source of truth sebelum perubahan |
| SK Yayasan | `sk_yayasan_*` | Pertahankan; controller perlu dipecah bertahap |
| BPPPMNU | `bpppmnu_*` | Pertahankan; model membership adalah pola baik |
| Komunikasi | `chats`, `notifications`, `push_device_tokens` | Chat existing terlalu sederhana untuk komunikasi sekolah |

### Masalah schema yang tetap ada

1. Kelas berada di `siswa.kelas` dan jadwal `class_name/class_names`; belum ada class group/enrollment.
2. Mata pelajaran masih `teaching_schedules.subject` text.
3. Tahun ajaran ada sebagai `tahun_pelajaran` sederhana dan `teaching_schedule_periods.school_year`; belum satu canonical academic period.
4. Guru/pegawai/kepala sekolah masih ditentukan melalui role + string profil, bukan membership/assignment berperiode.
5. Student credential masih ada di tabel `siswa`, lalu disalin ke `users` ketika mobile login.
6. Belum ada assignment siswa, quiz kelas, submission/attempt, atau gradebook akademik.
7. Terdapat representasi AMI ganda: tabel AMI di database utama dan aplikasi/database AMI standalone. Ownership dan sinkronisasi belum eksplisit.

## 4. Authentication dan authorization production

### Aplikasi utama

- Web staf memakai session/provider `users`.
- Konfigurasi menyediakan guard siswa/provider `siswa`, tetapi API mobile siswa memverifikasi record `siswa`, lalu membuat/memperbarui `users` shadow account dan menerbitkan token Sanctum.
- API mobile memakai bearer token Sanctum.
- Token dibuat tanpa module-specific abilities; role enforcement banyak dilakukan di controller.
- Role tetap satu enum pada `users`, bukan multi-role.
- Kepala sekolah adalah `tenaga_pendidik` dengan `ketugasan` string.
- School scope diperiksa manual di banyak controller dan belum menjadi invariant global.

### AMI dan CBT

AMI dan CBT memiliki database, tabel users, dan session sendiri. Tidak ditemukan implementasi OAuth/OIDC/SSO atau Sanctum pada scan konfigurasi/source deployment. Maka target “satu sumber pengguna dan satu autentikasi” belum berlaku untuk dua aplikasi tersebut.

Rekomendasi:

1. NUIST utama menjadi Identity Provider atau memakai IdP standar, bukan berbagi tabel password lintas database.
2. AMI dan CBT menjadi OAuth/OIDC clients menggunakan Authorization Code + PKCE.
3. Gunakan immutable central subject ID; AMI/CBT boleh menyimpan local profile/membership tetapi tidak password pusat.
4. Claims minimal: subject, organization/school memberships, role assignment identifiers; permission sensitif tetap diverifikasi server-side.
5. Migrasi akun lokal memakai account linking yang diaudit, bukan pencocokan nama.

## 5. Current architecture

```text
nuist.id / admin / sekolah / presensi / keuangan / spmb / mgmp
                │
        Laravel 12 monolith
        session + Sanctum
                │
       u167292830_nuist
     users + siswa credentials

ami.nuist.id                 cbt.nuist.id
Laravel 13                   Laravel 13
DB + users sendiri           DB + users sendiri
session sendiri              session sendiri
        \                     /
         belum ada central SSO
```

Subdomain `admin`, `sekolah`, `presensi`, `keuangan`, `spmb`, dan `mgmp` adalah presentation boundary dari aplikasi utama. AMI dan CBT adalah aplikasi standalone sesungguhnya.

## 6. Target architecture

```text
                    Central Identity / OIDC
                  users + people + memberships
                              │
          ┌───────────────────┼───────────────────┐
          │                   │                   │
   NUIST Main Monolith      AMI Client          CBT Client
   central operational DB   own domain DB       own domain DB
          │
   Authorization + School Context
          │
   Academic Core
   period ─ class ─ subject ─ enrollment ─ teaching assignment
          │
   attendance / journal / assignment / quiz / grades / billing
```

Tidak perlu memaksa AMI dan CBT memakai satu database fisik untuk mencapai satu identity. “Satu sumber user” dicapai melalui central subject dan SSO; data domain tetap dimiliki aplikasinya.

## 7. Role matrix target

Legenda: `V` view, `C` create, `U` update, `A` approve, `E` export, `M` monitor. Semua dibatasi organization/school/class/self scope.

| Fitur | Pengurus | Kepala Sekolah | Guru | Pegawai | Siswa |
|---|---|---|---|---|---|
| Dashboard yayasan/sekolah | V/M/E scoped | V/M/E sekolah | V sendiri/kelas | V sendiri | V sendiri |
| User/membership | V/M; C/U/A jika diberi | V/C/U sekolah | V diri | V diri | V diri |
| GTK/pegawai | V/M/E | V/M/E sekolah | V terbatas | V terbatas | — |
| Siswa/kelas | V/M agregat | V/M/E sekolah | V kelas ajar | V operasional | V kelas/diri |
| Academic period/mapel | V/M | V/C/U/A | V assignment | V terbatas | V enrollment |
| Presensi staf | V/M/E | V/M/A/E | C/U/V sendiri | C/U/V sendiri | — |
| Izin staf | V/M/E | V/M/A | C/U/V sendiri | C/U/V sendiri | — |
| Jurnal mengajar | V/M/E | V/M/A/E | C/U/V sendiri | — | V ringkasan diizinkan |
| Presensi siswa | V/M/E agregat | V/M/A/E | C/U/V kelas | C/U jika petugas | V sendiri |
| Tugas | V/M agregat | V/M | C/U/publish kelas | — | V/submit sendiri |
| Quiz | V/M agregat | V/M | C/U/publish kelas | — | V/attempt sendiri |
| Nilai | V/M/E terbatas | V/M/A/E | C/U kelas | — | V released sendiri |
| Tagihan siswa | V/M/E terbatas | V/M/E | — | C/U/A sesuai tugas | V sendiri |
| Chat/pengumuman | V audit khusus | V/M sekolah | C/V relasi kelas | C/V penugasan | C/V thread sendiri |

## 8. Urutan implementasi revisi

### Phase 0 — Production baseline dan disaster recovery

- Ambil schema-only dump production dan simpan sebagai artifact terkontrol/terenkripsi.
- Pulihkan 175 migration historis dari repository/backup bila tersedia, atau buat schema baseline resmi untuk instalasi baru.
- Dokumentasikan 304 migration ledger dan checksum schema production.
- Buat clone/staging dari backup; semua test dan migration dry-run dilakukan di staging, bukan hosting production.
- Reconcile database lokal agar tidak lagi dipakai sebagai bukti production.

### Phase 1 — Regression baseline

- Perbaiki fresh database test yang saat ini gagal akibat migration creation yang hilang.
- Jadikan login, school isolation, presensi, jurnal, PPDB, SPP, dan API contract sebagai test gate.
- Perbaiki konfigurasi Flutter base URL melalui environment/flavor, bukan hardcode production/local.

### Phase 2 — Identity bridge dan SSO design

- Tambah canonical link `siswa ↔ user/person` yang eksplisit dan unique.
- Backfill 3.795 siswa secara batch; 30 tanpa NISN masuk exception workflow.
- Pertahankan login lama selama dual-read.
- Tetapkan OIDC contract untuk AMI/CBT dan account-linking plan.

### Phase 3 — Membership, role, permission, scope

- Tambah organization/school memberships, roles, permissions, scoped assignments.
- Map role enum existing dan `ketugasan` tanpa menghapusnya.
- Modelkan kepala sekolah sebagai school assignment berperiode dan unique-active constraint.
- Tambah central `SchoolContext` serta policy deny-by-default.

### Phase 4 — Academic core

- Academic year/term, subject, class group/rombel, student enrollment, staff profile, teaching assignment.
- Backfill text/JSON existing dengan staging, confidence score, dan exception report.
- Tambah FK nullable dan dual-write; jangan drop kolom lama.

### Phase 5 — Integrasi presensi dan jurnal

- Hubungkan jadwal/jurnal ke teaching assignment dan term.
- Jangan rewrite 105 ribu presensi; lakukan additive index/FK/backfill bertahap.
- Pisahkan staff attendance, teaching attendance, dan student attendance.

### Phase 6–10 — Modul baru

1. Assignment + submission.
2. Quiz + versioned question bank + attempts.
3. Gradebook + release/correction audit.
4. Student attendance + guardian identity.
5. Billing facade dan official communication.

### Phase 11 — SSO rollout AMI/CBT

- Canary per aplikasi dan kelompok user.
- Local login tetap tersedia sebagai break-glass selama masa migrasi terbatas.
- Setelah account-linking tervalidasi, password lokal dinonaktifkan bertahap.

## 9. Risiko production

| Risiko | Level | Mitigasi |
|---|---|---|
| Tidak bisa membangun DB dari migration repo | Kritis | Baseline schema + restore drill + migration archive |
| Kebocoran lintas sekolah | Kritis | Policy terpusat, school context, negative test |
| Siswa terkunci saat identity cutover | Kritis | Explicit link, dual-read, feature flag, rollback |
| Salah menggabungkan AMI utama vs standalone | Kritis | Tetapkan source of truth dan ownership sebelum coding |
| Gangguan presensi production | Tinggi | Tidak rewrite; staging clone, online changes, canary |
| SSO account takeover karena linking email/nama | Tinggi | Immutable subject, admin verification, audit log |
| Kepala sekolah ganda/tidak aktif | Tinggi | Assignment berperiode + validation, bukan delete langsung |
| Mobile API lama rusak | Tinggi | Versioned API, contract test, deprecation window |
| Payment/VA inconsistency | Tinggi | Idempotency, unique provider reference, reconciliation |
| Biometric privacy/storage | Tinggi | Retention, encryption, access log, minimisasi capture |

## 10. Backward compatibility

1. Perubahan schema additive dan nullable terlebih dahulu.
2. Role enum, `ketugasan`, route, payload API, dan kolom kelas/mapel lama tetap hidup selama transisi.
3. Gunakan expand → backfill → dual-read → dual-write → verify → cutover → contract.
4. Feature flag per sekolah dan per modul.
5. Semua backfill idempotent, berbatch, mempunyai checkpoint dan exception report.
6. Tidak menjalankan perubahan pertama kali di hosting; gunakan clone production yang disanitasi.
7. Tidak hard-delete data legacy setidaknya sampai satu siklus akademik dan rekonsiliasi selesai.
8. AMI/CBT tidak berbagi password atau session table; transisi melalui OIDC dan account linking.

## 11. Keputusan final audit ulang

Prioritas pertama bukan memperbaiki PK/FK production—production sudah jauh lebih baik daripada lokal. Prioritas pertama adalah membuat schema production reproducible dan testable. Setelah Phase 0–3, barulah academic core serta tugas/quiz/nilai dibangun.

Urutan persetujuan yang disarankan:

1. Setujui Phase 0 read-only backup/schema baseline plan.
2. Buat staging clone dan validasi restore.
3. Pulihkan baseline regression.
4. Ajukan desain identity/membership/permission terperinci sebelum satu migration baru dibuat.
