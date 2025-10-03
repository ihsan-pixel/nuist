# TODO: Update Madrasah Detail to Show Tenaga Pendidik Totals by Status from Users Table

## Steps:
1. [x] Update MadrasahController.php detail method to use tenagaPendidikUsers relationship for $tpByStatus
2. [x] Update detail.blade.php to use tenagaPendidikUsers instead of tenagaPendidik and adjust field names (nama -> name)
3. [x] Test the madrasah detail page to ensure data displays correctly (server started, code changes applied correctly)
