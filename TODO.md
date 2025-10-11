# TODO: Make Export Excel Button Functional in Data Madrasah View

## Steps to Complete:

1. **[ ] Create app/Exports/MadrasahCompletenessExport.php**  
   - Implement an Excel export class using Maatwebsite/Excel's FromCollection concern.  
   - Replicate the logic from DataMadrasahController@index to fetch and process madrasah data for a specific kabupaten, including field_status (✅/❌) and completeness_percentage.  
   - Columns: No, Nama Madrasah, Alamat (status), Logo (status), Latitude (status), Longitude (status), Map Link (status), Polygon (koordinat) (status), Hari KBM (status), Status Guru (status), Kelengkapan (%).  
   - Use the same kabupaten order and grouping logic.

2. **[ ] Update app/Http/Controllers/DataMadrasahController.php**  
   - Add a new public method `export(Request $request)` that:  
     - Validates the 'kabupaten' query parameter.  
     - Fetches data similar to index() but filtered by the specific kabupaten.  
     - Returns an instance of MadrasahCompletenessExport with the data.  
     - Uses Excel::download() to generate and download the file with filename like "Kelengkapan_Data_{kabupaten}.xlsx".  
   - Import necessary classes: use Maatwebsite\Excel\Facades\Excel; use App\Exports\MadrasahCompletenessExport;

3. **[ ] Add route in routes/web.php**  
   - In the 'admin-masterdata' prefix group (middleware: auth, role:super_admin,pengurus), add:  
     - Route::get('/data-madrasah/export', [DataMadrasahController::class, 'export'])->name('admin.data_madrasah.export');  
   - This will handle GET requests with ?kabupaten=... query param.

4. **[ ] Update resources/views/admin/data_madrasah.blade.php**  
   - Replace the custom onclick button with a link: <a href="{{ route('admin.data_madrasah.export', ['kabupaten' => $kabupaten]) }}" class="btn btn-success btn-sm">Export Excel</a>  
   - Remove the XLSX CDN script tag.  
   - Remove the custom exportTableToExcel() JavaScript function.  
   - In DataTable initialization, remove or disable the 'excel' button from buttons array to avoid conflicts (keep others like copy, pdf, etc., or disable all if not needed).  
   - Ensure the table ID remains for DataTables functionality.

## Followup After Edits:
- [ ] Test the export: Navigate to /admin-masterdata/data-madrasah, click export for a kabupaten, verify download with correct data and ✅/❌ statuses.  
- [ ] Clear any route cache if needed: php artisan route:clear  
- [ ] Update this TODO.md by marking steps as completed after each one.
