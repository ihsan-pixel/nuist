# Fixing Duplicate Entry Error in Presensi Settings

## Steps to Complete:

1. **Resolve Database Connection**  
   Start the MySQL service in Laragon (right-click Laragon tray icon > MySQL > Start, or run as admin). Verify connection with `php artisan tinker` or `php artisan migrate:status`. If config issue, check config/database.php.

2. **Check Migration Status**  
   Verify if the clean migration (2025_10_04_000000_clean_presensi_settings_table.php) has been run using `php artisan migrate:status`.

3. **Run Clean Migration**  
   If not run, execute `php artisan migrate` to truncate the presensi_settings table and remove duplicates.

4. **Re-run PresensiSettingsSeeder**  
   Execute `php artisan db:seed --class=PresensiSettingsSeeder` to repopulate the table with one record per status_kepegawaian_id.

5. **Verify No Duplicates**  
   Query the table to ensure unique status_kepegawaian_id entries (e.g., count records and check for duplicates via SQL or Tinker).

6. **Test Application**  
   Access the presensi_admin.settings route and submit the form to confirm no errors. Clear caches if needed (`php artisan cache:clear` and `config:clear`).

7. **Monitor and Close**  
   If successful, mark complete. If issues persist, investigate further (e.g., adjust controller logic).
