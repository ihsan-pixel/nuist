# TODO: Add File Upload to Laporan Akhir Tahun Form

## Tasks:
- [x] Add enctype="multipart/form-data" to the form in create.blade.php
- [x] Add file upload fields (required) at the bottom of each step (1-9) in create.blade.php
- [x] Modify the store method in LaporanAkhirTahunKepalaSekolahController.php to handle file uploads
- [x] Add auto-save draft functionality with route
- [x] Create migration to add status column to database
- [x] Run migration on server database (php artisan migrate on server)
- [x] Test the file upload functionality
