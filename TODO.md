# Izin Terlambat Implementation Plan

## Tasks
- [x] Add popup alert after izin terlambat submission
- [x] Block presensi when izin terlambat is pending
- [x] Allow presensi with "terlambat sudah izin" status when approved
- [x] Update izin terlambat view with reminder popup

## Files to Edit
- [x] app/Http/Controllers/Mobile/Izin/IzinController.php
- [x] app/Http/Controllers/Mobile/Presensi/PresensiController.php
- [x] resources/views/mobile/izin-terlambat.blade.php

## Testing
- [ ] Test izin submission flow
- [ ] Test presensi blocking for pending izin
- [ ] Test presensi with approved izin
- [ ] Verify notifications work properly

# Izin Tugas Luar Implementation Plan

## Tasks
- [x] Allow tugas_luar submission even with existing presensi masuk
- [x] Auto-fill waktu_keluar on existing presensi when tugas_luar approved
- [x] Add alert for pending izin submissions
- [x] Update TODO.md with new tasks
- [x] Allow tugas_luar submission even with existing presensi masuk
- [x] Auto-fill waktu_keluar on existing presensi when tugas_luar approved
- [x] Update TODO.md with new tasks

## Files to Edit
- [x] app/Http/Controllers/Mobile/Izin/IzinController.php
- [x] app/Http/Controllers/IzinController.php
- [x] TODO.md

## Testing
- [ ] Test tugas_luar submission with existing presensi masuk
- [ ] Test approval flow and auto-fill waktu_keluar
- [ ] Verify presensi status remains 'hadir' but waktu_keluar is filled
- [ ] Test pending izin alert when trying to submit new izin
