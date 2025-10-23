# TODO: Change Fake GPS Detection from 3 to 2 Readings

## Tasks
- [x] Modify `resources/views/mobile/presensi.blade.php`:
  - [x] Remove page load geolocation call that stores reading2
  - [x] Update button click handler to store reading2 instead of reading3
  - [x] Update location_readings array to send only 2 readings
  - [x] Fix map display issues for mobile devices (enhanced CSS, canvas renderer, _onResize calls, inline styles)
- [x] Update `app/Http/Controllers/PresensiController.php`:
  - [x] Change analysis logic from 3 readings to 2 readings
  - [x] Update distance calculations and issue detection for reading1 vs reading2 only
- [x] Update `app/Http/Controllers/FakeLocationController.php`:
  - [x] Update analysis comments and logic to reference 2 readings instead of 3

## Followup Steps
- [ ] Test mobile presensi functionality
- [ ] Verify fake GPS detection logic with 2 readings
