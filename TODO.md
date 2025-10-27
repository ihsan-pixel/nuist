# Dashboard Layout Rearrangement Task

## Task: Rearrange dashboard layout for admin, super_admin, and pengurus roles
- Move welcome card, address card, and location card to left side (col-xl-4)
- Move statistics to right side (col-xl-8) in desktop mode
- Ensure responsive design for mobile

## Steps:
- [ ] Modify resources/views/dashboard/index.blade.php to create two-column layout
- [ ] For admin role: left column with welcome, madrasah address/location; right with admin stats
- [ ] For super_admin/pengurus: left column with welcome, foundation address/location; right with super admin stats
- [ ] Test layout in browser for desktop and mobile responsiveness
