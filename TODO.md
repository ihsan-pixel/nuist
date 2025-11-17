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
