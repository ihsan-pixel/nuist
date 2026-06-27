<?php

namespace App\Http\Controllers;

use App\Exports\SkYayasanUserImportTemplateExport;
use App\Models\Madrasah;
use App\Models\SkYayasanDocument;
use App\Models\SkYayasanImportBatch;
use App\Models\SkYayasanRequest;
use App\Models\SkYayasanTemplate;
use App\Models\User;
use App\Models\Yayasan;
use App\Support\SkYayasanImportSynchronizer;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class SkYayasanController extends Controller
{
    public function dashboard(Request $request): View
    {
        $this->ensureSuperAdmin();

        $statusCounts = SkYayasanRequest::query()
            ->selectRaw('current_status, COUNT(*) as total')
            ->groupBy('current_status')
            ->pluck('total', 'current_status');

        $documentCounts = SkYayasanDocument::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $latestRequests = SkYayasanRequest::query()
            ->with(['madrasah', 'employee', 'template'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        $schoolSummaries = Madrasah::query()
            ->withCount([
                'skYayasanRequests as total_pengajuan_sk' => fn ($query) => $query->where('request_type', 'perpanjangan'),
                'skYayasanRequests as total_pengajuan_pending' => fn ($query) => $query->whereIn('current_status', ['submitted', 'reviewed']),
                'skYayasanRequests as total_sk_terbit' => fn ($query) => $query->where('current_status', 'published'),
            ])
            ->havingRaw('total_pengajuan_sk > 0')
            ->orderByDesc('total_pengajuan_pending')
            ->orderByDesc('total_sk_terbit')
            ->take(10)
            ->get();

        return view('sk-yayasan.dashboard', [
            'statusCounts' => $statusCounts,
            'documentCounts' => $documentCounts,
            'latestRequests' => $latestRequests,
            'schoolSummaries' => $schoolSummaries,
            'pendingImportBatches' => SkYayasanImportBatch::query()->where('status', 'pending_review')->count(),
            'rejectedImportBatches' => SkYayasanImportBatch::query()->where('status', 'rejected')->count(),
            'activeTemplates' => SkYayasanTemplate::query()->where('is_active', true)->count(),
            'publishedThisMonth' => SkYayasanDocument::query()
                ->where('status', 'published')
                ->whereMonth('published_at', now()->month)
                ->whereYear('published_at', now()->year)
                ->count(),
        ]);
    }

    public function schoolIndex(Request $request): View
    {
        $user = $this->ensureSchoolAdmin();
        $madrasahId = (int) $user->madrasah_id;

        $submissions = SkYayasanRequest::query()
            ->with(['employee.statusKepegawaian', 'document', 'importBatch'])
            ->where('madrasah_id', $madrasahId)
            ->when($request->filled('status'), fn ($query) => $query->where('current_status', $request->string('status')->toString()))
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

        $employees = User::query()
            ->with('statusKepegawaian')
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->orderBy('name')
            ->get();

        $statusCounts = SkYayasanRequest::query()
            ->where('madrasah_id', $madrasahId)
            ->selectRaw('current_status, COUNT(*) as total')
            ->groupBy('current_status')
            ->pluck('total', 'current_status');

        $latestSyncedImport = SkYayasanImportBatch::query()
            ->where('madrasah_id', $madrasahId)
            ->where('status', 'synced')
            ->latest('synced_at')
            ->first();

        $latestSchoolSubmissionBatch = SkYayasanImportBatch::query()
            ->where('madrasah_id', $madrasahId)
            ->latest('uploaded_at')
            ->first();

        $submissionHistoryBatches = SkYayasanImportBatch::query()
            ->with(['reviewer', 'requests.employee.statusKepegawaian', 'requests.document'])
            ->where('madrasah_id', $madrasahId)
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status')->toString();

                if ($status === 'rejected') {
                    $query->where(function ($builder) {
                        $builder->where('status', 'rejected')
                            ->orWhereHas('requests', fn ($requestQuery) => $requestQuery->where('current_status', 'rejected'));
                    });

                    return;
                }

                $query->whereHas('requests', fn ($requestQuery) => $requestQuery->where('current_status', $status));
            })
            ->latest('uploaded_at')
            ->paginate(10, ['*'], 'history_page')
            ->withQueryString();

        return view('sk-yayasan.sekolah-index', [
            'submissions' => $submissions,
            'submissionHistoryBatches' => $submissionHistoryBatches,
            'employees' => $employees,
            'statusCounts' => $statusCounts,
            'importBatches' => SkYayasanImportBatch::query()
                ->with(['reviewer', 'requests.employee', 'rows'])
                ->where('madrasah_id', $madrasahId)
                ->latest('uploaded_at')
                ->take(8)
                ->get(),
            'hasExistingSchoolSubmission' => $latestSchoolSubmissionBatch !== null,
            'latestSchoolSubmissionBatch' => $latestSchoolSubmissionBatch,
            'autoSelectedEmployeeIds' => old('employee_ids', $latestSyncedImport?->matched_user_ids ?? []),
            'latestSyncedImport' => $latestSyncedImport,
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee'])
                ->whereHas('request', fn ($query) => $query->where('madrasah_id', $madrasahId))
                ->where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get(),
        ]);
    }

    public function schoolImportTemplate()
    {
        $this->ensureSchoolAdmin();

        return Excel::download(new SkYayasanUserImportTemplateExport(), 'template-import-sk-yayasan.xlsx');
    }

    public function importSchoolUsers(Request $request): RedirectResponse
    {
        return back()->with('error', 'Gunakan form pengajuan terpadu untuk mengirim guru/pegawai beserta semua berkas wajib.');
    }

    public function storeSchoolSubmission(Request $request): RedirectResponse
    {
        $user = $this->ensureSchoolAdmin();
        $madrasahId = (int) $user->madrasah_id;

        $hasExistingBatch = SkYayasanImportBatch::query()
            ->where('madrasah_id', $madrasahId)
            ->exists();

        if ($hasExistingBatch) {
            return back()->with('error', 'Sekolah ini sudah pernah mengajukan batch SK Yayasan. Gunakan menu riwayat upload untuk memperbarui data yang sudah dikirim.');
        }

        $request->validate([
            'submission_letter_number' => ['required', 'string', 'max:255'],
            'submission_letter_date' => ['required', 'date'],
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['required', 'integer', 'distinct'],
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'fakta_integritas_file' => ['required', 'file', 'mimes:pdf'],
            'penilaian_perilaku_file' => ['required', 'file', 'mimes:pdf'],
        ]);

        $employees = User::query()
            ->with('statusKepegawaian')
            ->whereIn('id', $request->input('employee_ids', []))
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        if ($employees->count() !== count($request->input('employee_ids', []))) {
            return back()
                ->withErrors(['employee_ids' => 'Sebagian pegawai yang dipilih tidak valid untuk sekolah ini.'])
                ->withInput();
        }

        $openRequestEmployeeIds = SkYayasanRequest::query()
            ->whereIn('employee_id', $employees->keys())
            ->whereIn('current_status', ['submitted', 'reviewed', 'approved'])
            ->pluck('employee_id')
            ->all();

        $availableEmployees = $employees->reject(fn ($employee) => in_array($employee->id, $openRequestEmployeeIds, true));
        $skippedEmployees = $employees->filter(fn ($employee) => in_array($employee->id, $openRequestEmployeeIds, true))->pluck('name')->all();

        if ($availableEmployees->isEmpty()) {
            return back()
                ->withErrors([
                    'employee_ids' => 'Semua pegawai yang dipilih masih memiliki pengajuan SK Yayasan yang belum selesai: ' . implode(', ', $skippedEmployees) . '.',
                ])
                ->withInput();
        }

        $report = $this->inspectSchoolSheetFile($request->file('excel_file'), (int) $user->madrasah_id);
        $batch = DB::transaction(function () use ($request, $report, $user) {
            $excelPath = $request->file('excel_file')->store('sk-yayasan/uploads/excel');
            $faktaPath = $request->file('fakta_integritas_file')->store('sk-yayasan/uploads/fakta-integritas');
            $penilaianPath = $request->file('penilaian_perilaku_file')->store('sk-yayasan/uploads/penilaian-perilaku');

            $batch = SkYayasanImportBatch::query()->create([
                'madrasah_id' => (int) $user->madrasah_id,
                'uploaded_by' => $user->id,
                'status' => 'pending_review',
                'original_filename' => $request->file('excel_file')->getClientOriginalName(),
                'stored_path' => $excelPath,
                'fakta_integritas_filename' => $request->file('fakta_integritas_file')->getClientOriginalName(),
                'fakta_integritas_path' => $faktaPath,
                'penilaian_perilaku_filename' => $request->file('penilaian_perilaku_file')->getClientOriginalName(),
                'penilaian_perilaku_path' => $penilaianPath,
                'total_rows' => count($report['rows']),
                'valid_rows' => $report['valid_count'],
                'invalid_rows' => $report['invalid_count'],
                'headings_valid' => $report['headings_valid'],
                'missing_headings' => $report['missing_headings'],
                'unexpected_headings' => $report['unexpected_headings'],
                'payload_rows' => $report['rows'],
                'matched_user_ids' => $report['valid_user_ids'],
                'uploaded_at' => now(),
            ]);

            $batch->rows()->createMany($this->buildImportBatchRowsPayload($report['rows']));

            return $batch;
        });
        DB::transaction(function () use ($availableEmployees, $madrasahId, $user, $batch, $request) {
            foreach ($availableEmployees as $employee) {
                SkYayasanRequest::create([
                    'madrasah_id' => $madrasahId,
                    'import_batch_id' => $batch->id,
                    'employee_id' => $employee->id,
                    'submitted_by' => $user->id,
                    'request_number' => $this->generateRequestNumber(),
                    'submission_letter_number' => $request->string('submission_letter_number')->trim()->toString(),
                    'submission_letter_date' => $request->date('submission_letter_date'),
                    'request_type' => 'perpanjangan',
                    'employment_category' => $employee->statusKepegawaian?->name ?? $employee->ketugasan,
                    'current_status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            }
        });

        $message = $availableEmployees->count() . ' pengajuan perpanjangan SK berhasil dikirim.';

        if (!$report['headings_valid']) {
            $message .= ' Format kolom Excel belum sesuai template dan akan ditinjau saat review super admin.';
        }

        if ($batch->invalid_rows > 0) {
            $message .= ' Ada ' . $batch->invalid_rows . ' baris data yang perlu perhatian saat review.';
        }

        if (!empty($skippedEmployees)) {
            $message .= ' Pegawai yang dilewati karena masih memiliki pengajuan aktif: ' . implode(', ', $skippedEmployees) . '.';
        }

        return back()->with('success', $message);
    }

    public function updateRejectedSchoolSubmission(Request $request, SkYayasanImportBatch $batch): RedirectResponse
    {
        $user = $this->ensureSchoolAdmin();
        $this->authorizeImportBatchAccess($batch);

        if (!in_array($batch->status, ['pending_review', 'rejected'], true)) {
            return back()->with('error', 'Batch ini sudah diproses dan tidak dapat diperbarui lagi.');
        }

        $batch->loadMissing('requests');
        $firstRequest = $batch->requests->first();

        $validated = $request->validate([
            'submission_letter_number' => [$firstRequest ? 'required' : 'nullable', 'string', 'max:255'],
            'submission_letter_date' => [$firstRequest ? 'required' : 'nullable', 'date'],
            'excel_file' => ['nullable', 'file', 'mimes:xlsx,xls,csv'],
            'fakta_integritas_file' => ['nullable', 'file', 'mimes:pdf'],
            'penilaian_perilaku_file' => ['nullable', 'file', 'mimes:pdf'],
        ]);

        $newLetterNumber = $firstRequest
            ? trim((string) ($validated['submission_letter_number'] ?? ''))
            : null;
        $newLetterDate = $firstRequest && !empty($validated['submission_letter_date'])
            ? Carbon::parse($validated['submission_letter_date'])->toDateString()
            : null;
        $oldLetterNumber = $firstRequest ? (string) ($firstRequest->submission_letter_number ?? '') : null;
        $oldLetterDate = $firstRequest ? optional($firstRequest->submission_letter_date)->toDateString() : null;

        $hasFileUpdate = $request->hasFile('excel_file')
            || $request->hasFile('fakta_integritas_file')
            || $request->hasFile('penilaian_perilaku_file');
        $hasLetterUpdate = $firstRequest
            ? ($newLetterNumber !== $oldLetterNumber || $newLetterDate !== $oldLetterDate)
            : false;

        if (!$hasFileUpdate && !$hasLetterUpdate) {
            return back()->with('error', $firstRequest
                ? 'Tidak ada perubahan yang dikirim. Ubah nomor/tanggal surat atau upload file baru.'
                : 'Tidak ada perubahan yang dikirim. Upload file baru untuk memperbarui batch ini.');
        }

        $report = $request->hasFile('excel_file')
            ? $this->inspectSchoolSheetFile($request->file('excel_file'), (int) $user->madrasah_id)
            : null;

        DB::transaction(function () use ($request, $batch, $user, $newLetterNumber, $newLetterDate, $report, $firstRequest) {
            $batchUpdatePayload = [
                'status' => 'pending_review',
                'uploaded_by' => $user->id,
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'synced_at' => null,
                'uploaded_at' => now(),
            ];

            if ($request->hasFile('excel_file')) {
                if (!empty($batch->stored_path) && Storage::exists($batch->stored_path)) {
                    Storage::delete($batch->stored_path);
                }

                $batchUpdatePayload = array_merge($batchUpdatePayload, [
                    'original_filename' => $request->file('excel_file')->getClientOriginalName(),
                    'stored_path' => $request->file('excel_file')->store('sk-yayasan/uploads/excel'),
                    'total_rows' => count($report['rows']),
                    'valid_rows' => $report['valid_count'],
                    'invalid_rows' => $report['invalid_count'],
                    'headings_valid' => $report['headings_valid'],
                    'missing_headings' => $report['missing_headings'],
                    'unexpected_headings' => $report['unexpected_headings'],
                    'payload_rows' => $report['rows'],
                    'matched_user_ids' => $report['valid_user_ids'],
                ]);
            }

            if ($request->hasFile('fakta_integritas_file')) {
                if (!empty($batch->fakta_integritas_path) && Storage::exists($batch->fakta_integritas_path)) {
                    Storage::delete($batch->fakta_integritas_path);
                }

                $batchUpdatePayload['fakta_integritas_filename'] = $request->file('fakta_integritas_file')->getClientOriginalName();
                $batchUpdatePayload['fakta_integritas_path'] = $request->file('fakta_integritas_file')->store('sk-yayasan/uploads/fakta-integritas');
            }

            if ($request->hasFile('penilaian_perilaku_file')) {
                if (!empty($batch->penilaian_perilaku_path) && Storage::exists($batch->penilaian_perilaku_path)) {
                    Storage::delete($batch->penilaian_perilaku_path);
                }

                $batchUpdatePayload['penilaian_perilaku_filename'] = $request->file('penilaian_perilaku_file')->getClientOriginalName();
                $batchUpdatePayload['penilaian_perilaku_path'] = $request->file('penilaian_perilaku_file')->store('sk-yayasan/uploads/penilaian-perilaku');
            }

            $batch->update($batchUpdatePayload);

            if ($report !== null) {
                $batch->rows()->delete();
                $batch->rows()->createMany($this->buildImportBatchRowsPayload($report['rows']));
            }

            if ($firstRequest) {
                $batch->requests()->update([
                    'submission_letter_number' => $newLetterNumber,
                    'submission_letter_date' => $newLetterDate,
                    'current_status' => 'submitted',
                    'review_notes' => null,
                    'submitted_by' => $user->id,
                    'reviewed_by' => null,
                    'submitted_at' => now(),
                    'reviewed_at' => null,
                ]);
            }
        });

        return back()->with('success', 'Berkas pengajuan berhasil diperbarui dan dikirim ulang untuk direview.');
    }

    public function updateSchoolImportBatchRows(Request $request, SkYayasanImportBatch $batch): RedirectResponse
    {
        $user = $this->ensureSchoolAdmin();
        $this->authorizeImportBatchAccess($batch);

        if (!in_array($batch->status, ['pending_review', 'rejected'], true)) {
            return back()->with('error', 'Data import pada batch ini sudah diproses dan tidak dapat diedit lagi.');
        }

        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.row_number' => ['required', 'integer', 'min:1'],
            'rows.*.excel_no' => ['nullable', 'string', 'max:100'],
            'rows.*.source_nuist_id' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nama' => ['nullable', 'string', 'max:255'],
            'rows.*.source_gelar' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tempat_lahir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tanggal_lahir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nip_maarif' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nuptk' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nomor_kartanu' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tmt_pertama' => ['nullable', 'string', 'max:255'],
            'rows.*.source_masa_kerja' => ['nullable', 'string', 'max:255'],
            'rows.*.source_pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tahun_lulus' => ['nullable', 'string', 'max:255'],
            'rows.*.source_program_studi' => ['nullable', 'string', 'max:255'],
            'rows.*.source_mapel_tugas' => ['nullable', 'string', 'max:255'],
            'rows.*.source_penilaian_kinerja' => ['nullable', 'string', 'max:255'],
            'rows.*.source_keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $report = $this->inspectEditableImportRows($validated['rows'], (int) $batch->madrasah_id);

        DB::transaction(function () use ($batch, $report, $user) {
            $batch->update([
                'status' => 'pending_review',
                'uploaded_by' => $user->id,
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'synced_at' => null,
                'uploaded_at' => now(),
                'total_rows' => count($report['rows']),
                'valid_rows' => $report['valid_count'],
                'invalid_rows' => $report['invalid_count'],
                'headings_valid' => true,
                'missing_headings' => [],
                'unexpected_headings' => [],
                'payload_rows' => $report['rows'],
                'matched_user_ids' => $report['valid_user_ids'],
            ]);

            $batch->rows()->delete();
            $batch->rows()->createMany($this->buildImportBatchRowsPayload($report['rows']));

            $batch->requests()->update([
                'current_status' => 'submitted',
                'review_notes' => null,
                'submitted_by' => $user->id,
                'reviewed_by' => null,
                'submitted_at' => now(),
                'reviewed_at' => null,
            ]);
        });

        return back()->with('success', 'Data import berhasil diperbarui dan dikirim ulang untuk direview.');
    }

    public function downloadImportBatchAttachment(SkYayasanImportBatch $batch, string $type)
    {
        $this->authorizeImportBatchAccess($batch);

        $attachments = [
            'excel' => [
                'path' => $batch->stored_path,
                'name' => $batch->original_filename,
            ],
            'fakta_integritas' => [
                'path' => $batch->fakta_integritas_path,
                'name' => $batch->fakta_integritas_filename,
            ],
            'penilaian_perilaku' => [
                'path' => $batch->penilaian_perilaku_path,
                'name' => $batch->penilaian_perilaku_filename,
            ],
        ];

        abort_unless(isset($attachments[$type]), 404);

        $attachment = $attachments[$type];

        abort_unless(!empty($attachment['path']) && Storage::exists($attachment['path']), 404);

        $absolutePath = Storage::path($attachment['path']);
        $filename = $attachment['name'] ?: basename($attachment['path']);
        $mimeType = Storage::mimeType($attachment['path']) ?: 'application/octet-stream';

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($filename) . '"',
        ]);
    }

    public function reviewImportBatch(Request $request, SkYayasanImportBatch $batch): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'action' => ['required', 'in:sync,reject'],
            'review_notes' => ['nullable', 'string'],
        ]);

        if ($batch->status !== 'pending_review') {
            return back()->with('error', 'Batch import ini sudah diproses sebelumnya.');
        }

        if ($validated['action'] === 'reject') {
            DB::transaction(function () use ($batch, $validated) {
                $batch->update([
                    'status' => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'review_notes' => $validated['review_notes'] ?? null,
                    'reviewed_at' => now(),
                ]);

                $batch->requests()->update([
                    'current_status' => 'rejected',
                    'review_notes' => $validated['review_notes'] ?? null,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);
            });

            return back()->with('success', 'Batch import ditolak. Admin sekolah dapat mengunggah perbaikan data.');
        }

        if (!$batch->headings_valid || $batch->invalid_rows > 0) {
            return back()->with('error', 'Batch import belum valid untuk disinkronkan. Tolak dulu lalu minta admin sekolah memperbaiki file.');
        }

        $batch->loadMissing('rows');
        $payloadRows = $batch->rows;

        if ($payloadRows->isEmpty()) {
            return back()->with('error', 'Batch import tidak memiliki data yang bisa disinkronkan.');
        }

        $synchronizer = new SkYayasanImportSynchronizer((int) $batch->madrasah_id);
        $updated = 0;
        $unchanged = 0;

        DB::transaction(function () use ($payloadRows, $synchronizer, &$updated, &$unchanged, $batch, $validated) {
            $payloadRows->each(function ($row) use ($synchronizer, &$updated, &$unchanged) {
                $userId = $row->matched_user_id;
                $user = $userId ? User::query()->find($userId) : null;

                if (!$user) {
                    $unchanged++;
                    return;
                }

                $hasChanges = $synchronizer->syncRow(
                    $user,
                    $row->user_payload ?? [],
                    $row->sk_payload ?? []
                );

                if ($hasChanges) {
                    $updated++;
                    return;
                }

                $unchanged++;
            });

            $batch->update([
                'status' => 'synced',
                'reviewed_by' => auth()->id(),
                'review_notes' => $validated['review_notes'] ?? null,
                'reviewed_at' => now(),
                'synced_at' => now(),
            ]);
        });

        return back()->with('success', "Batch import berhasil disinkronkan. {$updated} data diperbarui, {$unchanged} baris tidak mengubah data.");
    }

    public function updateImportBatchRows(Request $request, SkYayasanImportBatch $batch): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if (!in_array($batch->status, ['pending_review', 'rejected'], true)) {
            return back()->with('error', 'Batch import ini sudah diproses dan tidak dapat diedit lagi.');
        }

        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.row_number' => ['required', 'integer', 'min:1'],
            'rows.*.excel_no' => ['nullable', 'string', 'max:100'],
            'rows.*.source_nuist_id' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nama' => ['nullable', 'string', 'max:255'],
            'rows.*.source_gelar' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tempat_lahir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tanggal_lahir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nip_maarif' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nuptk' => ['nullable', 'string', 'max:255'],
            'rows.*.source_nomor_kartanu' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tmt_pertama' => ['nullable', 'string', 'max:255'],
            'rows.*.source_masa_kerja' => ['nullable', 'string', 'max:255'],
            'rows.*.source_pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'rows.*.source_tahun_lulus' => ['nullable', 'string', 'max:255'],
            'rows.*.source_program_studi' => ['nullable', 'string', 'max:255'],
            'rows.*.source_mapel_tugas' => ['nullable', 'string', 'max:255'],
            'rows.*.source_penilaian_kinerja' => ['nullable', 'string', 'max:255'],
            'rows.*.source_keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $report = $this->inspectEditableImportRows($validated['rows'], (int) $batch->madrasah_id);
        $wasRejected = $batch->status === 'rejected';

        DB::transaction(function () use ($batch, $report, $wasRejected) {
            $batch->update([
                'status' => 'pending_review',
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'synced_at' => null,
                'total_rows' => count($report['rows']),
                'valid_rows' => $report['valid_count'],
                'invalid_rows' => $report['invalid_count'],
                'headings_valid' => true,
                'missing_headings' => [],
                'unexpected_headings' => [],
                'payload_rows' => $report['rows'],
                'matched_user_ids' => $report['valid_user_ids'],
            ]);

            $batch->rows()->delete();
            $batch->rows()->createMany($this->buildImportBatchRowsPayload($report['rows']));

            if ($wasRejected) {
                $batch->requests()->update([
                    'current_status' => 'submitted',
                    'review_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }
        });

        return back()->with('success', 'Data review import berhasil diperbarui. Batch kembali ke status pending review.');
    }

    public function destroyImportBatch(SkYayasanImportBatch $batch): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $pathsToDelete = array_filter([
            $batch->stored_path,
            $batch->fakta_integritas_path,
            $batch->penilaian_perilaku_path,
        ]);

        DB::transaction(function () use ($batch) {
            $requestIds = $batch->requests()->pluck('id');

            if ($requestIds->isNotEmpty()) {
                SkYayasanDocument::query()->whereIn('request_id', $requestIds)->delete();
            }

            $batch->rows()->delete();
            $batch->requests()->delete();
            $batch->delete();
        });

        foreach ($pathsToDelete as $path) {
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        return back()->with('success', 'Pengajuan batch berhasil dihapus.');
    }

    public function superAdminPengajuan(Request $request): View
    {
        $this->ensureSuperAdmin();

        $submissions = SkYayasanRequest::query()
            ->with(['madrasah', 'employee.statusKepegawaian', 'submitter', 'reviewer', 'template', 'document', 'importBatch'])
            ->where('current_status', '!=', 'rejected')
            ->whereDoesntHave('importBatch', fn ($query) => $query->where('status', 'rejected'))
            ->when($request->filled('status'), fn ($query) => $query->where('current_status', $request->string('status')->toString()))
            ->when($request->filled('madrasah_id'), fn ($query) => $query->where('madrasah_id', (int) $request->madrasah_id))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = trim((string) $request->q);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('request_number', 'like', '%' . $keyword . '%')
                        ->orWhere('submission_letter_number', 'like', '%' . $keyword . '%')
                        ->orWhereHas('employee', fn ($employee) => $employee->where('name', 'like', '%' . $keyword . '%'))
                        ->orWhereHas('madrasah', fn ($madrasah) => $madrasah->where('name', 'like', '%' . $keyword . '%'));
                });
            })
            ->latest('submitted_at')
            ->paginate(12)
            ->withQueryString();

        $importBatchQuery = SkYayasanImportBatch::query()
            ->with(['madrasah', 'uploader', 'reviewer', 'rows'])
            ->withCount('requests');

        $pendingImportBatches = (clone $importBatchQuery)
            ->where('status', 'pending_review')
            ->latest('uploaded_at')
            ->paginate(8, ['*'], 'pending_import_page')
            ->withQueryString();

        $syncedImportBatches = (clone $importBatchQuery)
            ->where('status', 'synced')
            ->latest('synced_at')
            ->latest('uploaded_at')
            ->paginate(8, ['*'], 'synced_import_page')
            ->withQueryString();

        return view('sk-yayasan.pengajuan-index', [
            'submissions' => $submissions,
            'pendingImportBatches' => $pendingImportBatches,
            'syncedImportBatches' => $syncedImportBatches,
            'importPreviewColumns' => SkYayasanImportSynchronizer::expectedHeadings(),
            'madrasahs' => Madrasah::query()->orderBy('name')->get(['id', 'name']),
            'templates' => SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function updateSubmissionStatus(Request $request, SkYayasanRequest $submission): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'current_status' => ['required', 'in:reviewed,approved,rejected'],
            'review_notes' => ['nullable', 'string'],
            'template_id' => ['nullable', 'integer', 'exists:sk_yayasan_templates,id'],
        ]);

        $submission->update([
            'current_status' => $validated['current_status'],
            'review_notes' => $validated['review_notes'] ?? null,
            'template_id' => $validated['template_id'] ?? $submission->template_id,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Status pengajuan SK Yayasan berhasil diperbarui.');
    }

    public function templateIndex(): View
    {
        $this->ensureSuperAdmin();

        return view('sk-yayasan.template-index', [
            'templates' => SkYayasanTemplate::query()->oldest()->get(),
        ]);
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'document_title' => ['required', 'string', 'max:255'],
            'document_number_format' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        SkYayasanTemplate::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'umum',
            'document_title' => $validated['document_title'],
            'document_number_format' => $validated['document_number_format'] ?? null,
            'description' => $validated['description'] ?? null,
            'body' => $validated['body'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return back()->with('success', 'Template SK Yayasan berhasil ditambahkan.');
    }

    public function updateTemplate(Request $request, SkYayasanTemplate $template): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'document_title' => ['required', 'string', 'max:255'],
            'document_number_format' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $template->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'umum',
            'document_title' => $validated['document_title'],
            'document_number_format' => $validated['document_number_format'] ?? null,
            'description' => $validated['description'] ?? null,
            'body' => $validated['body'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return back()->with('success', 'Template SK Yayasan berhasil diperbarui.');
    }

    public function previewTemplatePdf(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'document_title' => ['required', 'string', 'max:255'],
            'document_number_format' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $issuedDate = now();
        $placeholders = $this->templatePreviewPlaceholders(
            $validated['document_title'],
            $validated['document_number_format'] ?? null,
            $issuedDate
        );

        $renderedContent = $this->renderTemplate($validated['body'], $placeholders);
        $documentNumber = $placeholders['{{nomor_sk}}'];

        $document = (object) [
            'document_number' => $documentNumber,
            'rendered_content' => $renderedContent,
            'template' => (object) [
                'document_title' => $validated['document_title'],
            ],
            'issued_date' => $issuedDate,
            'signer_name' => $placeholders['{{nama_penandatangan}}'],
            'signer_position' => $placeholders['{{jabatan_penandatangan}}'],
        ];

        $submission = (object) [
            'madrasah' => (object) [
                'name' => $placeholders['{{nama_sekolah}}'],
            ],
            'employee' => (object) [
                'name' => $placeholders['{{nama_pegawai}}'],
                'statusKepegawaian' => (object) [
                    'name' => $placeholders['{{status_kepegawaian}}'],
                ],
            ],
        ];

        $pdf = PDF::loadView('pdf.sk-yayasan-template', [
            'document' => $document,
            'submission' => $submission,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $documentNumber . '.pdf"');
    }

    public function destroyTemplate(SkYayasanTemplate $template): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($template->requests()->exists() || $template->documents()->exists()) {
            return back()->with('error', 'Template sudah dipakai pada pengajuan atau dokumen SK Yayasan.');
        }

        $template->delete();

        return back()->with('success', 'Template SK Yayasan berhasil dihapus.');
    }

    public function duplicateTemplate(SkYayasanTemplate $template): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $copyName = $template->name . ' (Copy)';
        $suffix = 2;

        while (SkYayasanTemplate::query()->where('name', $copyName)->exists()) {
            $copyName = $template->name . ' (Copy ' . $suffix . ')';
            $suffix++;
        }

        SkYayasanTemplate::create([
            'name' => $copyName,
            'category' => $template->category,
            'document_title' => $template->document_title,
            'document_number_format' => $template->document_number_format,
            'description' => $template->description,
            'body' => $template->body,
            'is_active' => false,
        ]);

        return back()->with('success', 'Template SK Yayasan berhasil diduplikasi.');
    }

    public function generateIndex(Request $request): View
    {
        $this->ensureSuperAdmin();

        $eligibleRequests = $this->generateEligibleRequestsConstraint();

        $schools = Madrasah::query()
            ->whereHas('skYayasanRequests', $eligibleRequests)
            ->withCount([
                'skYayasanRequests as generate_requests_count' => $eligibleRequests,
            ])
            ->orderByDesc('generate_requests_count')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('sk-yayasan.generate-index', [
            'schools' => $schools,
            'totalRequestsCount' => SkYayasanRequest::query()->where($eligibleRequests)->count(),
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee', 'request.madrasah'])
                ->where('status', 'published')
                ->latest('published_at')
                ->take(10)
                ->get(),
        ]);
    }

    public function generateSchoolIndex(Madrasah $madrasah): View
    {
        $this->ensureSuperAdmin();

        $eligibleRequests = $this->generateEligibleRequestsConstraint();
        $templates = SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get();

        $requests = SkYayasanRequest::query()
            ->with([
                'madrasah',
                'employee.statusKepegawaian',
                'template',
                'document.template',
                'importBatch',
            ])
            ->where('madrasah_id', $madrasah->id)
            ->where($eligibleRequests)
            ->orderByRaw("
                CASE
                    WHEN current_status = 'published' THEN 2
                    WHEN current_status = 'approved' THEN 1
                    WHEN current_status = 'reviewed' THEN 0
                    WHEN current_status = 'submitted' THEN 0
                    ELSE 3
                END
            ")
            ->orderByDesc('reviewed_at')
            ->orderByDesc('submitted_at')
            ->paginate(12)
            ->withQueryString();

        $requests->getCollection()->transform(function (SkYayasanRequest $submission) use ($templates) {
            return $this->decorateGenerateSubmission($submission, $templates);
        });

        return view('sk-yayasan.generate-school-index', [
            'madrasah' => $madrasah,
            'requests' => $requests,
            'templates' => $templates,
            'coreData' => $this->buildSchoolSkCoreData(
                $madrasah,
                $requests->getCollection()
                    ->pluck('document')
                    ->filter()
                    ->sortByDesc(fn (SkYayasanDocument $document) => optional($document->generated_at)?->timestamp ?? 0)
                    ->first()
            ),
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee', 'request.madrasah'])
                ->where('status', 'published')
                ->whereHas('request', fn (Builder $query) => $query->where('madrasah_id', $madrasah->id))
                ->latest('published_at')
                ->take(10)
                ->get(),
        ]);
    }

    public function generateDocument(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'request_id' => ['required', 'integer', 'exists:sk_yayasan_requests,id'],
            'template_id' => ['required', 'integer', 'exists:sk_yayasan_templates,id'],
            'issued_date' => ['required', 'date'],
            'document_number' => ['nullable', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:50'],
            'document_number_start' => ['nullable', 'string', 'max:255'],
            'established_at' => ['required', 'string', 'max:255'],
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_position' => ['nullable', 'string', 'max:255'],
            'copy_recipient_1' => ['required', 'string', 'max:255'],
            'copy_recipient_2' => ['required', 'string', 'max:255'],
            'publication_notes' => ['nullable', 'string'],
        ]);

        $submission = SkYayasanRequest::query()
            ->with(['madrasah.yayasan', 'employee.statusKepegawaian', 'template', 'document.template', 'importBatch'])
            ->whereKey($validated['request_id'])
            ->firstOrFail();

        $canGenerate = $this->submissionCanBeGenerated($submission);

        if (!$canGenerate) {
            return back()->with('error', 'Dokumen hanya bisa digenerate dari pengajuan yang sudah disetujui atau batch yang sudah tersinkron.');
        }

        $activeTemplates = SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get();
        $template = $this->resolveTemplateForSubmission($submission, $activeTemplates)
            ?? SkYayasanTemplate::query()->findOrFail($validated['template_id']);
        $issuedDate = Carbon::parse($validated['issued_date']);
        $documentNumber = !empty($validated['document_number'])
            ? $validated['document_number']
            : $this->generateDocumentNumber($template, $submission, $issuedDate);

        DB::transaction(function () use ($submission, $template, $validated, $issuedDate, $documentNumber) {
            $placeholders = $this->buildTemplatePlaceholders($submission, [
                'nomor_sk' => $documentNumber,
                'tanggal_terbit' => $issuedDate->translatedFormat('d F Y'),
                'tanggal_mulai' => '01 Juli ' . $issuedDate->format('Y'),
                'tanggal_selesai' => '30 Juni ' . $issuedDate->copy()->addYear()->format('Y'),
                'tahun_sk' => $issuedDate->format('Y'),
                'tahun_sk_berikutnya' => $issuedDate->copy()->addYear()->format('Y'),
                'tahun_penerbitan_sk' => $validated['school_year'],
                'nomor_sk_yayasan_mulai' => $validated['document_number_start'] ?? '-',
                'nama_penandatangan' => $validated['signer_name'],
                'jabatan_penandatangan' => $validated['signer_position'] ?? 'Ketua Yayasan',
                'ditetapkan_di' => $validated['established_at'],
                'tanggal_penetapan' => $issuedDate->translatedFormat('d F Y'),
                'tembusan_1' => $validated['copy_recipient_1'],
                'tembusan_2' => $validated['copy_recipient_2'],
                'catatan_penerbitan' => $validated['publication_notes'] ?? '-',
            ]);

            $renderedContent = $this->renderTemplate($template->body, $placeholders);

            SkYayasanDocument::query()->updateOrCreate(
                ['request_id' => $submission->id],
                [
                    'template_id' => $template->id,
                    'generated_by' => auth()->id(),
                    'document_number' => $documentNumber,
                    'issued_date' => $issuedDate->toDateString(),
                    'signer_name' => $validated['signer_name'],
                    'signer_position' => $validated['signer_position'] ?? 'Ketua Yayasan',
                    'publication_notes' => $validated['publication_notes'] ?? null,
                    'meta_payload' => [
                        'school_year' => $validated['school_year'],
                        'document_number_start' => $validated['document_number_start'] ?? null,
                        'established_at' => $validated['established_at'],
                        'copy_recipient_1' => $validated['copy_recipient_1'],
                        'copy_recipient_2' => $validated['copy_recipient_2'],
                    ],
                    'rendered_content' => $renderedContent,
                    'status' => $submission->current_status === 'published' ? 'published' : 'draft',
                    'generated_at' => now(),
                ]
            );

            $submission->update([
                'template_id' => $template->id,
            ]);
        });

        return back()->with('success', 'Draft SK Yayasan berhasil digenerate.');
    }

    public function publishDocument(SkYayasanDocument $document): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $document->load('request');

        $document->update([
            'status' => 'published',
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        $document->request->update([
            'current_status' => 'published',
        ]);

        return back()->with('success', 'SK Yayasan berhasil diterbitkan.');
    }

    public function downloadDocument(SkYayasanDocument $document)
    {
        $document->load(['request.madrasah.yayasan', 'request.employee.statusKepegawaian', 'template']);
        $this->authorizeDocumentAccess($document);

        $pdf = PDF::loadView('pdf.sk-yayasan-template', [
            'document' => $document,
            'submission' => $document->request,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $document->document_number . '.pdf"');
    }

    private function generateEligibleRequestsConstraint(): Closure
    {
        return function (Builder $query) {
            $query->whereIn('current_status', ['approved', 'published'])
                ->orWhere(function (Builder $syncedQuery) {
                    $syncedQuery->whereIn('current_status', ['submitted', 'reviewed'])
                        ->whereHas('importBatch', fn (Builder $batchQuery) => $batchQuery->where('status', 'synced'));
                });
        };
    }

    private function submissionCanBeGenerated(SkYayasanRequest $submission): bool
    {
        return in_array($submission->current_status, ['approved', 'published'], true)
            || (
                in_array($submission->current_status, ['submitted', 'reviewed'], true)
                && $submission->importBatch?->status === 'synced'
            );
    }

    private function decorateGenerateSubmission(SkYayasanRequest $submission, Collection $templates): SkYayasanRequest
    {
        $submission->submission_type_label = $this->formatSubmissionTypeLabel($submission);
        $submission->resolved_template = $this->resolveTemplateForSubmission($submission, $templates);

        return $submission;
    }

    private function buildSchoolSkCoreData(Madrasah $madrasah, ?SkYayasanDocument $document = null): array
    {
        $issueDate = $document?->issued_date ?? now();
        $year = (int) $issueDate->format('Y');
        $copyRecipients = $this->resolveSchoolCopyRecipients($madrasah);
        $meta = $document?->meta_payload ?? [];

        return [
            'school_year' => $meta['school_year'] ?? ($year . '-' . ($year + 1)),
            'document_number_start' => $meta['document_number_start'] ?? '',
            'signer_name' => $document?->signer_name ?? '',
            'signer_position' => $document?->signer_position ?? 'Ketua Yayasan',
            'established_at' => $meta['established_at'] ?? 'Yogyakarta',
            'issued_date' => $issueDate->format('Y-m-d'),
            'copy_recipient_1' => $meta['copy_recipient_1'] ?? $copyRecipients['copy_recipient_1'],
            'copy_recipient_2' => $meta['copy_recipient_2'] ?? $copyRecipients['copy_recipient_2'],
        ];
    }

    private function resolveSchoolCopyRecipients(Madrasah $madrasah): array
    {
        $specialMadrasahIds = [6, 7, 43, 45];
        $kabupaten = trim((string) ($madrasah->kabupaten ?? 'setempat'));
        $kabupaten = $kabupaten !== '' ? $kabupaten : 'setempat';

        if (in_array((int) $madrasah->id, $specialMadrasahIds, true)) {
            return [
                'copy_recipient_1' => 'Kepala Kantor Wilayah Kementerian Agama DIY',
                'copy_recipient_2' => 'Kepala Kantor Kementerian Agama Kabupaten ' . $kabupaten,
            ];
        }

        return [
            'copy_recipient_1' => 'Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY',
            'copy_recipient_2' => 'Kepala Balai Pendidikan Menengah Kabupaten ' . $kabupaten,
        ];
    }

    private function formatSubmissionTypeLabel(SkYayasanRequest $submission): string
    {
        $employmentType = $this->detectEmploymentType($submission);
        $requestType = trim((string) $submission->request_type);

        if ($requestType === '' && $employmentType !== null) {
            return strtoupper($employmentType);
        }

        $requestLabel = $requestType !== ''
            ? Str::of($requestType)->replace('_', ' ')->title()->toString()
            : 'Pengajuan';

        return trim($requestLabel . ' ' . strtoupper((string) $employmentType));
    }

    private function resolveTemplateForSubmission(SkYayasanRequest $submission, Collection $templates): ?SkYayasanTemplate
    {
        if ($submission->document?->template) {
            return $submission->document->template;
        }

        if ($submission->template) {
            return $submission->template;
        }

        $templateKey = $this->resolveTemplateKey($submission);

        if ($templateKey === null) {
            return null;
        }

        return $templates
            ->map(function (SkYayasanTemplate $template) use ($templateKey) {
                return [
                    'template' => $template,
                    'score' => $this->scoreTemplateMatch($template, $templateKey),
                ];
            })
            ->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->pluck('template')
            ->first();
    }

    private function resolveTemplateKey(SkYayasanRequest $submission): ?string
    {
        $employmentType = $this->detectEmploymentType($submission);

        if ($employmentType === null) {
            return null;
        }

        if (in_array($employmentType, ['gtt', 'ptt'], true)) {
            return $employmentType;
        }

        $requestText = $this->normalizeTemplateText((string) $submission->request_type);

        if ($this->containsTemplateWord($requestText, 'pengangkatan')) {
            return 'pengangkatan_' . $employmentType;
        }

        return $employmentType;
    }

    private function detectEmploymentType(SkYayasanRequest $submission): ?string
    {
        $source = $this->normalizeTemplateText(implode(' ', array_filter([
            $submission->employment_category,
            $submission->request_type,
            $submission->employee?->statusKepegawaian?->name,
            $submission->employee?->ketugasan,
        ])));

        foreach (['gty', 'pty', 'gtt', 'ptt'] as $type) {
            if ($this->containsTemplateWord($source, $type)) {
                return $type;
            }
        }

        return null;
    }

    private function scoreTemplateMatch(SkYayasanTemplate $template, string $templateKey): int
    {
        $haystack = $this->normalizeTemplateText(implode(' ', array_filter([
            $template->name,
            $template->category,
            $template->document_title,
        ])));

        $employmentType = Str::startsWith($templateKey, 'pengangkatan_')
            ? Str::after($templateKey, 'pengangkatan_')
            : $templateKey;

        if (!$this->containsTemplateWord($haystack, $employmentType)) {
            return 0;
        }

        $score = 20;

        if (in_array($templateKey, ['gty', 'pty'], true)) {
            $score += $this->containsTemplateWord($haystack, 'pengangkatan') ? -10 : 30;
        } elseif (in_array($templateKey, ['pengangkatan_gty', 'pengangkatan_pty'], true)) {
            $score += $this->containsTemplateWord($haystack, 'pengangkatan') ? 40 : -10;
        } else {
            $score += $this->containsTemplateWord($haystack, 'pengangkatan') ? -5 : 25;
        }

        if ($this->containsTemplateWord($this->normalizeTemplateText((string) $template->name), $employmentType)) {
            $score += 15;
        }

        if ($templateKey === 'pengangkatan_' . $employmentType
            && Str::contains($this->normalizeTemplateText((string) $template->name), 'pengangkatan ' . $employmentType)) {
            $score += 20;
        }

        if ($templateKey === $employmentType
            && Str::startsWith($this->normalizeTemplateText((string) $template->name), $employmentType . ' ')) {
            $score += 20;
        }

        return $score;
    }

    private function normalizeTemplateText(string $value): string
    {
        $normalized = Str::of($value)
            ->lower()
            ->replace(['/', '-', '_'], ' ')
            ->replaceMatches('/[^a-z0-9\s]+/', ' ')
            ->squish()
            ->toString();

        return trim($normalized);
    }

    private function containsTemplateWord(string $haystack, string $word): bool
    {
        return preg_match('/\b' . preg_quote($word, '/') . '\b/', $haystack) === 1;
    }

    private function ensureSuperAdmin(): User
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'super_admin', 403, 'Unauthorized access');

        return $user;
    }

    private function ensureSchoolAdmin(): User
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'admin', 403, 'Unauthorized access');
        abort_unless($user->madrasah_id, 403, 'Akun admin belum terhubung ke sekolah.');

        return $user;
    }

    private function authorizeDocumentAccess(SkYayasanDocument $document): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_unless(
            $user->role === 'admin' && (int) $user->madrasah_id === (int) $document->request->madrasah_id,
            403,
            'Unauthorized access'
        );
    }

    private function authorizeImportBatchAccess(SkYayasanImportBatch $batch): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_unless(
            $user->role === 'admin' && (int) $user->madrasah_id === (int) $batch->madrasah_id,
            403,
            'Unauthorized access'
        );
    }

    private function inspectSchoolSheetFile($file, int $madrasahId): array
    {
        $sheetReader = new class {
            use Importable;
        };

        $sheet = $sheetReader->toArray($file);
        $synchronizer = new SkYayasanImportSynchronizer($madrasahId);

        return $synchronizer->inspectSheet($sheet[0] ?? []);
    }

    private function inspectEditableImportRows(array $rows, int $madrasahId): array
    {
        $synchronizer = new SkYayasanImportSynchronizer($madrasahId);
        $normalizedRows = collect($rows)
            ->sortBy(fn (array $row) => (int) ($row['row_number'] ?? 0))
            ->values();

        $analysedRows = [];
        $validUserIds = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($normalizedRows as $index => $row) {
            $rowData = [
                'no' => $this->nullableImportCell($row['excel_no'] ?? null),
                'nuist_id' => $this->nullableImportCell($row['source_nuist_id'] ?? null),
                'nama' => $this->nullableImportCell($row['source_nama'] ?? null),
                'gelar' => $this->nullableImportCell($row['source_gelar'] ?? null),
                'tempat_lahir' => $this->nullableImportCell($row['source_tempat_lahir'] ?? null),
                'tanggal_lahir' => $this->nullableImportCell($row['source_tanggal_lahir'] ?? null),
                'nip_ma_arif' => $this->nullableImportCell($row['source_nip_maarif'] ?? null),
                'nuptk' => $this->nullableImportCell($row['source_nuptk'] ?? null),
                'nomor_kartanu' => $this->nullableImportCell($row['source_nomor_kartanu'] ?? null),
                'tmt_pertama' => $this->nullableImportCell($row['source_tmt_pertama'] ?? null),
                'masa_kerja' => $this->nullableImportCell($row['source_masa_kerja'] ?? null),
                'pendidikan_terakhir' => $this->nullableImportCell($row['source_pendidikan_terakhir'] ?? null),
                'tahun_lulus' => $this->nullableImportCell($row['source_tahun_lulus'] ?? null),
                'program_studi' => $this->nullableImportCell($row['source_program_studi'] ?? null),
                'mapel_tugas_yang_diampu' => $this->nullableImportCell($row['source_mapel_tugas'] ?? null),
                'penilaian_kinerja' => $this->nullableImportCell($row['source_penilaian_kinerja'] ?? null),
                'keterangan' => $this->nullableImportCell($row['source_keterangan'] ?? null),
            ];

            $analysis = $synchronizer->analyzeRow(
                $rowData,
                (int) ($row['row_number'] ?? ($index + 2))
            );

            $analysedRows[] = $analysis;

            if ($analysis['is_valid']) {
                $validCount++;
                $validUserIds[] = $analysis['user_id'];
            } else {
                $invalidCount++;
            }
        }

        return [
            'expected_headings' => SkYayasanImportSynchronizer::expectedHeadings(),
            'headings_valid' => true,
            'missing_headings' => [],
            'unexpected_headings' => [],
            'rows' => $analysedRows,
            'valid_count' => $validCount,
            'invalid_count' => $invalidCount,
            'valid_user_ids' => array_values(array_unique(array_filter($validUserIds))),
            'can_upload' => $validCount > 0 && $invalidCount === 0,
        ];
    }

    private function nullableImportCell(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    private function buildImportBatchRowsPayload(array $rows): array
    {
        return collect($rows)->map(function (array $row) {
            $sourceColumns = $row['source_columns'] ?? [];

            return [
                'row_number' => $row['row_number'] ?? 0,
                'excel_no' => $sourceColumns['No'] ?? null,
                'source_nuist_id' => $sourceColumns['NUIST ID'] ?? null,
                'source_nama' => $sourceColumns['Nama'] ?? null,
                'source_gelar' => $sourceColumns['Gelar'] ?? null,
                'source_tempat_lahir' => $sourceColumns['Tempat Lahir'] ?? null,
                'source_tanggal_lahir' => $sourceColumns['Tanggal Lahir'] ?? null,
                'source_nip_maarif' => $sourceColumns["NIP Ma'arif"] ?? null,
                'source_nuptk' => $sourceColumns['NUPTK'] ?? null,
                'source_nomor_kartanu' => $sourceColumns['Nomor Kartanu'] ?? null,
                'source_tmt_pertama' => $sourceColumns['TMT Pertama'] ?? null,
                'source_masa_kerja' => $sourceColumns['Masa Kerja'] ?? null,
                'source_pendidikan_terakhir' => $sourceColumns['Pendidikan Terakhir'] ?? null,
                'source_tahun_lulus' => $sourceColumns['Tahun Lulus'] ?? null,
                'source_program_studi' => $sourceColumns['Program Studi'] ?? null,
                'source_mapel_tugas' => $sourceColumns['Mapel/Tugas yang Diampu'] ?? null,
                'source_penilaian_kinerja' => $sourceColumns['Penilaian Kinerja'] ?? null,
                'source_keterangan' => $sourceColumns['Keterangan'] ?? null,
                'matched_user_id' => $row['user_id'] ?? null,
                'matched_name' => $row['matched_name'] ?? null,
                'is_valid' => $row['is_valid'] ?? false,
                'status_label' => $row['status_label'] ?? null,
                'validation_errors' => $row['errors'] ?? [],
                'user_payload' => $row['user_payload'] ?? [],
                'sk_payload' => $row['sk_payload'] ?? [],
            ];
        })->all();
    }

    private function generateRequestNumber(): string
    {
        $sequence = str_pad((string) (SkYayasanRequest::query()->count() + 1), 4, '0', STR_PAD_LEFT);

        return 'REQ-SKY/' . now()->format('Ym') . '/' . $sequence;
    }

    private function generateDocumentNumber(SkYayasanTemplate $template, SkYayasanRequest $submission, Carbon $issuedDate): string
    {
        $sequence = str_pad((string) (SkYayasanDocument::query()->count() + 1), 4, '0', STR_PAD_LEFT);
        $format = $template->document_number_format ?: '{seq}/SKY/{school_code}/{month}/{year}';
        $schoolCode = $submission->madrasah->scod ?: ('SCH' . $submission->madrasah_id);

        return strtr($format, [
            '{seq}' => $sequence,
            '{month}' => $issuedDate->format('m'),
            '{month_roman}' => $this->romanMonth((int) $issuedDate->format('n')),
            '{year}' => $issuedDate->format('Y'),
            '{school_code}' => $schoolCode,
        ]);
    }

    private function buildTemplatePlaceholders(SkYayasanRequest $submission, array $overrides = []): array
    {
        $submission->loadMissing([
            'madrasah.yayasan',
            'employee.statusKepegawaian',
            'employee.skYayasanEmployeeData',
            'importBatch.rows',
        ]);

        $employee = $submission->employee;
        $madrasah = $submission->madrasah;
        $yayasan = $madrasah->yayasan ?: Yayasan::query()->first();
        $employeeSkData = $employee?->skYayasanEmployeeData;
        $importRow = $submission->importBatch?->rows
            ?->first(fn ($row) => (int) $row->matched_user_id === (int) $submission->employee_id);

        $pick = function (...$values) {
            foreach ($values as $value) {
                if ($value === null) {
                    continue;
                }

                $string = trim((string) $value);

                if ($string !== '' && $string !== '-') {
                    return $string;
                }
            }

            return '-';
        };

        $base = [
            '{{nomor_sk}}' => $overrides['nomor_sk'] ?? '-',
            '{{judul_sk}}' => optional($submission->template)->document_title ?? 'Surat Keputusan Yayasan',
            '{{nama_yayasan}}' => $yayasan?->name ?? 'Yayasan',
            '{{alamat_yayasan}}' => $yayasan?->alamat ?? '-',
            '{{nama_sekolah}}' => $madrasah->name ?? '-',
            '{{nama_pegawai}}' => $pick($importRow?->source_nama, $employee->name),
            '{{gelar}}' => $pick($importRow?->source_gelar, $employee->gelar),
            '{{tempat_lahir}}' => $pick($importRow?->source_tempat_lahir, $employee->tempat_lahir),
            '{{tanggal_lahir}}' => $pick($importRow?->source_tanggal_lahir, optional($employee->tanggal_lahir)?->translatedFormat('d F Y')),
            '{{nip_maarif}}' => $pick($importRow?->source_nip_maarif, $employee->nip),
            '{{nuptk}}' => $pick($importRow?->source_nuptk, $employee->nuptk),
            '{{nomor_kartanu}}' => $pick($importRow?->source_nomor_kartanu, $employee->kartanu),
            '{{tmt_pertama}}' => $pick($importRow?->source_tmt_pertama, optional($employee->tmt)?->translatedFormat('d F Y')),
            '{{masa_kerja}}' => $pick($importRow?->source_masa_kerja, $employee->masa_kerja),
            '{{pendidikan_terakhir}}' => $pick($importRow?->source_pendidikan_terakhir, $employee->pendidikan_terakhir),
            '{{tahun_lulus}}' => $pick($importRow?->source_tahun_lulus, $employee->tahun_lulus),
            '{{program_studi}}' => $pick($importRow?->source_program_studi, $employee->program_studi),
            '{{mapel_tugas_yang_diampu}}' => $pick($importRow?->source_mapel_tugas, $employee->mengajar),
            '{{penilaian_kinerja}}' => $pick($importRow?->source_penilaian_kinerja, $employeeSkData?->penilaian_kinerja),
            '{{keterangan_sk_yayasan}}' => $pick($importRow?->source_keterangan, $employeeSkData?->keterangan),
            '{{jabatan}}' => $employee->ketugasan ?? '-',
            '{{status_kepegawaian}}' => $employee->statusKepegawaian?->name ?? ($submission->employment_category ?? '-'),
            '{{tanggal_mulai}}' => $overrides['tanggal_mulai'] ?? '01 Juli ' . now()->format('Y'),
            '{{tanggal_selesai}}' => $overrides['tanggal_selesai'] ?? '30 Juni ' . now()->addYear()->format('Y'),
            '{{tanggal_terbit}}' => $overrides['tanggal_terbit'] ?? now()->translatedFormat('d F Y'),
            '{{tahun_sk}}' => $overrides['tahun_sk'] ?? now()->format('Y'),
            '{{tahun_sk_berikutnya}}' => $overrides['tahun_sk_berikutnya'] ?? now()->addYear()->format('Y'),
            '{{tahun_penerbitan_sk}}' => $overrides['tahun_penerbitan_sk'] ?? (now()->format('Y') . '-' . now()->addYear()->format('Y')),
            '{{nomor_sk_yayasan_mulai}}' => $overrides['nomor_sk_yayasan_mulai'] ?? '-',
            '{{nama_penandatangan}}' => $overrides['nama_penandatangan'] ?? 'Ketua Yayasan',
            '{{jabatan_penandatangan}}' => $overrides['jabatan_penandatangan'] ?? 'Ketua Yayasan',
            '{{ditetapkan_di}}' => $overrides['ditetapkan_di'] ?? 'Yogyakarta',
            '{{tanggal_penetapan}}' => $overrides['tanggal_penetapan'] ?? ($overrides['tanggal_terbit'] ?? now()->translatedFormat('d F Y')),
            '{{tembusan_1}}' => $overrides['tembusan_1'] ?? '-',
            '{{tembusan_2}}' => $overrides['tembusan_2'] ?? '-',
            '{{catatan_pengajuan}}' => '',
            '{{nomor_surat_pengajuan}}' => $pick($submission->submission_letter_number),
            '{{tanggal_surat_pengajuan}}' => $pick(optional($submission->submission_letter_date)?->translatedFormat('d F Y')),
            '{{catatan_penerbitan}}' => $overrides['catatan_penerbitan'] ?? '-',
            '{{excel_no}}' => $pick($importRow?->excel_no),
            '{{source_nama}}' => $pick($importRow?->source_nama, $employee->name),
            '{{source_gelar}}' => $pick($importRow?->source_gelar, $employee->gelar),
            '{{source_tempat_lahir}}' => $pick($importRow?->source_tempat_lahir, $employee->tempat_lahir),
            '{{source_tanggal_lahir}}' => $pick($importRow?->source_tanggal_lahir, optional($employee->tanggal_lahir)?->translatedFormat('d F Y')),
            '{{source_nip_maarif}}' => $pick($importRow?->source_nip_maarif, $employee->nip),
            '{{source_nuptk}}' => $pick($importRow?->source_nuptk, $employee->nuptk),
            '{{source_nomor_kartanu}}' => $pick($importRow?->source_nomor_kartanu, $employee->kartanu),
            '{{source_tmt_pertama}}' => $pick($importRow?->source_tmt_pertama, optional($employee->tmt)?->translatedFormat('d F Y')),
            '{{source_masa_kerja}}' => $pick($importRow?->source_masa_kerja, $employee->masa_kerja),
            '{{source_pendidikan_terakhir}}' => $pick($importRow?->source_pendidikan_terakhir, $employee->pendidikan_terakhir),
            '{{source_tahun_lulus}}' => $pick($importRow?->source_tahun_lulus, $employee->tahun_lulus),
            '{{source_program_studi}}' => $pick($importRow?->source_program_studi, $employee->program_studi),
            '{{source_mapel_tugas}}' => $pick($importRow?->source_mapel_tugas, $employee->mengajar),
            '{{source_penilaian_kinerja}}' => $pick($importRow?->source_penilaian_kinerja, $employeeSkData?->penilaian_kinerja),
            '{{source_keterangan}}' => $pick($importRow?->source_keterangan, $employeeSkData?->keterangan),
        ];

        foreach ($overrides as $key => $value) {
            $base['{{' . $key . '}}'] = $value;
        }

        return $base;
    }

    private function renderTemplate(string $body, array $placeholders): string
    {
        $normalizedPlaceholders = $placeholders;

        foreach ($placeholders as $key => $value) {
            if (str_starts_with($key, '{{') && str_ends_with($key, '}}')) {
                $normalizedPlaceholders['@' . $key] = $value;
            }
        }

        return $this->normalizePersonRows(
            strtr($body, $normalizedPlaceholders)
        );
    }

    private function normalizePersonRows(string $html): string
    {
        if (!str_contains($html, 'sk-person-table')) {
            return $html;
        }

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-root">' . $html . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $html;
        }

        $xpath = new \DOMXPath($document);
        $tables = $xpath->query('//table[contains(@class, "sk-person-table")]');

        if ($tables === false) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $html;
        }

        foreach ($tables as $table) {
            $rows = [];

            foreach ($table->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'tr') {
                    $rows[] = $childNode;
                }
            }

            $visibleIndex = 1;

            foreach ($rows as $row) {
                $cells = [];

                foreach ($row->childNodes as $childNode) {
                    if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'td') {
                        $cells[] = $childNode;
                    }
                }

                if (count($cells) < 4) {
                    continue;
                }

                $valueText = trim(preg_replace('/\s+/u', ' ', html_entity_decode($cells[3]->textContent ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')));

                if ($valueText === '' || $valueText === '-' || $valueText === '@' || str_contains($valueText, '@{{')) {
                    $table->removeChild($row);
                    continue;
                }

                $cells[0]->nodeValue = $visibleIndex . '.';
                $visibleIndex++;
            }
        }

        $root = $document->getElementById('sk-root');
        $output = '';

        if ($root) {
            foreach ($root->childNodes as $childNode) {
                $output .= $document->saveHTML($childNode);
            }
        } else {
            $output = $html;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $output;
    }

    private function templatePreviewPlaceholders(string $documentTitle, ?string $documentNumberFormat, Carbon $issuedDate): array
    {
        $documentNumber = strtr(
            $documentNumberFormat ?: '{seq}/SK.02/LPM.DIY/{month_roman}/{year}',
            [
                '{seq}' => '001',
                '{school_code}' => 'SMK-DLINGO',
                '{month}' => $issuedDate->format('m'),
                '{month_roman}' => $this->romanMonth((int) $issuedDate->format('n')),
                '{year}' => $issuedDate->format('Y'),
            ]
        );

        return [
            '{{nomor_sk}}' => $documentNumber,
            '{{judul_sk}}' => $documentTitle,
            '{{nama_yayasan}}' => "Lembaga Pendidikan Ma'arif NU PWNU DIY",
            '{{alamat_yayasan}}' => 'Jl. Ibu Ruswo Nomor 60 Prawirodirjan, Gondomanan, Yogyakarta',
            '{{nama_sekolah}}' => 'SMK Pembangunan Dlingo',
            '{{nama_pegawai}}' => 'Ahmad Fathoni, S.Pd.',
            '{{gelar}}' => 'S.Pd.',
            '{{tempat_lahir}}' => 'Bantul',
            '{{tanggal_lahir}}' => '12 Januari 1990',
            '{{nip_maarif}}' => 'MIF.2026.001',
            '{{nuptk}}' => '1234567890123456',
            '{{nomor_kartanu}}' => 'NU.34.02.001',
            '{{tmt_pertama}}' => '01 Juli 2020',
            '{{masa_kerja}}' => '6 tahun',
            '{{pendidikan_terakhir}}' => 'S1',
            '{{tahun_lulus}}' => '2015',
            '{{program_studi}}' => 'Pendidikan Teknik Informatika',
            '{{mapel_tugas_yang_diampu}}' => 'XXX',
            '{{penilaian_kinerja}}' => 'Baik',
            '{{keterangan_sk_yayasan}}' => 'Perpanjangan SK',
            '{{jabatan}}' => 'Guru',
            '{{status_kepegawaian}}' => 'Guru Tetap Yayasan',
            '{{tanggal_mulai}}' => '01 Juli ' . $issuedDate->format('Y'),
            '{{tanggal_selesai}}' => '30 Juni ' . $issuedDate->copy()->addYear()->format('Y'),
            '{{tanggal_terbit}}' => $issuedDate->translatedFormat('d F Y'),
            '{{tahun_sk}}' => $issuedDate->format('Y'),
            '{{tahun_sk_berikutnya}}' => $issuedDate->copy()->addYear()->format('Y'),
            '{{nama_penandatangan}}' => 'Dr. Tadkiroatun Musfiroh, M. Hum.',
            '{{jabatan_penandatangan}}' => "Pengurus LP Ma'arif NU PWNU DIY",
            '{{catatan_pengajuan}}' => '-',
            '{{catatan_penerbitan}}' => '-',
        ];
    }

    private function romanMonth(int $month): string
    {
        return [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ][$month] ?? (string) $month;
    }
}
