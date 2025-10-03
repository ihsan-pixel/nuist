# TODO: Tambahkan Kolom Status Kepegawaian ke Tabel Presensi

## Langkah-langkah:
1. [x] Buat migration untuk menambahkan kolom status_kepegawaian_id ke tabel presensis
2. [x] Update model Presensi: tambahkan ke fillable dan relationship
3. [x] Update PresensiController store method untuk set status_kepegawaian_id saat create
4. [x] Update view presensi/index.blade.php untuk menampilkan kolom status kepegawaian
5. [x] Update view presensi_admin/index.blade.php untuk menampilkan kolom status kepegawaian
6. [x] Buat migration untuk populate existing records dengan status_kepegawaian_id dari user
7. [x] Jalankan migration dan test (Perlu jalankan php artisan migrate di environment yang sesuai)
