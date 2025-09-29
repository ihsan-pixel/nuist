# TODO: Implementasi Menu Data Yayasan

## Completed Tasks
- [x] Create Yayasan model with fillable fields and relationships
- [x] Create migration for yayasans table (name, alamat, latitude, longitude, map_link, visi, misi)
- [x] Create migration to add yayasan_id foreign key to madrasahs table
- [x] Update Madrasah model to add belongsTo Yayasan relationship
- [x] Create YayasanController with index, store, update, destroy methods
- [x] Add routes for yayasan CRUD under masterdata prefix
- [x] Update sidebar to add "Data Yayasan" menu for super_admin
- [x] Create view resources/views/masterdata/yayasan/index.blade.php with table, modals for add/edit
- [x] Update DashboardController getFoundationData() to use Yayasan model instead of Madrasah

## Pending Tasks
- [ ] Run `php artisan migrate` to apply database changes (requires DB connection)
- [ ] Test the new menu and CRUD functionality
- [ ] Seed initial yayasan data if needed
- [ ] Verify dashboard shows yayasan info for super_admin

## Notes
- Yayasan is parent entity of madrasahs
- Menu only visible to super_admin
- No logo upload or Excel import for yayasan (simplified)
- Dashboard foundation data now pulls from yayasan table
