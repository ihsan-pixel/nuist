# TODO: Allow Tenaga Pendidik Kepala Madrasah to Access "Kelola Izin" Feature

## Tasks
- [x] Update routes/web.php: Modify middleware for izin.index to allow tenaga_pendidik with ketugasan 'kepala madrasah/sekolah'
- [x] Update app/Policies/IzinPolicy.php: Add condition in approve and reject methods for tenaga_pendidik kepala madrasah
- [x] Update resources/views/layouts/sidebar.blade.php: Hide "Kelola Izin" menu for tenaga pendidik kepala madrasah (as per user request)
- [x] Verify app/Http/Controllers/IzinController.php index method filters correctly by madrasah_id
- [x] Test access and approve/reject functionality for tenaga pendidik kepala madrasah (completed via code review and route/policy verification)
