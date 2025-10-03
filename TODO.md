# Fixing Duplicate Entry Error in Presensi Settings

## Steps to Complete:

1. **Resolve Database Connection**  
   [x] Started MySQL service in Laragon. Verified with `php artisan migrate:status`.

2. **Check Migration Status**  
   [x] Clean migration (2025_10_04_000000_clean_presensi_settings_table.php) has been run (batch 35).

3. **Run Clean Migration**  
   [x] Already executed; table truncated.

4. **Re-run PresensiSettingsSeeder**  
   [x] Failed due to legacy singleton unique constraint. Will fix by dropping it first.

5. **Create New Migration to Drop Singleton**  
   [x] Created: 2025_10_03_181902_drop_singleton_from_presensi_settings_table.php

6. **Edit New Migration**  
   [x] Added dropUnique and dropColumn for singleton in up(); added reverse in down().

7. **Run New Migration**  
   [ ] Failed due to MySQL connection refused. Restart MySQL in Laragon and re-run `php artisan migrate` to apply the drop.

8. **Re-run Clean Migration/Truncate**  
   Truncate presensi_settings again to ensure clean state.

9. **Re-run PresensiSettingsSeeder**  
   Execute `php artisan db:seed --class=PresensiSettingsSeeder`.

10. **Verify No Duplicates**  
    Query the table to ensure unique status_kepegawaian_id entries (e.g., count records and check for duplicates via SQL or Tinker).

11. **Test Application**  
    Access the presensi_admin.settings route and submit the form to confirm no errors. Clear caches if needed (`php artisan cache:clear` and `config:clear`).

12. **Monitor and Close**  
    If successful, mark complete. If issues persist, investigate further (e.g., adjust controller logic).
