# TODO: Make Pengurus Dashboard Display Like Super Admin

## Tasks
- [x] Modify DashboardController to pass superAdminStats for pengurus role
- [x] Ensure pengurus gets the same statistics display as super_admin
- [ ] Test the dashboard for pengurus role

## Information Gathered
- Currently, superAdminStats is only set for super_admin in the controller
- The view already has the condition for both super_admin and pengurus to display the statistics
- Need to update the controller to include pengurus in the statistics generation

## Plan
- Edit app/Http/Controllers/DashboardController.php to set superAdminStats for both super_admin and pengurus
- Keep foundationData only for super_admin
- Verify the dashboard displays correctly for pengurus
