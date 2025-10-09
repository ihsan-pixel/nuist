# TODO: Allow Empty NPK in Tenaga Pendidik Import

## Tasks
- [x] Remove 'npk' from requiredFields in app/Imports/TenagaPendidikImport.php
- [x] Update resources/views/masterdata/tenaga-pendidik/index.blade.php to move 'npk' from required to optional columns
- [ ] Test the import functionality with empty NPK

## TODO: Modify Presensi Logic for Extended Time and Keterangan

## Tasks
- [x] Extend presensi masuk time until 08:00 for tenaga_pendidik
- [x] Add keterangan "tidak terlambat" if presensi before 07:00
- [x] Add keterangan "Terlambat X menit" if presensi after 07:00, calculated from 07:00
- [x] Reject presensi after 08:00
- [x] Reject presensi before 06:00
- [x] Apply same logic for updating alpha status to hadir
- [ ] Test the presensi functionality with different times
