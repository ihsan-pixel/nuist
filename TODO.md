# TODO List for Dashboard Statistics Update

## Completed Tasks
- [x] Updated getAdminStatistics method in DashboardController.php to count only 'tenaga_pendidik' role for total_teachers and status breakdowns
- [x] Updated getSuperAdminStatistics method in DashboardController.php to count only 'tenaga_pendidik' role for total_teachers and status breakdowns
- [x] Verified changes exclude 'admin' role from tenaga pendidik counts as per task requirements

## Followup Steps
- [ ] Test the dashboard for admin and super_admin roles to ensure counts only include 'tenaga_pendidik'
- [ ] Verify that the "Total Tenaga Pendidik" and "Detail Statistik Tenaga Pendidik" sections display correct numbers
