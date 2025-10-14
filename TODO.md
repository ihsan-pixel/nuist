# TODO: Hide "Sekolah Presensi" from Admin Role in Presensi Data

## Steps:
- [ ] Edit resources/views/presensi_admin/index.blade.php: Conditionally hide the "Sekolah Presensi" summary card for users with 'admin' role by wrapping it in @if($user->role !== 'admin') ... @endif.
- [ ] Update TODO.md: Mark the edit as completed.
- [ ] Test: Reload the presensi admin index page as an 'admin' user to confirm the card is hidden, while other elements remain visible.
- [ ] Complete task: Confirm functionality for 'super_admin' (unaffected) and finalize.
