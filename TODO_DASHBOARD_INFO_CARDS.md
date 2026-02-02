# TODO: Dashboard Info Cards Implementation

## Task: Add info cards to mobile pengurus dashboard
- jumlah sekolah (count from Madrasah table)
- jumlah tenaga pendidik (users with madrasah_id, role=tenaga_pendidik)
- jumlah siswa (sum of jumlah_siswa from DataSekolah with latest year)

## Steps:

### 1. Update DashboardController.php
- [x] Add calculation for jumlahSekolah (Madrasah::count())
- [x] Add calculation for jumlahTenagaPendidik (User::whereNotNull('madrasah_id')->where('role', 'tenaga_pendidik')->count())
- [x] Add calculation for jumlahSiswa (sum from DataSekolah with latest year)
- [x] Pass variables to view

### 2. Update dashboard.blade.php
- [x] Add info card section at the top (below header, before performance-card)
- [x] Style the info cards consistently
- [x] Display jumlahSekolah, jumlahTenagaPendidik, jumlahSiswa

### 3. Add Chart for Status Kepegawaian (NEW)
- [x] Add query to get tenaga pendidik count grouped by status_kepegawaian
- [x] Pass statusLabels and statusValues to view
- [x] Add chart section below info cards
- [x] Add ApexCharts initialization script
- [x] Style the chart container for mobile

## Status: COMPLETED ✓


