# TODO: Fix Izin Button Functionality

## Tasks
- [x] Modify MobileController@storeIzin to store izin data in presensis table instead of izins table
- [x] Update validation and file handling for presensis storage
- [x] Ensure izin appears in kelola izin (izin/index) for approval by admin/kepala sekolah
- [ ] Test the button functionality and data submission
- [ ] Verify approval process works

## Details
- Change storeIzin to create Presensi record with status='izin', status_izin='pending'
- Use keterangan field to store alasan or deskripsi_tugas
- Store file in surat_izin_path
- Handle different file fields (file_izin, file_tugas)
- Ensure no duplicate izin for same day/type
