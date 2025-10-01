# TODO: Display Foundation Address and Location on Pengurus Dashboard

## Tasks
- [x] Modify DashboardController to pass foundationData for pengurus role
- [x] Update dashboard view to show foundation address and location for pengurus
- [x] Test the dashboard for pengurus role

## Information Gathered
- Currently, foundationData is only set for super_admin in the controller
- The view has the foundation address and map section only for super_admin
- Need to include pengurus in both controller data passing and view conditions

## Plan
- Edit app/Http/Controllers/DashboardController.php to set foundationData for both super_admin and pengurus
- Update resources/views/dashboard/index.blade.php to show foundation section for both roles
- Verify the dashboard displays correctly for pengurus
