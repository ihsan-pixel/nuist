# PPDB Settings Integration Plan

## Current Status
- Analyzing lp-edit.blade.php and PPDBSetting model
- Identified missing PPDB fields in school profile edit form

## Tasks to Complete

### 1. Update lp-edit.blade.php
- [x] Add new "Pengaturan PPDB" section after existing PPDB fields
- [x] Include Status PPDB (buka/tutup) with visual indicator
- [x] Add Jadwal buka/tutup PPDB (datetime-local inputs)
- [x] Add Kuota total field
- [x] Add Kuota per jurusan (dynamic array inputs)
- [x] Add Jalur pendaftaran (dynamic array inputs)
- [x] Add Biaya pendaftaran field
- [x] Add Jadwal pengumuman field
- [x] Update JavaScript for new dynamic elements

### 2. Update AdminLPController.php
- [x] Add validation rules for new PPDB fields
- [x] Update update() method to handle PPDBJalur creation/updates
- [x] Ensure proper array handling for kuota_jurusan and jalur
- [x] Add proper error handling

### 3. Testing & Verification
- [ ] Test form submission with all new fields
- [ ] Verify validation works correctly
- [ ] Check dynamic array inputs functionality
- [ ] Verify PPDBJalur relationships are created properly
- [ ] Test with existing PPDB settings data

## Files to Edit
- resources/views/ppdb/dashboard/lp-edit.blade.php
- app/Http/Controllers/PPDB/AdminLPController.php

## Notes
- Removed: periode presensi, persyaratan upload, kontak ppdb as per user request
- Focus on core PPDB settings: status, jadwal, kuota, jalur, biaya, pengumuman
- Use existing edit.blade.php as reference for form structure
- Made PPDB settings section always visible (removed @if isset condition)
- Made all PPDB fields optional to allow partial updates
