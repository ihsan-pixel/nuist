# TODO: Modify Banner Modal to Show Only Once Per Session

- [x] Modify DashboardController.php to add session logic for banner_shown
- [x] Update compact in DashboardController.php to pass showBanner
- [x] Update dashboard.blade.php to use @if($showBanner) for modal display and script
- [ ] Test the dashboard to ensure banner appears only once per session
