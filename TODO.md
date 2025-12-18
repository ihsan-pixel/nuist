# TODO: Modify Banner Modal to Show Only Once Per Session

- [x] Modify DashboardController.php to add session logic for banner_shown
- [x] Update compact in DashboardController.php to pass showBanner
- [x] Update dashboard.blade.php to use @if($showBanner) for modal display and script
- [ ] Test the dashboard to ensure banner appears only once per session

# TODO: Change Dashboard Header to ID Card Layout

- [x] Modify dashboard.blade.php to update dashboard-header div with ID card layout (photo left, details right)
- [x] Add CSS styles for ID card appearance
- [x] Test the mobile dashboard for correct display and responsiveness
