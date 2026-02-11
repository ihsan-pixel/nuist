# TODO: Fix Talenta Level 1 Upload Bug

## Tasks
- [x] Add use Illuminate\Support\Str; to TalentaController.php
- [x] Remove $areaMapping array and $areaTitle logic in simpanTugasLevel1
- [x] Replace with direct TalentaMateri query using slug
- [x] Change 'data' => json_encode(...) to 'data' => $request->except(['_token', 'area', 'jenis_tugas', 'lampiran'])
- [x] Improve file upload to use Str::uuid() for filename
- [x] Add temporary dd($validated, $request->file('lampiran')); for debug
- [x] Remove dd() after confirming (removed immediately as it would stop execution)
- [ ] Test the upload after changes
