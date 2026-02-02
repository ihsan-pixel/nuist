# TODO: Add Employment Status Chart to Mobile Pengurus Dashboard

## Task: Display tenaga pendidik chart grouped by status kepegawaian below info cards section

### Steps:

- [x] 1. Update DashboardController.php - Add query for tenaga pendidik by status kepegawaian
- [x] 2. Update dashboard.blade.php - Add chart container and initialization script
- [x] 3. Update mobile.blade.php - Add ApexCharts library
- [x] 4. Test the chart displays correctly

## Implementation Details:

**Controller Changes:**
- Add `User::whereNotNull('madrasah_id')` query to get count by `status_kepegawaian_id`
- Join with `status_kepegawaian` table via relationship to get status names
- Pass `tenagaPendidikByStatus` array with status names and counts to view

**View Changes:**
- Add chart container below info cards section
- Include ApexCharts initialization for donut chart
- Mobile-friendly styling with legend below chart
- Show empty state message when no data

**Layout Changes:**
- Added ApexCharts library script to mobile.blade.php

## Status: COMPLETED

## Changes Made:

### app/Http/Controllers/Mobile/Pengurus/PengurusController.php
- Added `$tenagaPendidikByStatus` query that groups users by `status_kepegawaian_id`
- Eager loads `statusKepegawaian` relationship
- Maps results to include `status_name` and `count`

### resources/views/mobile/pengurus/dashboard.blade.php
- Added chart section with donut chart container
- Included legend showing status name and count for each category
- Added JavaScript to initialize ApexCharts donut chart
- Added empty state message when no data
- Mobile-responsive design with appropriate sizing

### resources/views/layouts/mobile.blade.php
- Added ApexCharts library script for mobile views

