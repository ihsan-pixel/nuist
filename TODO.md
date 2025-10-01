# TODO: Update Dashboard for Pengurus Role

## Tasks
- [x] Edit resources/views/dashboard/index.blade.php to add statistics cards for super_admin and pengurus roles
- [x] Add cards displaying total_madrasah, total_teachers, total_admin, total_super_admin, total_school_principals from superAdminStats
- [x] Add breakdown section for status kepegawaian if needed
- [ ] Test the dashboard display for pengurus role

## Information Gathered
- DashboardController already passes superAdminStats to both super_admin and pengurus
- The view has a placeholder for the right side but no content in the cards
- superAdminStats includes totals for madrasah, teachers, admins, super_admins, school principals, and status breakdown

## Plan
- Replace the empty card body with actual statistics display
- Use similar structure as admin dashboard but with overall stats
