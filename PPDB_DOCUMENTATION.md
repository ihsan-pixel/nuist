# 📋 Dokumentasi Modul PPDB NUIST 2025

## 🎯 Ringkasan
Modul PPDB (Penerimaan Peserta Didik Baru) NUIST 2025 adalah sistem pendaftaran online yang memungkinkan calon peserta didik mendaftar ke berbagai sekolah/madrasah di bawah naungan NU & NUIST.

---

## 📁 Struktur File & Folder

### Controllers
```
app/Http/Controllers/PPDB/
├── PPDBController.php          # Halaman umum PPDB
├── PendaftarController.php     # Form & proses pendaftaran
├── AdminSekolahController.php  # Dashboard admin sekolah
└── AdminLPController.php       # Dashboard admin LP. Ma'arif
```

### Models
```
app/Models/
├── PPDBSetting.php      # Konfigurasi PPDB per sekolah
├── PPDBPendaftar.php    # Data calon peserta didik
├── PPDBJalur.php        # Jalur pendaftaran (Prestasi, Reguler, dll)
└── PPDBVerifikasi.php   # Riwayat verifikasi data
```

### Views
```
resources/views/ppdb/
├── index.blade.php              # Halaman daftar sekolah
├── sekolah.blade.php            # Detail sekolah & jadwal PPDB
├── daftar.blade.php             # Form pendaftaran
└── dashboard/
    ├── sekolah.blade.php        # Dashboard admin sekolah
    ├── verifikasi.blade.php     # Halaman verifikasi data
    ├── seleksi.blade.php        # Halaman seleksi & input nilai
    ├── lp.blade.php             # Dashboard admin LP
    ├── lp-detail.blade.php      # Detail PPDB per sekolah
    ├── pendaftar.blade.php      # Daftar pendaftar
    └── export.blade.php         # Export data
```

### Migrations
```
database/migrations/
├── create_ppdb_settings_table.php         # Konfigurasi PPDB
├── create_ppdb_pendaftars_table.php       # Data pendaftar
├── create_ppdb_jalurs_table.php           # Jalur pendaftaran
└── create_ppdb_verifikasis_table.php      # Log verifikasi
```

### Routes
```
routes/web.php
# Public Routes
GET  /ppdb                                  # Daftar sekolah
GET  /ppdb/{slug}                          # Detail sekolah
GET  /ppdb/{slug}/daftar                   # Form pendaftaran
POST /ppdb/{slug}/daftar                   # Submit pendaftaran

# Admin Sekolah Routes (requires auth + role:admin_sekolah)
GET  /ppdb/sekolah/dashboard               # Dashboard
GET  /ppdb/sekolah/verifikasi              # Verifikasi data
POST /ppdb/sekolah/verifikasi/{id}         # Update verifikasi
GET  /ppdb/sekolah/seleksi                 # Halaman seleksi
POST /ppdb/sekolah/seleksi/{id}            # Update seleksi
GET  /ppdb/sekolah/export                  # Export data

# Admin LP Routes (requires auth + role:admin_lp)
GET  /ppdb/lp/dashboard                    # Dashboard LP
GET  /ppdb/lp/{slug}                       # Detail sekolah
```

---

## 🚀 Panduan Setup

### 1. Jalankan Migrations
```bash
php artisan migrate
```

### 2. Populate Data Testing (Opsional)
```bash
php artisan db:seed --class=PPDBSeeder
```

### 3. Verifikasi Database
Pastikan tabel-tabel berikut ada di database:
- `ppdb_settings`
- `ppdb_pendaftars`
- `ppdb_jalurs`
- `ppdb_verifikasis`

---

## 📊 Model Relationships

### PPDBSetting
```
- hasMany: PPDBPendaftar (pendaftars)
- hasMany: PPDBJalur (jalurs)
- hasMany: PPDBVerifikasi (verifikasis)
- belongsTo: Madrasah (sekolah)
```

### PPDBPendaftar
```
- belongsTo: PPDBSetting (ppdbSetting)
- belongsTo: PPDBJalur (jalur)
- belongsTo: User (verifikator, penyeleksi)
- hasMany: PPDBVerifikasi (verifikasis)

Scopes:
- pending()      # Status pending
- verifikasi()   # Status verifikasi
- lulus()        # Status lulus
- tidakLulus()   # Status tidak_lulus
```

### PPDBJalur
```
- belongsTo: PPDBSetting (ppdbSetting)
- hasMany: PPDBPendaftar (pendaftars)
```

### PPDBVerifikasi
```
- belongsTo: PPDBSetting (ppdbSetting)
- belongsTo: PPDBPendaftar (pendaftar)
- belongsTo: User (verifikator)
```

---

## 🔄 Alur Pendaftaran

### Tahap 1: Pendaftaran (Calon Peserta)
1. Calon peserta membuka `/ppdb`
2. Memilih sekolah yang ingin didaftar
3. Mengisi form pendaftaran lengkap
4. Upload berkas (KK, Ijazah)
5. Submit → Status: **PENDING**

### Tahap 2: Verifikasi (Admin Sekolah)
1. Admin sekolah login ke dashboard
2. Cek data pendaftar di halaman "Verifikasi"
3. Verifikasi dokumen dan data
4. Update status → **VERIFIKASI** atau **TIDAK_LULUS**

