# TODO: Update MGMP Image Upload to Use public_html (Document Root)

## Plan Summary
Mengubah penyimpanan gambar logo MGMP dari `storage/app/public` ke `public/uploads/mgmp_logos` agar bisa diakses langsung melalui document root di mode production.

## Steps

- [x] 1. Modify MGMPController.php - store() method to save to public/uploads/mgmp_logos
- [x] 2. Modify MGMPController.php - update() method to save to public/uploads/mgmp_logos
- [x] 3. Modify data-mgmp.blade.php - change image source from storage/ to uploads/
- [x] 4. Create directory public/uploads/mgmp_logos

## Status: COMPLETED
