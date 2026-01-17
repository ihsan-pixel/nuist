# TODO: Update UPPM Data Sekolah to Fetch from DataSekolah Table and Calculate Total Nominal

## Tasks
- [x] Add import for DataSekolah model in UppmController.php
- [x] Update dataSekolah method to query DataSekolah instead of User model for teacher counts
- [x] Update logic to fetch jumlah_siswa and teacher fields from DataSekolah for the selected year
- [x] Set values to 0 if no DataSekolah record exists for the madrasah and year
- [x] Test the changes to ensure data loads correctly and filtering by year works
- [ ] Update dataSekolah method to automatically calculate Total Nominal UPPM per Tahun based on UppmSetting for the year
- [ ] Fetch UppmSetting for the selected tahun_anggaran
- [ ] Calculate total_nominal = 12 * (jumlah_siswa * nominal_siswa + jumlah_pns_sertifikasi * nominal_pns_sertifikasi + ...)
- [ ] Set total_nominal to 0 if no setting exists
- [ ] Test the calculation and display of total nominal
