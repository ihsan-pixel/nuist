# TODO: Add File Upload to Laporan Akhir Tahun Form

## Tasks:
- [x] Add enctype="multipart/form-data" to the form in create.blade.php
- [x] Add file upload fields (required) at the bottom of each step (1-10) in create.blade.php
- [x] Modify the store method in LaporanAkhirTahunKepalaSekolahController.php to handle file uploads and save to public/uploads
- [x] Update the model to include file path fields
- [x] Create database migration for file attachment columns
- [x] Fix table name in migration (laporan_akhir_tahun_kepala_sekolah vs laporan_akhir_tahun_kepala_sekolahs)
- [x] Run the migration: php artisan migrate
- [ ] Test the file upload functionality
