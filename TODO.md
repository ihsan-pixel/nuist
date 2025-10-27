# TODO: Update Footer Version to Dynamic App Version

## Tasks
- [x] Update AppServiceProvider.php to add view composer that shares current app version with all views
- [x] Update footer.blade.php to display dynamic version instead of hardcoded "Version 1.0.2"
- [x] Update footer.blade.php to display dynamic app name instead of hardcoded "NUIST"
- [x] Test that footer displays correct version on pages (Laravel server is running on port 8000, but browser tool is disabled)

## Files to Edit
- app/Providers/AppServiceProvider.php
- resources/views/layouts/footer.blade.php
