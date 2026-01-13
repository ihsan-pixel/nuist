# TODO: Add File Upload to Laporan Akhir Tahun Form

## Tasks:
- [x] Add enctype="multipart/form-data" to the form in create.blade.php
- [x] Add file upload fields (required) at the bottom of each step (1-9) in create.blade.php (removed from step 10)
- [x] Modify the store method in LaporanAkhirTahunKepalaSekolahController.php to handle file uploads and save to public/uploads
- [x] Update the model to include file attachment fields
- [x] Add auto-save draft functionality for automatic draft saving
- [x] Add route for auto-save-draft endpoint
- [x] Fix database migration to add back status column
- [ ] Test the file upload functionality
