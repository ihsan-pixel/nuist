# TODO: Implement Dual Presensi for Tenaga Pendidik with Beban Kerja Lain

## Completed Tasks
- [x] Create migration to add madrasah_id to presensis table
- [x] Update Presensi model fillable and relationships
- [x] Modify PresensiController::store() to handle dual presensi logic
- [x] Update MobileController to fetch multiple presensi records

## Remaining Tasks
- [ ] Run migration to apply database changes
- [ ] Test dual presensi functionality
- [x] Update mobile views to display presensi for both madrasahs
- [ ] Update riwayat presensi to show dual presensi records
- [ ] Update laporan features to handle dual presensi
- [ ] Test edge cases (polygon validation, time restrictions, etc.)
