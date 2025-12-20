# TODO: Fix Alpha Stats in Mobile Dashboard

## Tasks to Complete

- [x] Modify dashboard() method in DashboardController to calculate additional alpha days for past working days without presensi
- [x] Update getStatsData() method to include queries for monthlyPresensi, monthlyHolidays, and hariKbm, and calculate additional alpha days
- [x] Ensure logic matches calendar: past days, working days (based on hariKbm), not holidays, no presensi
- [x] Test calendar navigation and verify stats update correctly
- [x] Check for different hariKbm settings (5 or 6 days)
- [x] Ensure kehadiranPercent and totalBasis are accurate
