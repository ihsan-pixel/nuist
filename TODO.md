# TODO: Remove Presensi Time Restrictions

## Completed Tasks
- [x] Remove "Belum waktunya presensi masuk" validation in PresensiController.php store() method for all users
- [x] Keep late calculation logic for non-pemenuhan_beban_kerja_lain users when >07:00
- [x] Remove masuk_end from timeRanges in MobileController.php presensi() method for all users
- [x] Update resources/views/mobile/presensi.blade.php to show "anytime" or remove end time display for masuk

## New Feature: Map Display for Presensi Monitoring

## Completed Tasks
- [x] Add map data preparation in MobileController::presensi() for kepala madrasah users
- [x] Create mapData array with user locations, status, and presensi details
- [x] Add map section to mobile presensi view with Leaflet integration
- [x] Implement custom markers (green for presensi, red for belum presensi)
- [x] Add popup information showing user details, presensi times, and location
- [x] Include legend and summary statistics below the map
- [x] Ensure responsive design matching existing mobile theme
- [x] Test map rendering and marker functionality
