# TODO: Implement Presensi Restrictions for Izin Users

## Overview
Modify the mobile presensi system so that users who have submitted izin cannot perform presensi masuk, except for users with approved izin terlambat who can presensi anytime.

## Tasks
- [x] Modify PresensiController.php store() method to add izin validation
- [x] Add logic to check existing presensi with status 'izin' for the same date
- [x] Allow presensi only if izin is approved terlambat (check keterangan for "Waktu masuk:")
- [x] Ensure presensi keluar is not affected by this restriction
- [x] Test the implementation

## Implementation Details
- Location: app/Http/Controllers/PresensiController.php
- Method: store()
- Logic: Before creating new presensi, check if user has existing izin for today
- Exception: If izin is approved and contains "Waktu masuk:" in keterangan, allow presensi
- Error message: "Anda telah mengajukan izin untuk hari ini. Presensi masuk tidak diizinkan kecuali izin terlambat yang telah disetujui."
