# TODO: Implement Real-Time Active Users Display

## Steps to Complete

- [x] Add API route for fetching active users in routes/api.php
- [x] Add API method in ActiveUsersController to return active users as JSON
- [x] Modify resources/views/active-users/index.blade.php to include JavaScript for periodic AJAX updates
- [x] Test the real-time functionality by running the application and checking updates without reload

## Notes
- Use polling every 30 seconds to fetch active users via AJAX
- Update the DOM elements dynamically without page reload
- Ensure authentication is handled for the API endpoint
