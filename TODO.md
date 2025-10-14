# TODO: Fix Tenaga Pendidik Madrasah Association for Super Admin Presensi

## Overview
The task is to ensure that users with role 'tenaga_pendidik' are correctly associated with their madrasah_id in the presensi data for super admin view. The current code logic in PresensiAdminController is correct, but data inconsistency (wrong/null madrasah_id in users table) causes mismatches. The plan involves creating a sync command to fix existing data and updating the import for future prevention.

## Steps

- [ ] Step 1: Create new Artisan command `FixTenagaPendidikMadrasahCommand` in `app/Console/Commands/` to scan and fix madrasah_id for users with role 'tenaga_pendidik'. Logic: For users with null/invalid madrasah_id, attempt to match and update based on available data (e.g., log mismatches; if TenagaPendidik has data, sync from there; otherwise, set to a default or skip).

- [ ] Step 2: Register the command in `app/Console/Kernel.php` by adding it to the `$commands` array.

- [ ] Step 3: Update `app/Imports/TenagaPendidikImport.php` to improve madrasah_id handling: If CSV provides school name instead of ID, add logic to find and set madrasah_id by name; enhance validation and logging.

- [ ] Step 4: Update `database/seeders/DatabaseSeeder.php` to call the sync command after imports/seeders run (e.g., `$this->call(FixTenagaPendidikMadrasahCommand::class);`).

- [ ] Step 5: In `app/Http/Controllers/PresensiAdminController.php`, add optional validation in index() and getData() to exclude users with null madrasah_id and log warnings for debugging.

- [ ] Step 6: Test the fix:
  - Run `php artisan fix:tenaga-madrasah` (or command name) to sync data.
  - Reload the presensi admin page for super admin and verify teachers appear under correct madrasah.
  - Test real-time update, modals, and export.
  - Re-import sample data if needed to confirm prevention.

- [ ] Step 7: Update TODO.md with completion marks and remove if all done.

## Notes
- Assume TenagaPendidik table may have legacy data; if empty, the command will log users needing manual fix.
- After all steps, use attempt_completion to finalize.
