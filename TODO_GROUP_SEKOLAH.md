# TODO: Group Sekolah by Kabupaten and Sort by SCOD

## Objective
Mengelompokkan tampilan sekolah per kabupaten dan mengurutkan dengan scod nya di halaman sekolah blade

## Steps

- [x] 1. Update LandingController.php - ubah query untuk group by kabupaten dan order by scod
- [x] 2. Update sekolah.blade.php - tampilkan sekolah grouped by kabupaten dengan header
- [ ] 3. Test perubahan dengan membuka halaman sekolah

## Details

### Step 1: LandingController.php
```php
// Sebelum
$madrasahs = Madrasah::all();

// Sesudah - group by kabupaten, sort by scod
$madrasahs = Madrasah::orderBy('kabupaten')->orderBy('scod')->get();
// Group by kabupaten for display
$groupedMadrasahs = $madrasahs->groupBy('kabupaten');
```

### Step 2: sekolah.blade.php
- Loop through grouped madrasahs
- Tampilkan header kabupaten
- Tampilkan cards sekolah di bawah header masing-masing

## Status
- [x] Plan confirmed by user
- [x] In Progress
- [ ] Completed - waiting for testing

