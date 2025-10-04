# TODO: Add Kabupaten Column to Madrasah

## Information Gathered
- Current madrasahs table columns: id, name, alamat, latitude, longitude, map_link, logo, polygon_koordinat, hari_kbm, timestamps
- Madrasah model fillable: name, alamat, latitude, longitude, map_link, logo, polygon_koordinat, hari_kbm
- Controller validates: name, alamat, latitude, longitude, map_link, logo, polygon_koordinat, hari_kbm
- Import maps: name (0), alamat (1), latitude (2), longitude (3), map_link (4), logo (5)
- Views display: name, alamat, hari_kbm, lokasi in index; name, alamat, hari_kbm in detail

## Plan
1. [x] Create migration to add 'kabupaten' column (string, nullable) to madrasahs table
2. [x] Update Madrasah model: add 'kabupaten' to fillable array
3. [x] Update MadrasahController: add 'kabupaten' validation in store() and update() methods
4. [x] Update MadrasahImport: add kabupaten mapping (row[1])
5. [x] Update index.blade.php: add "Kabupaten" column header and data, add kabupaten input in add/edit modals
6. [x] Update detail.blade.php: display kabupaten in madrasah info section

## Dependent Files
- [x] database/migrations/ (new migration)
- [x] app/Models/Madrasah.php
- [x] app/Http/Controllers/MadrasahController.php
- [x] app/Imports/MadrasahImport.php
- [x] resources/views/masterdata/madrasah/index.blade.php
- [x] resources/views/masterdata/madrasah/detail.blade.php

## Followup Steps
- [ ] Run php artisan migrate to apply migration (DB connection needed)
- [ ] Update import template if needed
- [ ] Test add/edit/import functionality
- [ ] Verify display in all views
