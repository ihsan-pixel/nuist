# 🚀 PPDB NUIST 2025 - QUICK START GUIDE

## ⚡ 5 Menit Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Setup PPDB (dengan data testing)
```bash
php artisan ppdb:setup
```

### 3. Link Storage
```bash
php artisan storage:link
```

### 4. Akses
- **Public**: http://localhost:8000/ppdb
- **Admin Sekolah**: http://localhost:8000/ppdb/sekolah/dashboard
- **Admin LP**: http://localhost:8000/ppdb/lp/dashboard

---

## 📁 File Structure

```
PPDB Module
├── Controllers (app/Http/Controllers/PPDB/)
│   ├── PPDBController.php              (Halaman umum)
│   ├── PendaftarController.php         (Form daftar)
│   ├── AdminSekolahController.php      (Dashboard sekolah)
│   └── AdminLPController.php           (Dashboard LP)
│
├── Models (app/Models/)
│   ├── PPDBSetting.php                 (Konfigurasi PPDB)
│   ├── PPDBPendaftar.php               (Data pendaftar)
│   ├── PPDBJalur.php                   (Jalur pendaftaran)
│   └── PPDBVerifikasi.php              (Log verifikasi)
│
├── Views (resources/views/ppdb/)
│   ├── index.blade.php                 (Daftar sekolah)
│   ├── sekolah.blade.php               (Detail sekolah)
│   ├── daftar.blade.php                (Form pendaftaran)
│   └── dashboard/
│       ├── sekolah.blade.php           (Dashboard sekolah)
│       ├── verifikasi.blade.php        (Verifikasi data)
│       ├── seleksi.blade.php           (Seleksi & nilai)
│       └── lp.blade.php                (Dashboard LP)
│
├── Migrations (database/migrations/)
│   ├── create_ppdb_settings_table.php
│   ├── create_ppdb_pendaftars_table.php
│   ├── create_ppdb_jalurs_table.php
│   └── create_ppdb_verifikasis_table.php
│
├── Routes (routes/web.php)
│   ├── /ppdb/*                         (Public routes)
│   ├── /ppdb/sekolah/*                 (Admin sekolah)
│   └── /ppdb/lp/*                      (Admin LP)
│
├── Seeders & Commands
│   ├── database/seeders/PPDBSeeder.php
│   └── app/Console/Commands/SetupPPDB.php
│
└── Documentation
    ├── PPDB_DOCUMENTATION.md           (Dokumentasi lengkap)
    └── PPDB_CHECKLIST.md               (Checklist pengembangan)
```

---

## 🔄 Alur Kerja

### 1️⃣ Calon Peserta Mendaftar
```
/ppdb (cek sekolah)
  ↓
/ppdb/{slug} (lihat detail)
  ↓
/ppdb/{slug}/daftar (isi form)
  ↓
POST berhasil → Status: PENDING
```

### 2️⃣ Admin Sekolah Verifikasi
```
/ppdb/sekolah/dashboard (lihat stats)
  ↓
/ppdb/sekolah/verifikasi (cek dokumen)
  ↓
Update status → VERIFIKASI atau TIDAK_LULUS
```

### 3️⃣ Admin Sekolah Seleksi
```
/ppdb/sekolah/seleksi (buka form seleksi)
  ↓
Input nilai + ranking
  ↓
Update status → LULUS atau TIDAK_LULUS
```

### 4️⃣ Admin LP Monitor
```
/ppdb/lp/dashboard (lihat semua sekolah)
  ↓
/ppdb/lp/{slug} (lihat detail per sekolah)
  ↓
Lihat laporan & statistik
```

---

## 🎯 Routes Map

### Public Routes
```
GET  /ppdb                              → index (daftar sekolah)
GET  /ppdb/{slug}                       → showSekolah (detail)
GET  /ppdb/{slug}/daftar                → create (form)
POST /ppdb/{slug}/daftar                → store (submit)
```

### Admin Sekolah (auth + admin_sekolah)
```
GET  /ppdb/sekolah/dashboard            → index (dashboard)
GET  /ppdb/sekolah/verifikasi           → verifikasi (list)
POST /ppdb/sekolah/verifikasi/{id}      → updateVerifikasi
GET  /ppdb/sekolah/seleksi              → seleksi (list)
POST /ppdb/sekolah/seleksi/{id}         → updateSeleksi
GET  /ppdb/sekolah/export               → export
```

