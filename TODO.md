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

## New Feature: Dedicated Monitor Map Page for Kepala Madrasah

## Completed Tasks
- [x] Create dedicated route /mobile/monitor-map for kepala madrasah only
- [x] Add monitorMap() method in MobileController with authorization check
- [x] Create resources/views/mobile/monitor-map.blade.php with full-page map layout
- [x] Implement date selector for choosing different dates
- [x] Add navigation menu buttons (list view, presensi, dashboard)
- [x] Style the dedicated page with consistent mobile theme
- [x] Add "Monitor Map Presensi" button on main presensi page for kepala madrasah
- [x] Ensure proper responsive design and mobile optimization
