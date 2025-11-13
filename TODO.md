# TODO: PPDB Index Page Implementation

## Overview
Create the PPDB index page with a complete navbar and school listing display. The page should show available schools for PPDB registration with status indicators.

## Tasks

### 1. Create PPDB Navbar Partial
- [ ] Create `resources/views/partials/ppdb/navbar.blade.php`
- [ ] Include logo and app name (NUIST PPDB)
- [ ] Center menu: Beranda, Sekolah, About, Kontak
- [ ] Right side: Button "Daftar" linking to registration
- [ ] Responsive design with mobile hamburger menu

### 2. Create PPDB Footer Partial
- [ ] Create `resources/views/partials/ppdb/footer.blade.php`
- [ ] Include contact information and links
- [ ] LP. Ma'arif branding

### 3. Update PPDB Index Page
- [ ] Update `resources/views/ppdb/index.blade.php`
- [ ] Include navbar and footer partials
- [ ] Display grid of available schools
- [ ] Show school name, status, registration period
- [ ] Include countdown timer for active registrations
- [ ] Add registration buttons for open schools

### 4. Add PPDB Custom CSS
- [ ] Update `assets/css/ppdb-custom.css`
- [ ] Navbar styling
- [ ] School cards styling
- [ ] Countdown timer styling
- [ ] Responsive breakpoints

### 5. Test and Verify
- [ ] Test page loads without 500 error
- [ ] Verify navbar functionality
- [ ] Check school listing displays correctly
- [ ] Test responsive design
- [ ] Verify links work properly

## Dependencies
- PPDBController.index() returns $sekolah collection
- PPDBSetting model with relationships
- Existing routes in web.php
- Tailwind CSS framework

## Acceptance Criteria
- Page loads successfully at /ppdb
- Navbar displays logo, menu, and register button
- School grid shows available PPDB schools
- Status indicators (open/closed)
- Countdown timers for active registrations
- Responsive design works on mobile/desktop
