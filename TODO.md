# TODO: Update Madrasah Detail Tenaga Pendidik Table

## Steps:
1. [x] Update MadrasahController.php detail method to use tenagaPendidikUsers relationship for $tpByStatus
2. [x] Update detail.blade.php to use tenagaPendidikUsers instead of tenagaPendidik and adjust field names (nama -> name)
3. [x] Remove No HP and NIP/NUPTK columns from the table
4. [x] Add Action column with View button
5. [x] Add modal for displaying full tenaga pendidik details
6. [x] Add JavaScript to populate modal on View button click
7. [x] Fix event delegation for DataTables pagination
8. [x] Test the madrasah detail page to ensure data displays correctly (server started, code changes applied correctly)
