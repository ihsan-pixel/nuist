# Presence Polygon Implementation Plan

## 1. Database Modification
- [x] Create a migration to add `polygon_koordinat` (JSON/TEXT) to the `madrasahs` table.
- [x] Create a migration to remove `radius_presensi` from the `presensi_settings` table.

## 2. User Interface for Polygon Drawing
- [x] Identify the madrasah edit view file.
- [x] Integrate Leaflet.js and Leaflet Draw plugin into the view.
- [x] Add a map for drawing and editing the polygon.

## 3. Update Backend Logic
- [x] Modify `MadrasahController` to save/update `polygon_koordinat`.
- [x] Update validation logic in `PresensiController.php` to check against the polygon.

## 4. Remove Legacy Radius System
- [x] Remove `radius_presensi` input from `resources/views/presensi_admin/settings.blade.php`.
- [x] Remove `radius_presensi` handling from `PresensiAdminController.php`.
- [x] Remove `radius_presensi` from the `PresensiSettings` model.

## 5. Follow-up Steps
- [x] Run database migrations.
- [ ] Test polygon drawing and saving.
- [ ] Test presence validation with the new polygon system.