### Tahap 3: Seleksi (Admin Sekolah)
1. Admin sekolah membuka halaman "Seleksi"
2. Input nilai/ranking untuk setiap pendaftar
3. Update status → **LULUS** atau **TIDAK_LULUS**
4. Unduh hasil seleksi

### Tahap 4: Monitoring (Admin LP)
1. Admin LP bisa melihat dashboard terpadu
2. Monitor progress di semua sekolah
3. Akses laporan per sekolah

---

## 📝 Database Schema

### ppdb_settings
```sql
- id (bigint)
- sekolah_id (bigint) - FK
- slug (string) - unique URL slug
- nama_sekolah (string)
- tahun (int)
- status (enum: buka/tutup)
- jadwal_buka (datetime)
- jadwal_tutup (datetime)
- timestamps
```

### ppdb_pendaftars
```sql
- id (bigint)
- ppdb_setting_id (bigint) - FK
- ppdb_jalur_id (bigint) - FK
- nomor_pendaftaran (string) - unique
- nama_lengkap (string)
- nisn (string) - unique
- asal_sekolah (string)
- jurusan_pilihan (string)
- berkas_kk (string) - file path
- berkas_ijazah (string) - file path
- status (enum: pending/verifikasi/lulus/tidak_lulus)
- nilai (decimal) - nullable
- ranking (int) - nullable
- catatan_verifikasi (text) - nullable
- diverifikasi_oleh (bigint) - FK to users
- diverifikasi_tanggal (datetime) - nullable
- diseleksi_oleh (bigint) - FK to users
- diseleksi_tanggal (datetime) - nullable
- timestamps
```

### ppdb_jalurs
```sql
- id (bigint)
- ppdb_setting_id (bigint) - FK
- nama_jalur (string)
- keterangan (text) - nullable
- urutan (int)
- timestamps
```

### ppdb_verifikasis
```sql
- id (bigint)
- ppdb_setting_id (bigint) - FK
- ppdb_pendaftar_id (bigint) - FK
- status (enum: verified/rejected)
- catatan (text) - nullable
- diverifikasi_oleh (bigint) - FK to users
- diverifikasi_tanggal (datetime)
- timestamps
```

---

## 🔐 Authorization & Roles

### Public Access
- `/ppdb` - Semua orang bisa akses
- `/ppdb/{slug}` - Semua orang bisa akses
- `/ppdb/{slug}/daftar` - Semua orang bisa isi form

### Admin Sekolah (role: admin_sekolah)
- Akses hanya PPDB sekolahnya sendiri
- Bisa verifikasi data pendaftar
- Bisa input nilai & seleksi
- Bisa export data

### Admin LP (role: admin_lp)
- Akses semua PPDB sekolah
- Hanya monitoring (read-only)
- Akses laporan terpadu

---

## 🎨 UI/UX Features

### Frontend
- ✅ Responsive design (mobile-first)
- ✅ Tailwind CSS styling
- ✅ Countdown timer untuk jadwal pendaftaran
- ✅ Progress bar di form pendaftaran
- ✅ File upload dengan drag-drop
- ✅ Form validation (client & server)

### Admin Dashboard
- ✅ Statistik real-time
- ✅ Tabel pendaftar dengan pagination
- ✅ Filter & search
- ✅ Bulk update status
- ✅ Export ke Excel/PDF

---

## 📱 Fitur yang Akan Datang

### Phase 2
- [ ] Login OTP via WhatsApp untuk pendaftar
- [ ] Notifikasi email hasil seleksi
- [ ] Dashboard calon peserta (tracking status)
- [ ] Cetak kartu pendaftaran PDF
- [ ] Cetak surat penerimaan

### Phase 3
- [ ] Sistem pembayaran (registrasi)
- [ ] Jadwal tes online
- [ ] Nilai tes online integration
- [ ] Pengumuman hasil via WhatsApp

---

## 🐛 Troubleshooting

### Error: "PPDB tidak ditemukan"
- Pastikan `sekolah_id` sudah diatur di `ppdb_settings`
- Jalankan seeder: `php artisan db:seed --class=PPDBSeeder`

### Error: "Anda tidak memiliki akses"
- Pastikan user memiliki role `admin_sekolah` atau `admin_lp`
- Cek `sekolah_id` di user profile sesuai dengan PPDB

### File upload tidak bekerja
- Pastikan storage linked: `php artisan storage:link`
- Cek folder permissions: `chmod -R 755 storage/`

---

## 📞 Kontak & Support

**Email:** ppdb@nuist.id  
**WhatsApp:** +62 812 3456 7890  
**Website:** https://nuist.id

---

## 📜 Changelog

### v1.0.0 (2025-11-12)
- ✨ Initial release
- 📝 Form pendaftaran dengan 3 tahap
- ✓ Dashboard admin sekolah
- 🎯 Dashboard admin LP dengan monitoring
- 📊 Statistik real-time
- 💾 Export data pendaftar

---

## 👨‍💻 Developer Notes

### Kode Nomor Pendaftaran
Format: `SMKM-2025-0001`
- 4 huruf pertama slug sekolah (uppercase)
- Tahun
- Nomor urut (4 digit, zero-padded)

### Validasi NISN
NISN harus unik per database (unique constraint)

### Status Flow
```
pending → verifikasi → lulus
       ↘ tidak_lulus
```

---

**Last Updated:** November 12, 2025  
**Maintained by:** NUIST Development Team
