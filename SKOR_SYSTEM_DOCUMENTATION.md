# Dokumentasi Sistem Scoring PPDB Pendaftar

## Ringkasan Perubahan

Sistem scoring PPDB telah dioptimalkan untuk **otomatis menghitung dan menyimpan skor** setiap kali data pendaftar dibuat atau diupdate.

## Cara Kerja Sistem Scoring

### 1. **Penghitungan Skor Otomatis**

Skor dihitung berdasarkan:

#### a) Skor Nilai Akademik (skor_nilai)
- Rata-rata nilai raport ≥ 90 → **10 poin**
- Rata-rata nilai raport 80-89 → **7 poin**
- Rata-rata nilai raport 70-79 → **6 poin**
- Rata-rata nilai raport < 70 → **0 poin**

#### b) Skor Prestasi (skor_prestasi)
- Memiliki sertifikat prestasi → **10 poin**
- Tidak memiliki sertifikat prestasi → **0 poin**

#### c) Skor Domisili (skor_domisili)
- Saat ini: **0 poin** (bisa dikembangkan lebih lanjut)

#### d) Skor Dokumen (skor_dokumen)
- Saat ini: **0 poin** (bisa dikembangkan lebih lanjut)

#### e) Skor Total (skor_total)
```
skor_total = skor_nilai + skor_prestasi + skor_domisili + skor_dokumen
```

### 2. **Kapan Skor Dihitung dan Disimpan?**

#### Saat Membuat Data Baru (Creating)
Ketika pendaftar baru dibuat melalui form pendaftaran, skor otomatis dihitung dan disimpan ke database.

```php
// File: app/Models/PPDBPendaftar.php
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($model) {
        $model->hitungSkor(); // Hitung skor saat membuat data
    });
}
```

#### Saat Mengupdate Data (Updating)
Ketika data pendaftar diupdate dan ada perubahan field yang terkait skor, maka skor akan otomatis dihitung ulang.

Field yang memicu penghitungan ulang:
- `rata_rata_nilai_raport` - Nilai raport
- `nilai` - Nilai (fallback)
- `berkas_sertifikat_prestasi` - File sertifikat prestasi
- `berkas_kip_pkh` - File KIP/PKH

```php
static::updating(function ($model) {
    $fieldsRelatedToScore = [
        'rata_rata_nilai_raport',
        'nilai',
        'berkas_sertifikat_prestasi',
        'berkas_kip_pkh',
    ];
    
    if ($model->isDirty($field)) { // Cek apakah field berubah
        $model->hitungSkor(); // Hitung ulang skor
    }
});
```

#### Saat Menampilkan Data Pendaftar di Dashboard
Di `AdminLPController::showPendaftar()`, skor dihitung untuk semua pendaftar dan disimpan:

```php
// File: app/Http/Controllers/PPDB/AdminLPController.php
foreach ($pendaftars as $pendaftar) {
    $pendaftar->hitungSkor();
    $pendaftar->save(); // Simpan ke database
}
```

### 3. **Struktur Database**

Tabel `ppdb_pendaftar` memiliki kolom-kolom:

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `skor_nilai` | integer | Skor berdasarkan nilai akademik |
| `skor_prestasi` | integer | Skor berdasarkan prestasi |
| `skor_domisili` | integer | Skor berdasarkan domisili |
| `skor_dokumen` | integer | Skor berdasarkan kelengkapan dokumen |
| `skor_total` | integer | Total skor (sum dari 4 kolom di atas) |
| `ranking` | integer | Ranking berdasarkan skor_total |

### 4. **Alur Proses Lengkap**

```
Pendaftar Daftar
    ↓
Form Pendaftaran Disubmit
    ↓
PPDBPendaftar::create() dipanggil
    ↓
Boot Method Creating Trigger
    ↓
hitungSkor() Dihitung & Disimpan ke Database
    ↓
Pendaftar Ditampilkan di Dashboard dengan Skor
```

### 5. **Contoh Penghitungan Skor**

**Data Pendaftar:**
- Rata-rata nilai raport: 85
- Memiliki sertifikat prestasi: Ya

**Perhitungan:**
```
skor_nilai = 7 (karena 85 ≥ 80)
skor_prestasi = 10 (ada sertifikat)
skor_domisili = 0 (belum diimplementasikan)
skor_dokumen = 0 (belum diimplementasikan)
skor_total = 7 + 10 + 0 + 0 = 17
```

## File-file yang Berubah

### 1. **app/Models/PPDBPendaftar.php**
- Menambah boot method untuk otomatis hitung skor
- Method `hitungSkor()` diperbaharui (tanpa save)
- Menghapus method `hitungSkorDanSimpan()`

### 2. **app/Http/Controllers/PPDB/AdminLPController.php**
- Update method `showPendaftar()` untuk selalu hitung dan simpan skor

## Penggunaan

### Menghitung Skor Manual (Jika Diperlukan)

```php
$pendaftar = PPDBPendaftar::find(1);

// Hitung skor (tidak simpan)
$pendaftar->hitungSkor();

// Simpan ke database
$pendaftar->save();
```

### Menampilkan Skor di View

```blade
<span class="badge bg-primary">{{ $pendaftar->skor_total ?? 0 }}</span>
```

## Pengembangan Lebih Lanjut

Untuk mengembangkan sistem scoring lebih lanjut:

1. **Skor Domisili**: Tambahkan logika untuk menghitung skor berdasarkan jarak domisili
2. **Skor Dokumen**: Tambahkan logika untuk menghitung skor berdasarkan kelengkapan dokumen
3. **Weighting**: Tambahkan bobot berbeda untuk setiap kategori skor
4. **Dinamis**: Buat scoring configuration yang bisa diatur oleh admin

## Testing

Untuk memastikan sistem berfungsi:

1. Buat pendaftar baru dan verifikasi `skor_total` tersimpan di database
2. Update nilai raport pendaftar dan verifikasi skor otomatis diperbaharui
3. Buka dashboard dan lihat skor di kolom "Skor Total"

## Troubleshooting

### Skor Tidak Tersimpan
- Pastikan kolom `skor_nilai`, `skor_prestasi`, `skor_domisili`, `skor_dokumen`, `skor_total` ada di tabel
- Pastikan kolom tersebut sudah ada di `$fillable` di model

### Skor Tidak Otomatis Dihitung
- Cek apakah boot method sudah ada di model
- Verifikasi apakah field yang berubah termasuk dalam `fieldsRelatedToScore`

### Infinite Loop pada Save
- Boot method menggunakan `isDirty()` untuk mencegah infinite loop
- Hanya field yang spesifik yang memicu penghitungan ulang
