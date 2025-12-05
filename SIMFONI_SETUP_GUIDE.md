# Setup Guide - Simfoni (Data SK Tenaga Pendidik)

## 📋 Apa itu Simfoni?

Simfoni adalah form digital untuk mengumpulkan dan mengelola data Surat Kepangkatan (SK) dan informasi lengkap tenaga pendidik. Form ini terdiri dari 4 bagian utama:

- **A. DATA SK** - Informasi identitas dan kepangkatan
- **B. RIWAYAT KERJA** - Data pengalaman kerja
- **C. KEAHLIAN DAN DATA LAIN** - Keahlian, prestasi, kontak
- **D. DATA KEUANGAN** - Informasi gaji dan penghasilan

## 🗄️ Database

### Tabel: `simfoni`

File: `database/migrations/2025_12_05_000000_create_simfoni_table.php`

#### Fields

| Field | Type | Nullable | Keterangan |
|-------|------|----------|-----------|
| id | BIGINT | ✗ | Primary Key |
| user_id | BIGINT | ✗ | Foreign Key ke users |
| nama_lengkap_gelar | VARCHAR(255) | ✓ | Auto-filled dari users.name |
| tempat_lahir | VARCHAR(255) | ✓ | Auto-filled dari users.tempat_lahir |
| tanggal_lahir | DATE | ✓ | Auto-filled dari users.tanggal_lahir |
| nuptk | VARCHAR(255) | ✓ | Auto-filled dari users.nuptk |
| kartanu | VARCHAR(255) | ✓ | Auto-filled dari users.kartanu |
| nipm | VARCHAR(255) | ✓ | NIP Ma'arif Baru (auto-filled dari users.nipm) |
| nik | VARCHAR(255) | ✓ | Nomor Induk Kependudukan |
| tmt | DATE | ✓ | Auto-filled dari users.tmt |
| strata_pendidikan | VARCHAR(255) | ✓ | Auto-filled dari users.pendidikan_terakhir |
| pt_asal | VARCHAR(255) | ✓ | Perguruan Tinggi Asal |
| tahun_lulus | INT | ✓ | Tahun lulus |
| program_studi | VARCHAR(255) | ✓ | Auto-filled dari users.program_studi |
| status_kerja | VARCHAR(100) | ✓ | PNS, PPPK, Honorer, Yayasan |
| tanggal_sk_pertama | DATE | ✓ | Tanggal SK Pertama |
| nomor_sk_pertama | VARCHAR(100) | ✓ | Nomor SK Pertama |
| nomor_sertifikasi_pendidik | VARCHAR(100) | ✓ | Nomor Sertifikat Pendidik |
| riwayat_kerja_sebelumnya | LONGTEXT | ✓ | Deskripsi riwayat kerja |
| keahlian | LONGTEXT | ✓ | Keahlian khusus |
| kedudukan_lpm | VARCHAR(100) | ✓ | Kedudukan di LPM |
| prestasi | LONGTEXT | ✓ | Prestasi/Pencapaian |
| tahun_sertifikasi_impassing | VARCHAR(100) | ✓ | Tahun Sertifikasi & Impassing |
| no_hp | VARCHAR(255) | ✓ | Auto-filled dari users.phone |
| email | VARCHAR(255) | ✓ | Auto-filled dari users.email |
| status_pernikahan | VARCHAR(50) | ✓ | Belum Kawin, Kawin, Cerai Hidup, Cerai Mati |
| alamat_lengkap | LONGTEXT | ✓ | Alamat lengkap |
| bank | VARCHAR(100) | ✓ | Nama Bank |
| nomor_rekening | VARCHAR(50) | ✓ | Nomor Rekening |
| gaji_sertifikasi | DECIMAL(15,2) | ✓ | Gaji Sertifikasi |
| gaji_pokok | DECIMAL(15,2) | ✓ | Gaji Pokok Perbulan |
| honor_lain | DECIMAL(15,2) | ✓ | Honor Lain |
| penghasilan_lain | DECIMAL(15,2) | ✓ | Penghasilan Lain |
| penghasilan_pasangan | DECIMAL(15,2) | ✓ | Penghasilan Pasangan (tidak dihitung) |
| total_penghasilan | DECIMAL(15,2) | ✓ | Total Penghasilan (auto-calculate) |
| created_at | TIMESTAMP | ✗ | |
| updated_at | TIMESTAMP | ✗ | |

## 🔧 File-File yang Dibuat

### 1. Model
**File**: `app/Models/Simfoni.php`
- Eloquent Model untuk tabel simfoni
- Relasi: belongsTo(User)

### 2. Migration
**File**: `database/migrations/2025_12_05_000000_create_simfoni_table.php`
- Membuat tabel simfoni dengan struktur lengkap
- Indexes pada user_id dan created_at

