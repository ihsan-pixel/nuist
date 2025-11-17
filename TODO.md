# TODO: Tambahkan Tombol Clear Cache di Halaman Login

## Tugas Utama
- Tambahkan tombol kecil "Update Aplikasi" di halaman login yang berfungsi untuk clear cache
- Tombol dapat diakses oleh semua pengguna (tidak memerlukan autentikasi)
- Posisi tombol: di bawah tombol login, ukuran kecil

## Langkah-langkah Implementasi

### 1. Buat Route untuk Clear Cache
- Tambahkan route POST `/clear-cache` di `routes/web.php` tanpa middleware auth
- Route akan menjalankan `Artisan::call('cache:clear')` dan `Artisan::call('config:clear')`
- Return response JSON dengan status success

### 2. Modifikasi Halaman Login
- Edit `resources/views/auth/login.blade.php`
- Tambahkan tombol kecil di bawah tombol login
- Tambahkan JavaScript untuk handle klik tombol (AJAX call ke route clear-cache)
- Styling tombol agar kecil dan tidak mengganggu layout

### 3. Testing
- Test tombol berfungsi dengan benar
- Pastikan tidak ada error saat clear cache
- Verifikasi bahwa cache benar-benar ter-clear

## Status Progress
- [x] Route clear-cache dibuat
- [x] Tombol ditambahkan ke halaman login
- [x] JavaScript handler ditambahkan
- [x] Testing selesai
