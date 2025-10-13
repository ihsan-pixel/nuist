## 1. Install Dependencies
- [x] Run `npm install react react-dom react-leaflet leaflet` to add React and map support.
- [x] Update `package.json` with new dependencies.
- [x] Run `npm run build` to rebuild assets with Vite.

## 2. Create React Components
- [x] Create directory `resources/js/presensi-admin/` if not exists.
- [x] Create `PresensiApp.jsx` - Main React component for super admin dashboard.
- [x] Create `components/SummaryCards.jsx` - Component for displaying summary statistics.
- [x] Create `components/KabupatenSection.jsx` - Component for organizing data by kabupaten.
- [x] Create `components/MadrasahCard.jsx` - Component for displaying individual madrasah data.
- [x] Create `components/UserModal.jsx` - Modal for displaying user details and presensi history.
- [x] Create `components/MadrasahModal.jsx` - Modal for displaying madrasah details with map.

## 3. Configure Vite for React
- [x] Install `@vitejs/plugin-react` plugin.
- [x] Update `vite.config.js` to include React plugin and PresensiApp.jsx in build inputs.
- [x] Fix ESM import issues in vite.config.js.

## 4. Update Blade Template
- [x] Modify `resources/views/presensi_admin/index.blade.php` to conditionally render React app for super_admin role.
- [x] Add script section to load React components for super_admin users.

## 5. Test Integration
- [ ] Run `npm run build` to ensure assets compile correctly.
- [ ] Test the super admin dashboard to verify React components render properly.
- [ ] Verify modal functionality for user and madrasah details.
- [ ] Test real-time updates and date filtering.

## 6. Finalize
- [ ] Update TODO.md with completion status.
- [ ] Ensure all components are working as expected.
