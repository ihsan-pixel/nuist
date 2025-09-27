# TODO for Password Change Feature for Tenaga Pendidik

## Completed Steps
- [x] Create migration to add 'password_changed' boolean column to users table
- [x] Update User model to include 'password_changed' in fillable
- [x] Modify topbar.blade.php to add change password button for 'tenaga_pendidik' if not password_changed
- [x] Fix spaces in asset paths in master.blade.php and topbar.blade.php
- [x] Update HomeController updatePassword to set password_changed = true after success

## Pending Steps
- [ ] Run the migration: `php artisan migrate`
- [ ] Test the feature by logging in as a user with role 'tenaga_pendidik'
- [ ] Verify that the button appears above logout
- [ ] Change password and confirm button hides after
- [ ] Check that asset paths load correctly without spaces
