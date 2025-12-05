# SIMFONI Quick Reference

## 🔗 URLs

| Action | URL | Method |
|--------|-----|--------|
| View Form | `/mobile/simfoni` | GET |
| Save/Update Data | `/mobile/simfoni` | POST |

## 📂 File Locations

```
Model:           app/Models/Simfoni.php
Controller:      app/Http/Controllers/Mobile/SimfoniController.php
View:            resources/views/mobile/simfoni.blade.php
Migration:       database/migrations/2025_12_05_000000_create_simfoni_table.php
Routes:          routes/web.php (lines ~196-198)
```

## 🗄️ Database Info

**Table Name**: `simfoni`  
**Primary Key**: `id` (BIGINT)  
**Foreign Key**: `user_id` (references users.id)  
**Rows**: 1 per user (one-to-one relationship)  
**Total Columns**: 42 (40 data + id + timestamps)

### Column Count by Section

| Section | Count | Type |
|---------|-------|------|
| A. DATA SK | 13 | Various |
| B. RIWAYAT KERJA | 5 | Various |
| C. KEAHLIAN & DATA LAIN | 9 | Various |
| D. DATA KEUANGAN | 8 | DECIMAL(15,2) |
| **Total** | **40** | |

## 👤 Who Can Access?

- **Roles**: `tenaga_pendidik`, `admin`
- **Authentication**: Required (middleware: auth)
- **Authorization**: User can only edit their own data

## 🧩 Auto-Fill Fields (11 fields)

These fields are automatically populated from the users table:

```
1. nama_lengkap_gelar    ← users.name
2. tempat_lahir          ← users.tempat_lahir
3. tanggal_lahir         ← users.tanggal_lahir
4. nuptk                 ← users.nuptk
5. kartanu               ← users.kartanu
6. nipm                  ← users.nipm
7. tmt                   ← users.tmt
8. strata_pendidikan     ← users.pendidikan_terakhir
9. program_studi         ← users.program_studi
10. no_hp                ← users.phone
11. email                ← users.email
```

## 🔢 Auto-Calculate Field (1 field)

**Field**: `total_penghasilan`  
**Formula**: `gaji_sertifikasi + gaji_pokok + honor_lain + penghasilan_lain`  
**Triggers**: Change on any of the 4 fields above

## 📋 Form Sections

### A. DATA SK (13 fields)
Auto-filled: 9 fields  
Manual input: 3 fields (NIK, PT Asal, Tahun Lulus)

### B. RIWAYAT KERJA (5 fields)
Required: 3 fields (Status Kerja, Tanggal SK, Nomor SK)  
Optional: 2 fields

### C. KEAHLIAN & DATA LAIN (9 fields)
Auto-filled: 2 fields (No HP, Email)  
Required: 1 field (Alamat Lengkap)  
Optional: 6 fields

### D. DATA KEUANGAN (8 fields)
All optional except logical dependencies

## 🎨 Color Scheme

| Element | Color | Hex |
|---------|-------|-----|
| Primary Gradient Start | Ungu | #6b4c9a |
| Primary Gradient End | Ungu Gelap | #5a4080 |
| Section Title BG | Gradient | #6b4c9a → #5a4080 |
| Text Color | Gelap | #333 |
| Border Color | Tipis | #e0e0e0 |
| Error Color | Merah | #dc3545 |
| Success Color | Hijau | #28a745 |

## ⚡ JavaScript Features

1. **Auto-fill on Load**
   - Checks if readonly fields are empty
   - Populates from blade variables
   - Runs on DOMContentLoaded

2. **Auto-Calculate**
   - Listens to form change events
   - Recalculates total on input change
   - Updates total_penghasilan field

## 📱 Responsive Breakpoints

- **Mobile**: ≤ 420px (target)
- **Tablet**: 421-768px
- **Desktop**: > 768px

Grid Layout:
- Default: 1 column
- Certain sections: 2 columns (row-2col)

## ✅ Required Fields (5 fields)

1. `nama_lengkap_gelar` - auto-filled
2. `tempat_lahir` - auto-filled
3. `tanggal_lahir` - auto-filled
4. `nik` - manual input
5. `tahun_lulus` - manual input
6. `status_kerja` - manual input (dropdown)
7. `tanggal_sk_pertama` - manual input
8. `nomor_sk_pertama` - manual input
9. `status_pernikahan` - manual input (dropdown)
10. `alamat_lengkap` - manual input
11. `email` - auto-filled
12. `no_hp` - auto-filled

## 🔐 Form Submission

**Method**: POST  
**Endpoint**: `/mobile/simfoni`  
**CSRF Token**: Required (@csrf in form)  
**Data Type**: form-data  
**Redirect**: Back with success message

## 📊 Database Query Examples

### Get User's Simfoni Data
```php
$simfoni = Simfoni::where('user_id', $userId)->first();
```

### Get All Simfoni Records
```php
$all = Simfoni::all();
```

### Get by Status Kerja
```php
$pns = Simfoni::where('status_kerja', 'PNS')->get();
```

## 🚀 Setup Command

```bash
php artisan migrate
```

This creates the `simfoni` table with all 40 fields.

## 🧪 Test Data Entry

Minimum fields to submit:
- nama_lengkap_gelar (auto)
- tempat_lahir (auto)
- tanggal_lahir (auto)
- nik (required)
- tahun_lulus (required)
- status_kerja (required)
- tanggal_sk_pertama (required)
- nomor_sk_pertama (required)
- status_pernikahan (required)
- alamat_lengkap (required)

## 📞 Troubleshooting

**Form not loading**
→ Check migration was run: `php artisan migrate`

**Auto-fill not working**
→ Check user table has data in: tempat_lahir, tanggal_lahir, nuptk, kartanu, nipm, tmt, pendidikan_terakhir, program_studi, phone, email

**Total not calculating**
→ Check JavaScript console for errors, ensure form has id="simfoniForm"

**Validation error**
→ Check controller validation rules in SimfoniController::store()

## 📖 Documentation Files

- `SIMFONI_SETUP_GUIDE.md` - Detailed setup instructions
- `SIMFONI_IMPLEMENTATION_CHECKLIST.md` - Implementation checklist
- `SIMFONI_SUMMARY.md` - Complete implementation summary

---

**Last Updated**: December 5, 2025  
**Version**: 1.0
