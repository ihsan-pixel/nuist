# Quick Start: Testing Sistem Scoring PPDB

## 🚀 Langkah-langkah Testing

### Setup Database
Pastikan migration sudah dijalankan dan kolom scoring sudah ada:
```bash
php artisan migrate
```

### Verify Database Columns
```sql
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'ppdb_pendaftar' 
AND COLUMN_NAME LIKE 'skor%';

-- Hasil yang diharapkan:
-- skor_nilai
-- skor_prestasi
-- skor_domisili
-- skor_dokumen
-- skor_total
```

---

## 📋 Test Case 1: Pendaftar Baru Otomatis Dapat Skor

### Step 1: Buat Pendaftar Baru
Gunakan form pendaftaran atau Tinker:

```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::create([
    'ppdb_setting_id' => 1,
    'nama_lengkap' => 'Test Student',
    'nisn' => '1234567890',
    'rata_rata_nilai_raport' => 85,
    'berkas_sertifikat_prestasi' => null,
    'status' => 'pending',
]);

exit
```

### Step 2: Verifikasi Skor di Database
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

echo "Skor Nilai: " . $pendaftar->skor_nilai . "\n";        // Harus: 7
echo "Skor Prestasi: " . $pendaftar->skor_prestasi . "\n";  // Harus: 0
echo "Skor Total: " . $pendaftar->skor_total . "\n";        // Harus: 7

exit
```

**Expected Result:**
```
Skor Nilai: 7
Skor Prestasi: 0
Skor Total: 7
```

---

## 📋 Test Case 2: Update Nilai Otomatis Recalculate Skor

### Step 1: Update Nilai Raport
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

// Update nilai raport menjadi lebih tinggi
$pendaftar->update(['rata_rata_nilai_raport' => 92]);

exit
```

### Step 2: Verifikasi Skor Berubah
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

echo "Skor Nilai (baru): " . $pendaftar->skor_nilai . "\n";  // Harus: 10
echo "Skor Total (baru): " . $pendaftar->skor_total . "\n";  // Harus: 10

exit
```

**Expected Result:**
```
Skor Nilai (baru): 10
Skor Total (baru): 10
```

---

## 📋 Test Case 3: Skor Prestasi Ter-update

### Step 1: Update dengan Sertifikat Prestasi
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

// Simulasi upload sertifikat
$pendaftar->update(['berkas_sertifikat_prestasi' => 'path/to/file.pdf']);

exit
```

### Step 2: Verifikasi Skor Prestasi Berubah
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

echo "Skor Prestasi (dengan sertifikat): " . $pendaftar->skor_prestasi . "\n";  // Harus: 10
echo "Skor Total (updated): " . $pendaftar->skor_total . "\n";                  // Harus: 20

exit
```

**Expected Result:**
```
Skor Prestasi (dengan sertifikat): 10
Skor Total (updated): 20
```

---

## 📋 Test Case 4: Dashboard Menampilkan Skor

### Step 1: Buka Dashboard
```
Navigate to: /ppdb/lp/dashboard/pendaftar
(atau sesuai route di aplikasi Anda)
```

### Step 2: Verifikasi Kolom "Skor Total"
- Cek tabel pendaftar
- Kolom "Skor Total" harus menampilkan angka (bukan kosong)
- Angka harus sesuai dengan kalkulasi di database

**Expected Result:**
```
Tabel:
No | Nama | NISN | ... | Skor Total | Status
1  | Test | 1234 | ... | 20         | pending
```

---

## 🐛 Debugging

### Check Boot Method Triggered
Tambahkan log di `app/Models/PPDBPendaftar.php`:

```php
static::creating(function ($model) {
    \Log::info('Creating hook triggered for: ' . $model->nama_lengkap);
    $model->hitungSkor();
    \Log::info('Skor dihitung: ' . $model->skor_total);
});
```

Cek di `storage/logs/laravel.log`

### Check Dirty Fields
```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();
dd($pendaftar->getDirty()); // Lihat field apa yang berubah
```

### Raw SQL Check
```bash
php artisan tinker
```

```php
$pendaftar = \App\Models\PPDBPendaftar::latest()->first();

// Execute raw SQL to see actual database values
$result = \DB::select("SELECT id, nama_lengkap, skor_nilai, skor_prestasi, skor_total FROM ppdb_pendaftar WHERE id = ?", [$pendaftar->id]);

dd($result);

exit
```

---

## 📊 Monitoring Skor

### Check Semua Pendaftar
```bash
php artisan tinker
```

```php
\App\Models\PPDBPendaftar::select('nama_lengkap', 'rata_rata_nilai_raport', 'skor_nilai', 'skor_prestasi', 'skor_total')
    ->orderBy('skor_total', 'desc')
    ->limit(10)
    ->get()
    ->each(function($p) {
        echo $p->nama_lengkap . ": " . $p->skor_total . "\n";
    });

exit
```

### Check Null Scores
```bash
php artisan tinker
```

```php
$nullScores = \App\Models\PPDBPendaftar::whereNull('skor_total')->count();
echo "Pendaftar dengan skor null: " . $nullScores . "\n";

exit
```

---

## 🔄 Fixing Issues

### Jika Ada Pendaftar Tanpa Skor

#### Option 1: Via Tinker (Quick)
```bash
php artisan tinker
```

```php
\App\Models\PPDBPendaftar::whereNull('skor_total')
    ->each(function($pendaftar) {
        $pendaftar->hitungSkor();
        $pendaftar->save();
        echo "Fixed: " . $pendaftar->nama_lengkap . "\n";
    });

exit
```

#### Option 2: Membuat Command
File: `app/Console/Commands/FixScoringCommand.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\PPDBPendaftar;
use Illuminate\Console\Command;

class FixScoringCommand extends Command
{
    protected $signature = 'scoring:fix';
    protected $description = 'Fix missing scores for all pendaftars';

    public function handle()
    {
        $pendaftars = PPDBPendaftar::all();
        
        foreach ($pendaftars as $pendaftar) {
            $pendaftar->hitungSkor();
            $pendaftar->save();
        }
        
        $this->info('✅ All scores fixed!');
    }
}
```

Run:
```bash
php artisan scoring:fix
```

---

## 📈 Performance Tips

### 1. Jangan Hitung Terlalu Sering
Hanya hitung saat data berubah (sudah otomatis di boot method)

### 2. Batch Update Untuk Banyak Record
```php
// BURUK - Trigger boot method untuk setiap record
\App\Models\PPDBPendaftar::where('status', 'pending')
    ->update(['status' => 'verifikasi']);

// BAIK - Disable events jika tidak perlu scoring
\App\Models\PPDBPendaftar::withoutEvents(function () {
    return \App\Models\PPDBPendaftar::where('status', 'pending')
        ->update(['status' => 'verifikasi']);
});
```

### 3. Index Database
```sql
ALTER TABLE ppdb_pendaftar ADD INDEX idx_skor_total (skor_total);
```

---

## ✅ Checklist

- [ ] Database migration sudah dijalankan
- [ ] Kolom `skor_*` sudah ada di tabel
- [ ] Boot method sudah ada di PPDBPendaftar.php
- [ ] Controller sudah diupdate
- [ ] Test Case 1 passed ✅
- [ ] Test Case 2 passed ✅
- [ ] Test Case 3 passed ✅
- [ ] Test Case 4 passed ✅
- [ ] Dashboard menampilkan skor dengan benar

---

**Status:** Ready for Production ✅
