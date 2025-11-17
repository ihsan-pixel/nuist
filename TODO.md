# TODO: Implement Dual Presensi for Users with Beban Kerja Lain

## Tasks:
- [ ] Modify PresensiController::storePresensi() to determine madrasah based on polygon check and save madrasah_id
- [ ] Allow multiple presensi per day for users with pemenuhan_beban_kerja_lain (up to 2 per day: one per madrasah)
- [ ] Update riwayat presensi view to show madrasah name based on presensi.madrasah_id
- [ ] Update PresensiAdminController monitoring to handle multiple presensi per user per day
- [ ] Test the changes to ensure presensi works for both madrasahs
