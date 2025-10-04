# TODO List - Tambah Kolom Hari KBM pada Madrasah

## Completed Tasks
- [x] Buat migrasi untuk menambahkan kolom `hari_kbm` (enum '5','6') ke tabel madrasahs
- [x] Update model Madrasah untuk menambahkan 'hari_kbm' ke fillable
- [x] Update controller MadrasahController untuk validasi dan penyimpanan hari_kbm di store dan update
- [x] Tambahkan kolom "Hari KBM" ke tabel di index.blade.php
- [x] Tambahkan field select hari_kbm ke modal tambah madrasah
- [x] Tambahkan field select hari_kbm ke modal edit madrasah
- [x] Tampilkan hari_kbm di halaman detail madrasah

## Pending Tasks
- [ ] Jalankan migrasi database: `php artisan migrate`
- [ ] Test fungsionalitas tambah/edit madrasah dengan hari_kbm
- [ ] Verifikasi tampilan di semua halaman terkait

## Notes
- Kolom hari_kbm adalah enum dengan nilai '5' atau '6'
- Field ini opsional (nullable)
- Ditampilkan sebagai "5 hari" atau "6 hari" di UI
