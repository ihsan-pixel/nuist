# TODO: Update UPPM Data Sekolah and Perhitungan Iuran to Use DataSekolah Table

## Tasks
- [x] Add import for DataSekolah model in UppmController.php
- [x] Update dataSekolah method to query DataSekolah instead of User model for teacher counts
- [x] Update logic to fetch jumlah_siswa and teacher fields from DataSekolah for the selected year
- [x] Set values to 0 if no DataSekolah record exists for the madrasah and year
- [x] Test the changes to ensure data loads correctly and filtering by year works
- [x] Update dataSekolah method to automatically calculate Total Nominal UPPM per Tahun based on UppmSetting for the year
- [x] Fetch UppmSetting for the selected tahun_anggaran
- [x] Calculate total_nominal = 12 * (jumlah_siswa * nominal_siswa + jumlah_pns_sertifikasi * nominal_pns_sertifikasi + ...)
- [x] Set total_nominal to 0 if no setting exists
- [x] Test the calculation and display of total nominal
- [x] Update perhitunganIuran method to use data from DataSekolah table instead of UppmSchoolData
- [x] For each madrasah, fetch DataSekolah record for the year
- [x] If DataSekolah exists, use its counts for calculation; else skip or set to 0
- [x] Ensure calculation uses UppmSetting as before
- [x] Test the perhitungan iuran page to verify it uses data sekolah data
