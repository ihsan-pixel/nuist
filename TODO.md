# TODO: Refactor Mobile Controllers and Views

## Overview
Refactor the monolithic MobileController into separate controllers for each mobile menu/feature, organizing them into dedicated folders for better structure and maintainability.

## Tasks
- [ ] Create Mobile folder structure under app/Http/Controllers/
- [ ] Create Dashboard subfolder and controller
- [ ] Create Presensi subfolder and controller
- [ ] Create Jadwal subfolder and controller
- [ ] Create Profile subfolder and controller
- [ ] Create Izin subfolder and controller
- [ ] Create Laporan subfolder and controller
- [ ] Create Monitoring subfolder and controller (for kepala madrasah features)
- [ ] Move methods from MobileController to respective new controllers
- [ ] Update routes in web.php to use new controllers
- [ ] Test routes to ensure no errors
- [ ] Remove moved methods from MobileController (optional, keep for backup)
- [ ] Update any references if needed

## Notes
- Ensure all use statements and dependencies are copied to new controllers
- Keep method names and logic unchanged to avoid errors
- Views remain in resources/views/mobile/ as they are already organized
- Routes will be updated to point to new controller namespaces
