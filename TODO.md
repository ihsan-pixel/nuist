# TODO for Profile Madrasah/Sekolah Menu

This TODO tracks the implementation of the new "Profile Madrasah/Sekolah" menu, restricted to super_admin and pengurus roles, displaying a card-based grid of madrasah profiles with educator counts.

- [x] Update app/Models/Madrasah.php: Add hasMany relationship to TenagaPendidik for eager-loading/counting educators per madrasah.
- [x] Add profile() method to app/Http/Controllers/MadrasahController.php: Fetch madrasahs based on role (all for super_admin/pengurus), with withCount('tenagaPendidik'), and return view 'masterdata.madrasah.profile'.
- [x] Add route in routes/web.php: GET /madrasah/profile -> MadrasahController@profile, named 'madrasah.profile' (place under admin middleware if applicable).
- [x] Create resources/views/masterdata/madrasah/profile.blade.php: Extend layouts.master, add breadcrumb, display madrasahs in Bootstrap grid (col-md-3 cards), each with logo image (or placeholder), name as title, alamat snippet, and badge for tenaga pendidik count (e.g., green if >0, styled like image).
- [x] Update resources/views/layouts/sidebar.blade.php: Add <li><a href="{{ route('madrasah.profile') }}">Profile Madrasah/Sekolah</a></li> after Data Madrasah/Sekolah, wrapped in @if(in_array($userRole, ['super_admin', 'pengurus'])).
- [x] Test: Run `php artisan route:list | grep madrasah` to verify route; login as super_admin/pengurus, navigate to menu, confirm cards display (seed data if needed via `php artisan db:seed`); check admin role hides menu.

# TODO for Displaying Total Tenaga Pendidik Count in Madrasah Detail

- [ ] Update app/Http/Controllers/MadrasahController.php detail method: Add $totalTp = $madrasah->tenagaPendidik->count(); and include in compact.
- [ ] Update resources/views/masterdata/madrasah/detail.blade.php: Add a card for total tenaga pendidik count before the status-based cards.
