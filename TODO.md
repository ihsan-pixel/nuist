# TODO for Password Change Feature for Tenaga Pendidik

## Completed Steps
- [x] Create migration to add 'password_changed' boolean column to users table
- [x] Update User model to include 'password_changed' in fillable
- [x] Modify topbar.blade.php to add change password button for 'tenaga_pendidik' if not password_changed
- [x] Fix spaces in asset paths in master.blade.php and topbar.blade.php
- [x] Update HomeController updatePassword to set password_changed = true after success
- [x] Add check in PresensiController to prevent tenaga_pendidik access to presensi menu if password not changed
- [x] Hide presensi menu in sidebar for tenaga_pendidik if password not changed

## Pending Steps
- [ ] Run the migration: `php artisan migrate` (attempted but failed due to database connection issue - no MySQL connection available)
- [ ] Test the feature by logging in as a user with role 'tenaga_pendidik'
- [ ] Verify that the button appears above logout
- [ ] Change password and confirm button hides after
- [ ] Check that asset paths load correctly without spaces
- [ ] Test presensi access restriction for tenaga_pendidik without password change
