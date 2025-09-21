# Perbaikan Import dan Input Data Tenaga Pendidik

## Status: MOSTLY COMPLETED

### Masalah yang Ditemukan:
1. **TenagaPendidikImport.php**: Field `mengajar` hilang dari validasi dan User::create()
2. **User.php Model**: Cast `ketugasan` belum sesuai enum
3. **File CSV**: Menggunakan nilai lama untuk `ketugasan` yang tidak sesuai enum baru
4. **Validasi**: Perlu validasi enum untuk `ketugasan`

### Rencana Perbaikan:

#### 1. TenagaPendidikImport.php ✅ COMPLETED
- [x] Tambahkan `mengajar` ke required fields
- [x] Tambahkan `mengajar` ke User::create()
- [x] Tambahkan validasi enum untuk `ketugasan`
- [x] Update log messages

#### 2. User.php Model ✅ COMPLETED
- [x] Update cast `ketugasan` dari string ke enum

#### 3. File CSV Template ✅ COMPLETED
- [x] Update nilai `ketugasan` di test_import_tenaga_pendidik.csv
- [x] Update nilai `ketugasan` di test_import_tenaga_pendidik_updated.csv
- [x] Tambahkan kolom `mengajar` ke template

#### 4. Verifikasi ✅ PENDING
- [ ] Test import dengan data yang benar
- [ ] Verifikasi form input sudah sesuai

### File yang Telah Diedit:
- ✅ app/Imports/TenagaPendidikImport.php
- ✅ app/Models/User.php
- ✅ test_import_tenaga_pendidik.csv
- ✅ test_import_tenaga_pendidik_updated.csv

### Perubahan yang Dilakukan:

#### 1. TenagaPendidikImport.php
- Menambahkan field `mengajar` ke array required fields
- Menambahkan field `mengajar` ke User::create()
- Menambahkan validasi enum untuk `ketugasan` dengan nilai yang diizinkan: 'tenaga pendidik' dan 'kepala madrasah/sekolah'
- Error message yang lebih informatif untuk validasi ketugasan

#### 2. User.php Model
- Menghapus cast `ketugasan` => 'string' karena Laravel akan menangani enum secara otomatis
- Model sekarang kompatibel dengan kolom enum di database

#### 3. File CSV Template
- Update nilai `ketugasan` dari "Guru Matematika" menjadi "tenaga pendidik"
- Menambahkan kolom `mengajar` dengan nilai yang sesuai (Guru Matematika, Guru Bahasa, dll.)
- Data sekarang sesuai dengan struktur database yang baru

### Langkah Selanjutnya:
1. Test import dengan file CSV yang telah diperbaiki
2. Verifikasi bahwa form input di view sudah menangani field baru dengan benar
3. Test create/update tenaga pendidik melalui form web
