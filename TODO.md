# TODO: Implementasi Menu "Progres Mengajar" untuk Super Admin

## Tugas Utama
- Membuat menu baru "Progres Mengajar" untuk user role super admin
- Menampilkan tabel persentase dari masing-masing madrasah dengan kolom:
  - SCOD
  - Nama Sekolah
  - Sudah input jadwal (persentase guru yang sudah memiliki jadwal mengajar)
  - Sudah presentasi mengajar sesuai jadwal (persentase guru yang sudah melakukan presensi mengajar)
  - Berapa persen guru yang sudah melakukan presensi mengajar
  - Berapa persen guru yang belum presensi mengajar

## Langkah-langkah Implementasi
- [x] Buat TeachingProgressController
- [x] Tambah route di routes/web.php
- [x] Buat view resources/views/admin/teaching_progress.blade.php
- [x] Update resources/views/layouts/sidebar.blade.php untuk menambah menu
- [x] Test akses dan fungsionalitas (file sudah dibuat, route sudah ditambahkan, menu sudah ditambahkan)

## Detail Teknis
- Controller: TeachingProgressController dengan method index()
- Route: /admin/teaching-progress, middleware role:super_admin
- View: Tabel dengan DataTables, grouped by kabupaten seperti data_madrasah
- Sidebar: Tambah menu di bagian INFORMATION untuk super_admin
- Logika: Hitung persentase berdasarkan TeachingSchedule dan TeachingAttendance per madrasah
