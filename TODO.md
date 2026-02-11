# TODO: Change talenta_pemateri to many-to-many with talenta_materi

## Steps:
- [x] Create new migration for pivot table talenta_pemateri_materi
- [x] Modify existing talenta_pemateri migration to remove materi_id
- [x] Update TalentaPemateri model: change to belongsToMany materis
- [x] Update TalentaMateri model: add belongsToMany pemateris
- [x] Update InstumenTalentaController: validation to array, store to attach
- [x] Update blade table display: show multiple materis as badges
- [x] Run php artisan migrate:fresh (Failed due to missing users table migration)
- [ ] Test functionality (after fixing users table issue)

# TODO: Change talenta_fasilitator to many-to-many with talenta_materi

## Steps:
- [x] Create new migration for pivot table talenta_fasilitator_materi
- [x] Create migration to drop materi_id from talenta_fasilitator
- [x] Update TalentaFasilitator model: change to belongsToMany materis
- [x] Update TalentaMateri model: add belongsToMany fasilitators
- [x] Update InstumenTalentaController: storeFasilitator validation to array, store to attach
- [x] Update input-fasilitator.blade.php: multiple select and display multiple materis as badges
- [x] Update penilaianFasilitator method to use with('materis')
- [x] Run migrations
- [ ] Test functionality