### 3. Controller
**File**: `app/Http/Controllers/Mobile/SimfoniController.php`

Methods:
- `show()` - Menampilkan form (GET /mobile/simfoni)
- `store()` - Menyimpan data (POST /mobile/simfoni)

### 4. View
**File**: `resources/views/mobile/simfoni.blade.php`
- Form yang terdiri dari 4 section utama
- Auto-fill dari data user yang sudah terlogin
- Validasi error handling
- Auto-calculate total penghasilan
- Responsive design untuk mobile

### 5. Routes
**File**: `routes/web.php`

```php
Route::middleware(['auth', 'role:tenaga_pendidik,admin'])->prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/simfoni', [SimfoniController::class, 'show'])->name('simfoni.show');
    Route::post('/simfoni', [SimfoniController::class, 'store'])->name('simfoni.store');
});
```

## 🚀 Setup Steps

### 1. Run Migration

```bash
php artisan migrate
```

### 2. Akses Form

URL: `/mobile/simfoni`

## 📝 Form Structure

### Section A - DATA SK (Auto-filled dari User)
- Nama Lengkap dengan Gelar *(required)*
- Tempat & Tanggal Lahir *(required)*
- NUPTK
- Karta-NU
- NIP Ma'arif Baru
- Nomor Induk Kependudukan (NIK) *(required)*
- TMT Pertama *(required)*
- Strata Pendidikan *(required)*
- PT Asal
- Tahun Lulus *(required)*
- Nama Program Studi *(required)*

### Section B - RIWAYAT KERJA
- Status Kerja Saat Ini *(required)* - Dropdown (PNS, PPPK, Honorer, Yayasan)
- Tanggal & Nomor SK Pertama *(required)*
- Nomor Sertifikasi Pendidik
- Riwayat Kerja Sebelumnya - Textarea

### Section C - KEAHLIAN DAN DATA LAIN
- Keahlian - Textarea
- Kedudukan di LPM
- Prestasi - Textarea
- Tahun Sertifikasi & Impassing
- Nomor HP/WA *(required, auto-filled)*
- E-mail Aktif *(required, auto-filled)*
- Status Pernikahan *(required)* - Dropdown
- Alamat Lengkap *(required)*

### Section D - DATA KEUANGAN/KESEJAHTERAAN
- Bank & Nomor Rekening
- Gaji Sertifikasi
- Gaji Pokok Perbulan
- Honor Lain
- Penghasilan Lain
- Penghasilan Pasangan (tidak dihitung)
- Total Penghasilan (auto-calculate)

## 🎨 Styling

- Color Primary: #6b4c9a (Ungu)
- Section title: Gradient ungu
- Form validation: Error messages berwarna merah
- Mobile responsive: Max-width 420px

## ✅ Validasi

### Server-side (Controller)
Semua field dengan `(required)` di atas harus diisi

### Client-side (HTML5)
- Validasi tipe input (email, date, number)
- Required field indicator (*)

## 🔒 Security

- User hanya bisa mengisi data mereka sendiri
- Authorization: Hanya role `tenaga_pendidik` dan `admin` yang bisa akses
- CSRF protection: @csrf token di form

## 💾 Data Persistence

- Form menggunakan GET/SHOW untuk menampilkan data existing
- POST untuk menyimpan data baru atau update
- Auto-fill dari table users
- Session flash message untuk feedback

## 📱 Mobile Features

- Responsive design
- Touch-friendly inputs
- Auto-calculate fields
- Section collapse-able untuk navigasi lebih mudah
- Readonly fields untuk data auto-fill (visual distinction)

## 🔄 Auto-fill dari User Table

Fields yang otomatis diisi dari tabel users:

```php
'nama_lengkap_gelar' => $user->name
'tempat_lahir' => $user->tempat_lahir
'tanggal_lahir' => $user->tanggal_lahir
'nuptk' => $user->nuptk
'kartanu' => $user->kartanu
'nipm' => $user->nipm
'tmt' => $user->tmt
'strata_pendidikan' => $user->pendidikan_terakhir
'program_studi' => $user->program_studi
'no_hp' => $user->phone
'email' => $user->email
'alamat_lengkap' => $user->alamat
```

## 🧮 Auto-Calculate

Total Penghasilan dihitung otomatis saat ada perubahan pada:
- Gaji Sertifikasi
- Gaji Pokok
- Honor Lain
- Penghasilan Lain

Formula: `Total = Gaji Sertifikasi + Gaji Pokok + Honor Lain + Penghasilan Lain`

## 📞 Support

Untuk pertanyaan atau perubahan, hubungi development team.

---

**Created**: December 5, 2025
**Version**: 1.0
