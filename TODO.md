# TODO: Update Import Template for Tenaga Pendidik

## Task Description
Update the import template for tenaga pendidik to match the database schema and input form, ensuring import can be performed successfully.

## Information Gathered
- Import class requires: nama, email, tempat_lahir, tanggal_lahir, no_hp, kartanu, nip, nuptk, npk, madrasah_id, pendidikan_terakhir, tahun_lulus, program_studi, status_kepegawaian_id, tmt, ketugasan, mengajar, alamat, pemenuhan_beban_kerja_lain, madrasah_id_tambahan
- Input form has pemenuhan_beban_kerja_lain and madrasah_id_tambahan as optional
- Database includes these fields
- Current CSV templates missing optional fields, causing import failure
- Template files in public/template/ do not exist

## Plan
1. [ ] Update TenagaPendidikImport.php to make pemenuhan_beban_kerja_lain and madrasah_id_tambahan optional
2. [ ] Update test CSV files to include optional columns
3. [ ] Create template files in public/template/: tenaga_pendidik_template.xlsx, tenaga_pendidik_template.csv, tenaga_pendidik_import_structure.txt, panduan_import_tenaga_pendidik.txt
4. [ ] Update view to reflect correct required and optional fields

## Dependent Files
- app/Imports/TenagaPendidikImport.php
- test_import_tenaga_pendidik.csv
- test_import_tenaga_pendidik_updated.csv
- public/template/ (new directory and files)
- resources/views/masterdata/tenaga-pendidik/index.blade.php

## Followup Steps
- Test import with updated template
- Verify data integrity after import
