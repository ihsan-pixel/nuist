# TODO: Fix TalentaMateri Slug Migration Issue

## Step 1: Modify Existing Migration
- [x] Update `database/migrations/2026_02_11_102525_add_slug_and_improvements_to_talenta_materi.php` to add 'slug' as nullable without unique constraint
- [x] Improve population logic to handle duplicates by appending numbers if needed

## Step 2: Update TalentaMateri Model
- [x] Add anti-duplicate slug generation logic in the `booted` method

## Step 3: Create New Migration for Unique Constraint
- [x] Create a new migration file to add unique constraint on 'slug' column

## Step 4: Run Migrations and Populate Data
- [ ] Run the modified migration
- [ ] Use tinker to populate slugs for existing records
- [ ] Run the new migration to add unique constraint
