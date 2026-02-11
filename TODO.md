# TODO: Add Aksi Column with Buat Akun Button for Pemateri

## Steps to Complete
- [x] Add new POST route in routes/web.php for creating user for pemateri
- [x] Add createUserForPemateri method in InstumenTalentaController.php
- [x] Edit input-pemateri.blade.php to add Aksi column, button, and modal
- [x] Make nama field read-only in modal
- [x] Fix modal nama field ID conflict
- [x] Add user_id to TalentaPemateri model fillable
- [x] Update controller to link user_id to pemateri
- [x] Hide button when account already created
- [ ] Test the functionality
