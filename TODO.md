# TODO: Update 2026 Holidays in Database

## Steps to Complete
- [ ] Update HolidaySeeder.php to include all correct 2026 holidays from the provided list, including missing Isra’ Mi’raj and correcting dates/names.
- [ ] Run the seeder command to update the database: `php artisan db:seed --class=HolidaySeeder`
- [ ] Verify that all 2026 holidays are now in the database (optional: check via tinker or query)

## Notes
- The seeder currently has incorrect dates for several 2026 holidays (e.g., Idul Fitri in March instead of April).
- Added missing Isra’ Mi’raj on 2026-01-16.
- Replaced the entire 2026 section in the holidays array.