### Admin LP (auth + admin_lp)
```
GET  /ppdb/lp/dashboard                 → index (dashboard LP)
GET  /ppdb/lp/{slug}                    → detailSekolah
```

---

## 💾 Database Tables

### ppdb_settings
Konfigurasi PPDB per sekolah
```
id, sekolah_id, slug, nama_sekolah, tahun, status, 
jadwal_buka, jadwal_tutup, timestamps
```

### ppdb_pendaftars
Data calon peserta didik
```
id, ppdb_setting_id, ppdb_jalur_id, nomor_pendaftaran, 
nama_lengkap, nisn, asal_sekolah, jurusan_pilihan,
berkas_kk, berkas_ijazah, status, nilai, ranking,
catatan_verifikasi, diverifikasi_oleh, diverifikasi_tanggal,
diseleksi_oleh, diseleksi_tanggal, timestamps
```

### ppdb_jalurs
Jalur pendaftaran (Prestasi, Reguler, Afirmasi)
```
id, ppdb_setting_id, nama_jalur, keterangan, urutan, timestamps
```

### ppdb_verifikasis
Log verifikasi data
```
id, ppdb_setting_id, ppdb_pendaftar_id, status, 
catatan, diverifikasi_oleh, diverifikasi_tanggal, timestamps
```

---

## 🔐 Authorization

### Public (Tidak perlu login)
- Lihat daftar sekolah
- Lihat detail sekolah
- Isi form pendaftaran

### Admin Sekolah (auth + role:admin_sekolah)
- Verifikasi data pendaftar sekolahnya
- Input nilai & seleksi
- Export data

### Admin LP (auth + role:admin_lp)
- Lihat semua PPDB
- Monitoring terpadu
- Laporan per sekolah

---

## 🛠️ Troubleshooting

### ❌ Error: "PPDB tidak ditemukan"
**Solusi:** Jalankan `php artisan ppdb:setup`

### ❌ Error: "File upload failed"
**Solusi:** 
```bash
php artisan storage:link
chmod -R 755 storage/
```

### ❌ Error: "Anda tidak memiliki akses"
**Solusi:** Cek role user dan sekolah_id di database

### ❌ NISN duplikat
**Solusi:** NISN harus unik per database, cek kolom unique constraint

---

## 📊 Status Transitions

```
pending 
  ├─→ verifikasi ─→ lulus
  └─→ tidak_lulus
```

### Status Meanings
- **pending**: Baru daftar, menunggu verifikasi
- **verifikasi**: Dokumen sudah diverifikasi
- **lulus**: Lulus seleksi
- **tidak_lulus**: Tidak lulus verifikasi atau seleksi

---

## 🎨 Customize Tampilan

### Styling
- Menggunakan **Tailwind CSS** (v3+)
- Icons dari **Heroicons**
- Layout responsive mobile-first

### Warna Tema
```
Primary: Green (NU branding)
Secondary: Blue (NUIST branding)
Status Colors:
  - Pending: Yellow
  - Verifikasi: Blue
  - Lulus: Green
  - Tidak Lulus: Red
```

### Logo & Gambar
Letakkan di `public/images/` atau gunakan `storage/`:
```blade
<img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo">
```

---

## 📱 Mobile Responsive

Semua halaman sudah responsive:
- Mobile (sm): max-width 640px
- Tablet (md): max-width 768px
- Desktop (lg): max-width 1024px

Test menggunakan Chrome DevTools → Toggle device toolbar

---

## 🚨 Important Notes

### File Upload
- Max size: 2MB per file
- Allowed: PDF, JPG, PNG
- Storage: `storage/app/public/ppdb/`

### Nomor Pendaftaran
Format: `SMKM-2025-0001`
- Auto-generated dengan unique constraint
- Tidak bisa diubah setelah daftar

### Backup Data
Jangan lupa backup database sebelum production:
```bash
php artisan backup:run
```

---

## 🔮 Fitur Mendatang

- [ ] Email notifikasi hasil
- [ ] PDF export & cetak
- [ ] WhatsApp OTP login
- [ ] Dashboard analytics
- [ ] Tes online integration
- [ ] Pembayaran registrasi

---

## 📞 Support

**Email:** ppdb@nuist.id  
**WhatsApp:** +62 812 3456 7890  
**Docs:** `PPDB_DOCUMENTATION.md`

---

**Happy Coding! 🎉**  
*Terakhir diperbaharui: 12 November 2025*
