# TODO: Implementasi Pengaturan Presensi per Status Kepegawaian

## Tugas Utama
- Modifikasi sistem presensi agar pengaturan presensi dapat disesuaikan berdasarkan jenis status kepegawaian tenaga pendidik.

## Langkah-langkah Implementasi

### 1. Migrasi Database
- [x] Buat migrasi untuk menambahkan kolom `status_kepegawaian_id` ke tabel `presensi_settings`
- [x] Tambahkan constraint unique pada kombinasi `status_kepegawaian_id`
- [ ] Jalankan migrasi

### 2. Update Model PresensiSettings
- [x] Hapus atribut `singleton` dari model
- [x] Tambahkan `status_kepegawaian_id` ke fillable
- [x] Tambahkan relationship ke StatusKepegawaian
- [x] Update method untuk mendapatkan settings berdasarkan status

### 3. Update PresensiAdminController
- [x] Modifikasi method `settings()` untuk menampilkan semua settings per status
- [x] Modifikasi method `updateSettings()` untuk menangani multiple settings
- [x] Tambahkan method untuk membuat settings baru per status jika belum ada

### 4. Update View presensi_admin/settings.blade.php
- [x] Ubah tampilan untuk menampilkan list settings per status kepegawaian
- [x] Tambahkan form untuk setiap status dengan input waktu
- [x] Pastikan UI user-friendly dengan accordion atau tabs per status

### 5. Update PresensiController
- [x] Modifikasi method `store()` untuk mendapatkan settings berdasarkan status kepegawaian user
- [x] Pastikan logika validasi menggunakan settings yang sesuai

### 6. Seeding Data
- [x] Buat seeder untuk membuat settings awal untuk setiap status kepegawaian yang ada
- [ ] Jalankan seeder setelah migrasi

### 7. Testing
- [ ] Test presensi untuk user dengan status berbeda
- [ ] Verifikasi settings diterapkan dengan benar
- [ ] Test UI admin untuk mengatur settings per status

## Catatan Teknis
- Pastikan backward compatibility dengan data existing
- Handle case dimana user belum memiliki status_kepegawaian_id
- Update dokumentasi jika diperlukan
