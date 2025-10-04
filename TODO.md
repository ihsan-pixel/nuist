# TODO: Update Presensi System Based on Madrasah Hari KBM and User Conditions

## Tasks
- [x] Modify PresensiController::store method to determine time ranges based on user's madrasah hari_kbm instead of PresensiSettings
- [x] Add logic for special Friday pulang time for hari_kbm=5
- [x] Add condition to skip time validations if user has pemenuhan_beban_kerja_lain = true
- [x] Modify polygon validation to allow presensi in madrasah_tambahan if pemenuhan_beban_kerja_lain = true
- [x] Update presensi create view to show time ranges based on hari_kbm
- [x] Update presensi admin settings view to show fixed time ranges per hari_kbm
- [ ] Test the changes with different scenarios
