# TODO: Change Presensi System Configuration

## Tasks
- [x] Change late calculation to start at 07:00 instead of 05:00 in PresensiController.php (2 locations)
- [x] Change time ranges to allow presensi from 05:00 to 07:00 in MobileController.php and PresensiController.php
- [x] Update UI text to show "Terlambat setelah 07:00" in mobile/presensi.blade.php
- [x] Implement presensi store logic directly in MobileController.php instead of delegating to PresensiController
- [x] Modify keterangan to only show "terlambat X menit" for users who presensi after 07:00, leave empty for on-time presensi
- [x] Fix late calculation logic to properly compare times on the same day
- [x] Fix inkonsistensi format keterangan terlambat antara PresensiController.php dan MobileController.php

## Followup steps
- [ ] Test that presensi can be done from 5 AM and late is calculated correctly starting at 07:00
