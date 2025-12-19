# TODO: Improve Schedule Section on Mobile Dashboard

## Tasks
- [x] Modify DashboardController to fetch teaching attendance data for each schedule
- [x] Update dashboard.blade.php to display schedules in a carousel format
- [x] Add indicators for presensi mengajar status (sudah/belum) in the carousel
- [x] Test carousel functionality on mobile
- [x] Ensure presensi status is accurately displayed

## Information Gathered
- Schedule section currently displays today's schedules in a simple grid
- Controller fetches schedules from TeachingSchedule model
- Need to check TeachingAttendance model for presensi status

## Dependent Files
- app/Http/Controllers/Mobile/Dashboard/DashboardController.php
- resources/views/mobile/dashboard.blade.php
