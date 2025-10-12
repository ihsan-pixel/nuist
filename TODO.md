# TODO: Add SCOD Column to Madrasahs Table

## Completed Steps
- [x] Create migration file: database/migrations/2025_10_05_000000_add_scod_to_madrasahs_table.php
- [x] Update Madrasah model: add 'scod' to fillable array
- [x] Update DataMadrasahController: add 'scod' to fields for completeness check
- [x] Update data_madrasah.blade.php view: add SCOD column header and data cell
- [x] Update MadrasahCompletenessExport: add SCOD to headings and data

## Pending Steps
- [ ] Run composer install (resolve PHP version issue if needed)
- [ ] Run php artisan migrate to apply the migration
- [ ] Test the application to ensure SCOD column displays correctly
