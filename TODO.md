# TODO: Remove Presensi Time Restrictions

## Completed Tasks
- [x] Remove "Belum waktunya presensi masuk" validation in PresensiController.php store() method for all users
- [x] Keep late calculation logic for non-pemenuhan_beban_kerja_lain users when >07:00
- [x] Remove masuk_end from timeRanges in MobileController.php presensi() method for all users
- [x] Update resources/views/mobile/presensi.blade.php to show "anytime" or remove end time display for masuk
