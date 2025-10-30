# TODO: Update Fake Location Detection for Both Masuk and Keluar

## Overview
Update the fake location detection page to show both presensi masuk and keluar fake location detections.

## Tasks
- [x] Update FakeLocationController to analyze both masuk and keluar fake locations
- [x] Modify the view to display waktu keluar column and fake location status indicators
- [x] Add summary cards for fake masuk and fake keluar counts
- [x] Update detail modal to show keluar location data
- [x] Test the updated functionality

## Implementation Details
- Controller: app/Http/Controllers/FakeLocationController.php
- View: resources/views/fake-location/index.blade.php
- Added analysis for both is_fake_location and is_fake_location_keluar fields
- Added waktu_keluar column to table
- Added status dropdown to show which type of fake location was detected
- Added summary cards for fake masuk and fake keluar counts
- Updated detail modal to show keluar location data
