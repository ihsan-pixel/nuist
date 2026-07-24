<?php

namespace App\Http\Controllers;

use App\Exports\SkYayasanSchoolSubmissionSummaryExport;
use App\Exports\SkYayasanUserImportTemplateExport;
use App\Models\AcademicaResetUpdate;
use App\Models\AppSetting;
use App\Models\Madrasah;
use App\Models\MgmpMember;
use App\Models\SkYayasanDocument;
use App\Models\SkYayasanImportBatch;
use App\Models\SkYayasanImportRow;
use App\Models\SkYayasanRequest;
use App\Models\SkYayasanTemplate;
use App\Models\User;
use App\Models\Yayasan;
use App\Services\UppmPaymentStatusService;
use App\Support\SkYayasanImportSynchronizer;
use Closure;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

        if (!$this->skYayasanDashboardSupported()) {
            return view('sk-yayasan.dashboard', $this->emptySkYayasanDashboardPayload(
                'Data dashboard SK Yayasan belum tersedia karena tabel atau kolom pendukung belum lengkap.'
            ));
        }

        try {
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
                'dashboardWarning' => null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return view('sk-yayasan.dashboard', $this->emptySkYayasanDashboardPayload(
                'Data dashboard SK Yayasan sementara tidak dapat dimuat. Detail error sudah dicatat di log aplikasi.'
            ));
        }
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
                $this->createRequestWithGeneratedNumber([
                    'madrasah_id' => $madrasahId,
                    'import_batch_id' => $batch->id,
                    'employee_id' => $employee->id,
                    'submitted_by' => $user->id,
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
        $deletedRequestCount = 0;

        DB::transaction(function () use ($batch, $report, $user, &$deletedRequestCount) {
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
            $syncSummary = $this->synchronizeBatchRequestsFromRows($batch);
            $deletedRequestCount = (int) ($syncSummary['deleted'] ?? 0);

            $batch->requests()->update([
                'current_status' => 'submitted',
                'review_notes' => null,
                'submitted_by' => $user->id,
                'reviewed_by' => null,
                'submitted_at' => now(),
                'reviewed_at' => null,
            ]);
        });

        $message = 'Data import berhasil diperbarui dan dikirim ulang untuk direview.';

        if ($deletedRequestCount > 0) {
            $message .= ' ' . $deletedRequestCount . ' pengajuan yang barisnya dihapus juga dikeluarkan dari batch.';
        }

        return back()->with('success', $message);
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

            $this->synchronizeBatchRequestsFromRows($batch);

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

        if (!in_array($batch->status, ['pending_review', 'rejected', 'synced'], true)) {
            return back()->with('error', 'Batch import ini sudah diproses dan tidak dapat diedit lagi.');
        }

        $validated = $request->validate([
            'action' => ['nullable', 'in:save,sync'],
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
            'review_notes' => ['nullable', 'string'],
        ]);

        $report = $this->inspectEditableImportRows($validated['rows'], (int) $batch->madrasah_id);
        $wasRejected = $batch->status === 'rejected';
        $shouldSync = ($validated['action'] ?? 'save') === 'sync';
        $deletedRequestCount = 0;

        if ($shouldSync && $report['invalid_count'] > 0) {
            return back()->with('error', 'Data masih memiliki baris yang belum valid. Perbaiki semua baris sebelum sinkronisasi ulang.');
        }

        $message = 'Data review import berhasil diperbarui. Batch kembali ke status pending review.';

        DB::transaction(function () use ($batch, $report, $wasRejected, $shouldSync, $validated, &$message, &$deletedRequestCount) {
            $batch->update([
                'status' => $shouldSync ? 'synced' : 'pending_review',
                'reviewed_by' => $shouldSync ? auth()->id() : null,
                'review_notes' => $validated['review_notes'] ?? null,
                'reviewed_at' => $shouldSync ? now() : null,
                'synced_at' => $shouldSync ? now() : null,
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
            $syncSummary = $this->synchronizeBatchRequestsFromRows($batch);
            $deletedRequestCount = (int) ($syncSummary['deleted'] ?? 0);

            if ($shouldSync) {
                $batch->load('rows');
                $synchronizer = new SkYayasanImportSynchronizer((int) $batch->madrasah_id);
                $updated = 0;
                $unchanged = 0;

                $batch->rows->each(function ($row) use ($synchronizer, &$updated, &$unchanged) {
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

                $this->synchronizeBatchRequestsFromRows($batch);

                $batch->requests()->update([
                    'current_status' => 'submitted',
                    'review_notes' => $validated['review_notes'] ?? null,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);

                $message = "Data review import berhasil diperbarui dan disinkronisasi ulang. {$updated} data diperbarui, {$unchanged} baris tidak mengubah data.";
                return;
            }

            if ($wasRejected) {
                $batch->requests()->update([
                    'current_status' => 'submitted',
                    'review_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }
        });

        if ($deletedRequestCount > 0) {
            $message .= ' ' . $deletedRequestCount . ' pengajuan yang barisnya dihapus juga dikeluarkan dari batch.';
        }

        return back()->with('success', $message);
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

        $summaryData = $this->buildSuperAdminPengajuanSummary();

        $submissions = SkYayasanRequest::query()
            ->with(['madrasah', 'employee.statusKepegawaian', 'submitter', 'reviewer', 'template', 'document', 'importBatch'])
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

        $submissionAppointmentAlerts = $this->buildSubmissionAppointmentAlerts($submissions->getCollection());
        $submissions->getCollection()->transform(function (SkYayasanRequest $submission) use ($submissionAppointmentAlerts) {
            $submission->appointment_alert = $submissionAppointmentAlerts[$submission->id] ?? null;

            return $submission;
        });

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

        $syncedImportBatchSchoolCount = SkYayasanImportBatch::query()
            ->where('status', 'synced')
            ->distinct('madrasah_id')
            ->count('madrasah_id');

        return view('sk-yayasan.pengajuan-index', [
            'submissions' => $submissions,
            'pendingImportBatches' => $pendingImportBatches,
            'syncedImportBatches' => $syncedImportBatches,
            'syncedImportBatchSchoolCount' => $syncedImportBatchSchoolCount,
            'importPreviewColumns' => SkYayasanImportSynchronizer::expectedHeadings(),
            'madrasahs' => Madrasah::query()->orderBy('name')->get(['id', 'name']),
            'templates' => SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get(),
            'schoolSubmissionSummaryRows' => $summaryData['rows'],
            'schoolSubmissionSummaryCards' => $summaryData['cards'],
            'keteranganSummaryCounts' => $summaryData['keterangan_counts'],
        ]);
    }

    public function exportSchoolSubmissionSummary()
    {
        $this->ensureSuperAdmin();

        $summaryData = $this->buildSuperAdminPengajuanSummary();

        return Excel::download(
            new SkYayasanSchoolSubmissionSummaryExport(
                $summaryData['export_rows'],
                $summaryData['export_headings']
            ),
            'rekap-pengajuan-sk-yayasan-sekolah-' . now()->format('Ymd_His') . '.xlsx'
        );
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
            'globalSkSettings' => $this->getGlobalSkSettings(),
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
            'name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'document_title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $issuedDate = now();
        $placeholders = $this->templatePreviewPlaceholders(
            $validated['document_title'],
            $issuedDate
        );

        $renderedContent = $this->renderTemplate($validated['body'], $placeholders, [
            'name' => $validated['name'] ?? null,
            'category' => $validated['category'] ?? null,
            'document_title' => $validated['document_title'],
        ]);
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

        $this->resetBrokenSkYayasanFontCache();

        $pdf = PDF::loadView('pdf.sk-yayasan-template', [
            'document' => $document,
            'submission' => $submission,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $documentNumber . '.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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
        $this->repairSyncedBatchesRequests();

        $globalSkSettings = $this->getGlobalSkSettings();
        $numberLockSupported = $this->skYayasanDocumentNumberLockSupported();
        $schoolCounts = [
            'skYayasanImportBatches as synced_batches_count' => fn (Builder $query) => $query->where('status', 'synced'),
            'skYayasanRequests as generate_requests_count' => fn (Builder $query) => $query
                ->whereHas('importBatch', fn (Builder $batchQuery) => $batchQuery->where('status', 'synced')),
            'skYayasanRequests as generated_documents_count' => fn (Builder $query) => $query
                ->whereHas('document'),
        ];

        if ($numberLockSupported) {
            $schoolCounts['skYayasanRequests as locked_documents_count'] = fn (Builder $query) => $query
                ->whereHas('document', fn (Builder $documentQuery) => $documentQuery->whereNotNull('number_locked_at'));
        }

        $uppmPaymentStatusService = app(UppmPaymentStatusService::class);
        $uppmPaymentRequirement = $uppmPaymentStatusService->resolveSkPaymentRequirement($globalSkSettings['issued_date'] ?? null);
        $uppmValidationYear = (int) $uppmPaymentRequirement['year'];
        $uppmValidationPeriodKey = (string) $uppmPaymentRequirement['period_key'];
        $uppmValidationPeriodLabel = (string) $uppmPaymentRequirement['period_label'];
        $uppmValidationEnabled = $uppmPaymentStatusService->shouldEnforceSkGenerateGate($uppmValidationYear);
        $syncedSchoolCount = Madrasah::query()
            ->whereHas('skYayasanImportBatches', fn (Builder $query) => $query->where('status', 'synced'))
            ->count();

        $schools = $this->generateQueueSchoolsQuery($uppmValidationYear, $uppmValidationPeriodKey)
            ->withCount($schoolCounts)
            ->get();
        $schools = $this->prepareGenerateQueueSchools(
            $schools,
            $numberLockSupported
        );

        $blockedSchools = collect();
        if ($uppmValidationEnabled) {
            $blockedSchools = $this->generateQueueSchoolsQuery($uppmValidationYear, $uppmValidationPeriodKey, false)
                ->whereNotIn('id', $schools->pluck('id')->all())
                ->withCount($schoolCounts)
                ->get();

            $blockedSchools = $this->prepareGenerateQueueSchools(
                $blockedSchools,
                $numberLockSupported,
                $uppmPaymentStatusService->summariesForYear($blockedSchools, $uppmValidationYear),
                $uppmValidationPeriodKey
            );
        }

        $eligibleSchoolIds = $schools->pluck('id')->map(fn ($id) => (int) $id)->all();

        $appointmentRequests = $this->buildGenerateAppointmentRequestsTable($schools);

        return view('sk-yayasan.generate-index', [
            'schools' => $schools,
            'blockedSchools' => $blockedSchools,
            'totalRequestsCount' => empty($eligibleSchoolIds)
                ? 0
                : SkYayasanRequest::query()
                    ->whereHas('importBatch', fn (Builder $query) => $query->where('status', 'synced'))
                    ->whereIn('madrasah_id', $eligibleSchoolIds)
                    ->count(),
            'syncedBatchCount' => SkYayasanImportBatch::query()->where('status', 'synced')->count(),
            'globalSkSettings' => $globalSkSettings,
            'numberLockSupported' => $numberLockSupported,
            'uppmValidationEnabled' => $uppmValidationEnabled,
            'uppmValidationYear' => $uppmValidationYear,
            'uppmValidationPeriodLabel' => $uppmValidationPeriodLabel,
            'uppmBlockedSchoolCount' => $blockedSchools->count(),
            'syncedSchoolCount' => $syncedSchoolCount,
            'appointmentRequests' => $appointmentRequests->where('tmt_is_two_years_or_more', true)->values(),
            'appointmentRequestsUnderTwoYears' => $appointmentRequests->where('tmt_is_two_years_or_more', false)->values(),
        ]);
    }

    public function updateGenerateSettings(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'sk_yayasan_school_year' => ['required', 'string', 'max:50'],
            'sk_yayasan_number_start' => ['required', 'integer', 'min:1'],
            'sk_yayasan_signer_name' => ['required', 'string', 'max:255'],
            'sk_yayasan_signer_position' => ['nullable', 'string', 'max:255'],
            'sk_yayasan_established_at' => ['required', 'string', 'max:255'],
            'sk_yayasan_issued_date' => ['required', 'date'],
            'sk_yayasan_number_format_suffix' => ['required', 'string', 'max:255'],
        ]);

        AppSetting::getSettings()->update([
            'sk_yayasan_school_year' => $validated['sk_yayasan_school_year'],
            'sk_yayasan_number_start' => $validated['sk_yayasan_number_start'],
            'sk_yayasan_signer_name' => $validated['sk_yayasan_signer_name'],
            'sk_yayasan_signer_position' => $validated['sk_yayasan_signer_position'] ?: 'Ketua Yayasan',
            'sk_yayasan_established_at' => $validated['sk_yayasan_established_at'],
            'sk_yayasan_issued_date' => $validated['sk_yayasan_issued_date'],
            'sk_yayasan_number_format_suffix' => $validated['sk_yayasan_number_format_suffix'],
        ]);

        return back()->with('success', 'Data pokok SK global berhasil diperbarui.');
    }

    public function syncGenerateAppointmentNipm(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->repairSyncedBatchesRequests();

        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.teacher_id' => ['required', 'integer'],
            'rows.*.nipm_mode' => ['nullable', 'in:existing,system'],
            'rows.*.nipm' => ['nullable', 'regex:/^\d{20}$/'],
        ], [
            'rows.*.nipm.regex' => 'NIPM harus berisi tepat 20 digit angka.',
        ]);

        $globalSkSettings = $this->getGlobalSkSettings();
        $uppmPaymentStatusService = app(UppmPaymentStatusService::class);
        $uppmPaymentRequirement = $uppmPaymentStatusService->resolveSkPaymentRequirement($globalSkSettings['issued_date'] ?? null);
        $uppmValidationYear = (int) $uppmPaymentRequirement['year'];
        $uppmValidationPeriodKey = (string) $uppmPaymentRequirement['period_key'];

        $schools = $this->generateQueueSchoolsQuery($uppmValidationYear, $uppmValidationPeriodKey)->get();
        $appointmentRequests = $this->buildGenerateAppointmentRequestsTable($schools)
            ->keyBy(fn (array $row) => (int) $row['teacher_id']);

        $updates = [];

        foreach ($validated['rows'] as $row) {
            $teacherId = (int) $row['teacher_id'];
            $selectedMode = (string) ($row['nipm_mode'] ?? 'system');
            $nipm = preg_replace('/\D+/u', '', (string) ($row['nipm'] ?? '')) ?? '';

            if (!$appointmentRequests->has($teacherId)) {
                continue;
            }

            $appointmentRow = $appointmentRequests->get($teacherId);

            if (($appointmentRow['has_nipm_source_choice'] ?? false) === true && $selectedMode === 'existing') {
                continue;
            }

            if ($nipm === '') {
                continue;
            }

            $expectedScod = $this->normalizeNipmSchoolScod($appointmentRow['school_scod'] ?? null);
            if (substr($nipm, 14, 3) !== $expectedScod) {
                return back()->withErrors([
                    'rows' => 'Ada NIPM yang tidak sesuai dengan SCOD sekolah guru terkait.',
                ]);
            }

            $updates[$teacherId] = $nipm;
        }

        if (empty($updates)) {
            return back()->with('info', 'Tidak ada NIPM baru yang perlu disinkronkan.');
        }

        DB::transaction(function () use ($updates) {
            foreach ($updates as $teacherId => $nipm) {
                User::query()
                    ->whereKey($teacherId)
                    ->update(['nip' => $nipm]);
            }
        });

        return back()->with('success', count($updates) . ' NIPM berhasil disinkronkan ke database.');
    }

    public function generateSchoolIndex(Madrasah $madrasah): View
    {
        $this->ensureSuperAdmin();
        $this->repairSyncedBatchesRequests((int) $madrasah->id);

        $syncedQueueRequests = $this->syncedGenerateQueueRequestsConstraint();
        $templates = SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get();

        $requests = SkYayasanRequest::query()
            ->with([
                'madrasah',
                'employee.statusKepegawaian',
                'template',
                'document.template',
                'importBatch.rows',
                'importBatch.uploader',
                'importBatch.reviewer',
            ])
            ->where('madrasah_id', $madrasah->id)
            ->where($syncedQueueRequests)
            ->get();

        $requests = $requests->map(function (SkYayasanRequest $submission) use ($templates) {
            return $this->decorateGenerateSubmission($submission, $templates);
        })->sort(function (SkYayasanRequest $left, SkYayasanRequest $right) {
            $leftSequence = $this->extractDocumentNumberSequence($left->document?->document_number);
            $rightSequence = $this->extractDocumentNumberSequence($right->document?->document_number);

            if ($leftSequence === null && $rightSequence !== null) {
                return 1;
            }

            if ($leftSequence !== null && $rightSequence === null) {
                return -1;
            }

            if ($leftSequence !== null && $rightSequence !== null && $leftSequence !== $rightSequence) {
                return $leftSequence <=> $rightSequence;
            }

            $leftName = mb_strtolower((string) ($left->employee?->name ?? ''));
            $rightName = mb_strtolower((string) ($right->employee?->name ?? ''));

            if ($leftName !== $rightName) {
                return $leftName <=> $rightName;
            }

            return (int) $left->id <=> (int) $right->id;
        })->values();

        $submissionLetterReference = $requests->first(function (SkYayasanRequest $submission) {
            return filled($submission->submission_letter_number) || $submission->submission_letter_date !== null;
        }) ?? $requests->first();

        $submissionLetterNumbers = $requests
            ->pluck('submission_letter_number')
            ->filter(fn ($value) => filled($value))
            ->unique()
            ->values();

        $submissionLetterDates = $requests
            ->map(fn (SkYayasanRequest $submission) => optional($submission->submission_letter_date)->toDateString())
            ->filter(fn ($value) => filled($value))
            ->unique()
            ->values();

        return view('sk-yayasan.generate-school-index', [
            'madrasah' => $madrasah,
            'requests' => $requests,
            'templates' => $templates,
            'coreData' => $this->buildSchoolSkCoreData($madrasah),
            'submissionLetterReference' => $submissionLetterReference,
            'submissionLetterIsMixed' => $submissionLetterNumbers->count() > 1 || $submissionLetterDates->count() > 1,
            'importPreviewColumns' => SkYayasanImportSynchronizer::expectedHeadings(),
            'numberLockSupported' => $this->skYayasanDocumentNumberLockSupported(),
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee', 'request.madrasah'])
                ->where('status', 'published')
                ->whereHas('request', fn (Builder $query) => $query->where('madrasah_id', $madrasah->id))
                ->latest('published_at')
                ->take(10)
                ->get(),
        ]);
    }

    public function updateGenerateSchoolSubmissionLetter(Request $request, Madrasah $madrasah): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->repairSyncedBatchesRequests((int) $madrasah->id);

        $requestIds = SkYayasanRequest::query()
            ->where('madrasah_id', $madrasah->id)
            ->where($this->syncedGenerateQueueRequestsConstraint())
            ->pluck('id');

        if ($requestIds->isEmpty()) {
            return back()->with('error', 'Tidak ada pengajuan tersinkron pada sekolah ini yang bisa diperbarui.');
        }

        $validated = $request->validate([
            'submission_letter_number' => ['required', 'string', 'max:255'],
            'submission_letter_date' => ['required', 'date'],
        ]);

        $updatedCount = SkYayasanRequest::query()
            ->whereIn('id', $requestIds->all())
            ->update([
                'submission_letter_number' => trim((string) $validated['submission_letter_number']),
                'submission_letter_date' => Carbon::parse($validated['submission_letter_date'])->toDateString(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Nomor dan tanggal surat pengajuan berhasil diperbarui untuk ' . $updatedCount . ' pengajuan pada sekolah ini.');
    }

    public function generateDocument(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'request_id' => ['required', 'integer', 'exists:sk_yayasan_requests,id'],
            'template_id' => ['required', 'integer', 'exists:sk_yayasan_templates,id'],
            'issued_date' => ['required', 'date'],
            'document_number' => ['nullable', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:50'],
            'document_number_start' => ['nullable', 'string', 'max:255'],
            'number_format_suffix' => ['nullable', 'string', 'max:255'],
            'established_at' => ['required', 'string', 'max:255'],
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_position' => ['nullable', 'string', 'max:255'],
            'copy_recipient_1' => ['required', 'string', 'max:255'],
            'copy_recipient_2' => ['required', 'string', 'max:255'],
            'publication_notes' => ['nullable', 'string'],
            'preview_pdf' => ['nullable', 'boolean'],
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
        $document = $this->persistGeneratedDocument($submission, $template, $issuedDate, [
            'school_year' => $validated['school_year'],
            'document_number_start' => $validated['document_number_start'] ?? null,
            'number_format_suffix' => $validated['number_format_suffix'] ?? null,
            'signer_name' => $validated['signer_name'],
            'signer_position' => $validated['signer_position'] ?? 'Ketua Yayasan',
            'established_at' => $validated['established_at'],
            'copy_recipient_1' => $validated['copy_recipient_1'],
            'copy_recipient_2' => $validated['copy_recipient_2'],
            'publication_notes' => $validated['publication_notes'] ?? null,
            'document_number' => $validated['document_number'] ?? null,
        ]);

        if ((bool) ($validated['preview_pdf'] ?? false)) {
            return $this->downloadDocument($document);
        }

        return back()->with('success', 'Draft SK Yayasan berhasil digenerate.');
    }

    public function generateSchoolPdf(Request $request, Madrasah $madrasah)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'issued_date' => ['nullable', 'date'],
            'school_year' => ['nullable', 'string', 'max:50'],
            'document_number_start' => ['nullable', 'string', 'max:255'],
            'number_format_suffix' => ['nullable', 'string', 'max:255'],
            'signer_name' => ['nullable', 'string', 'max:255'],
            'signer_position' => ['nullable', 'string', 'max:255'],
            'established_at' => ['nullable', 'string', 'max:255'],
            'copy_recipient_1' => ['nullable', 'string', 'max:255'],
            'copy_recipient_2' => ['nullable', 'string', 'max:255'],
        ]);

        $requests = SkYayasanRequest::query()
            ->with([
                'madrasah.yayasan',
                'employee.statusKepegawaian',
                'employee.skYayasanEmployeeData',
                'template',
                'document.template',
                'importBatch.rows',
            ])
            ->where('madrasah_id', $madrasah->id)
            ->where($this->generateEligibleRequestsConstraint())
            ->get();

        if ($requests->isEmpty()) {
            return back()->with('error', 'Tidak ada pengajuan pada sekolah ini yang bisa digenerate.');
        }

        $templates = SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get();
        $coreData = array_merge(
            $this->buildSchoolSkCoreData($madrasah),
            array_filter([
                'school_year' => $validated['school_year'] ?? null,
                'document_number_start' => $validated['document_number_start'] ?? null,
                'number_format_suffix' => $validated['number_format_suffix'] ?? null,
                'signer_name' => $validated['signer_name'] ?? null,
                'signer_position' => $validated['signer_position'] ?? null,
                'established_at' => $validated['established_at'] ?? null,
                'issued_date' => $validated['issued_date'] ?? null,
                'copy_recipient_1' => $validated['copy_recipient_1'] ?? null,
                'copy_recipient_2' => $validated['copy_recipient_2'] ?? null,
            ], fn ($value) => $value !== null && $value !== '')
        );
        $issuedDate = Carbon::parse($coreData['issued_date']);
        $sortedRequests = $requests
            ->sortBy(fn (SkYayasanRequest $submission) => mb_strtolower((string) ($submission->employee?->name ?? '')))
            ->values();

        $missingTemplates = $sortedRequests
            ->filter(fn (SkYayasanRequest $submission) => !$this->resolveTemplateForSubmission($submission, $templates))
            ->map(fn (SkYayasanRequest $submission) => $submission->employee?->name ?? ('Request #' . $submission->id))
            ->all();

        if (!empty($missingTemplates)) {
            return back()->with('error', 'Template belum tersedia untuk: ' . implode(', ', $missingTemplates));
        }

        $documents = DB::transaction(function () use ($sortedRequests, $templates, $issuedDate, $coreData) {
            $generatedDocuments = collect();

            foreach ($sortedRequests as $submission) {
                $template = $this->resolveTemplateForSubmission($submission, $templates);

                $generatedDocuments->push($this->persistGeneratedDocument($submission, $template, $issuedDate, [
                    'school_year' => $coreData['school_year'],
                    'document_number_start' => $coreData['document_number_start'],
                    'number_format_suffix' => $coreData['number_format_suffix'],
                    'signer_name' => $coreData['signer_name'],
                    'signer_position' => $coreData['signer_position'],
                    'established_at' => $coreData['established_at'],
                    'copy_recipient_1' => $coreData['copy_recipient_1'],
                    'copy_recipient_2' => $coreData['copy_recipient_2'],
                    'publication_notes' => null,
                    'document_number' => null,
                ]));
            }

            return $generatedDocuments;
        });

        return $this->downloadSchoolDocumentsPdf($madrasah, $documents);
    }

    public function regenerateAllDocuments(): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->repairSyncedBatchesRequests();

        $schools = $this->generateQueueSchoolsQuery()->get();

        if ($schools->isEmpty()) {
            return back()->with('error', 'Belum ada sekolah tersinkron yang bisa digenerate ulang.');
        }

        $templates = SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get();
        $payloads = collect();
        $missingTemplates = [];

        foreach ($schools as $school) {
            $coreData = $this->buildSchoolSkCoreData($school);
            $schoolIssuedDate = Carbon::parse($coreData['issued_date']);
            $requests = SkYayasanRequest::query()
                ->with([
                    'madrasah.yayasan',
                    'employee.statusKepegawaian',
                    'employee.skYayasanEmployeeData',
                    'template',
                    'document.template',
                    'importBatch.rows',
                ])
                ->where('madrasah_id', $school->id)
                ->where($this->generateEligibleRequestsConstraint())
                ->get()
                ->sortBy(fn (SkYayasanRequest $submission) => mb_strtolower((string) ($submission->employee?->name ?? '')))
                ->values();

            foreach ($requests as $submission) {
                $template = $this->resolveTemplateForSubmission($submission, $templates);

                if (!$template) {
                    $missingTemplates[] = ($school->name ?? 'Sekolah') . ' - ' . ($submission->employee?->name ?? ('Request #' . $submission->id));
                    continue;
                }

                $payloads->push([
                    'submission' => $submission,
                    'template' => $template,
                    'issued_date' => $schoolIssuedDate->copy(),
                    'data' => [
                        'school_year' => $coreData['school_year'],
                        'document_number_start' => $coreData['document_number_start'],
                        'number_format_suffix' => $coreData['number_format_suffix'],
                        'signer_name' => $coreData['signer_name'],
                        'signer_position' => $coreData['signer_position'],
                        'established_at' => $coreData['established_at'],
                        'copy_recipient_1' => $coreData['copy_recipient_1'],
                        'copy_recipient_2' => $coreData['copy_recipient_2'],
                        'publication_notes' => $submission->document?->publication_notes,
                    ],
                ]);
            }
        }

        if (!empty($missingTemplates)) {
            return back()->with('error', 'Template belum tersedia untuk: ' . implode(', ', array_slice($missingTemplates, 0, 10)) . (count($missingTemplates) > 10 ? ' dan lainnya.' : ''));
        }

        if ($payloads->isEmpty()) {
            return back()->with('error', 'Belum ada pengajuan yang memenuhi syarat untuk generate ulang.');
        }

        $numberLockSupported = $this->skYayasanDocumentNumberLockSupported();
        $issuedDate = $payloads->first()['issued_date'];
        $preferredStartNumber = max(1, (int) ($payloads->first()['data']['document_number_start'] ?? 1));
        $preferredNumberFormatSuffix = $payloads->first()['data']['number_format_suffix'] ?? null;

        DB::transaction(function () use ($payloads, $numberLockSupported, $issuedDate, $preferredStartNumber, $preferredNumberFormatSuffix) {
            $renumberablePayloads = $payloads->filter(function (array $payload) use ($numberLockSupported) {
                $document = $payload['submission']->document;

                if (!$document) {
                    return true;
                }

                return !$numberLockSupported || $document->number_locked_at === null;
            })->values();

            $this->assignTemporaryDocumentNumbers(
                $renumberablePayloads
                    ->pluck('submission.document')
                    ->filter()
                    ->values()
            );

            $assignedNumbers = $this->buildAssignedDocumentNumbers(
                $renumberablePayloads,
                $issuedDate,
                $preferredStartNumber,
                $preferredNumberFormatSuffix
            );

            foreach ($payloads as $payload) {
                /** @var \App\Models\SkYayasanRequest $submission */
                $submission = $payload['submission'];

                $this->persistGeneratedDocument(
                    $submission,
                    $payload['template'],
                    $payload['issued_date'],
                    array_merge($payload['data'], [
                        'document_number' => $assignedNumbers[$submission->id] ?? null,
                    ])
                );
            }
        });

        $renumberedCount = $payloads->filter(function (array $payload) use ($numberLockSupported) {
            $document = $payload['submission']->document;

            return !$document || !$numberLockSupported || $document->number_locked_at === null;
        })->count();

        return back()->with('success', 'Generate ulang semua sekolah selesai. ' . $renumberedCount . ' nomor SK disusun ulang mengikuti urutan SCOD sekolah.');
    }

    public function lockAllDocumentNumbers(): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if (!$this->skYayasanDocumentNumberLockSupported()) {
            return back()->with('error', 'Fitur kunci nomor SK belum aktif karena kolom database belum dimigrasikan.');
        }

        $schoolIds = $this->generateQueueSchoolsQuery()->pluck('id');

        if ($schoolIds->isEmpty()) {
            return back()->with('error', 'Belum ada sekolah tersinkron yang bisa dikunci nomornya.');
        }

        $documentsQuery = SkYayasanDocument::query()
            ->whereNotNull('document_number')
            ->whereHas('request', fn (Builder $query) => $query->whereIn('madrasah_id', $schoolIds->all()));

        $generatedCount = (clone $documentsQuery)->count();

        if ($generatedCount === 0) {
            return back()->with('error', 'Belum ada draft SK yang bisa dikunci.');
        }

        $lockableQuery = (clone $documentsQuery)->whereNull('number_locked_at');
        $lockableCount = $lockableQuery->count();

        if ($lockableCount === 0) {
            return back()->with('success', 'Semua nomor SK pada antrean sekolah sudah terkunci.');
        }

        $updatedCount = DB::transaction(function () use ($lockableQuery) {
            return $lockableQuery->update([
                'number_locked_at' => now(),
                'number_locked_by' => auth()->id(),
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', $updatedCount . ' nomor SK berhasil dikunci untuk seluruh antrean sekolah.');
    }

    public function lockSchoolDocumentNumbers(Madrasah $madrasah): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if (!$this->skYayasanDocumentNumberLockSupported()) {
            return back()->with('error', 'Fitur kunci nomor SK belum aktif karena kolom database belum dimigrasikan.');
        }

        $generatedCount = $this->schoolDocumentsQuery($madrasah)->count();

        if ($generatedCount === 0) {
            return back()->with('error', 'Belum ada draft SK pada sekolah ini yang bisa dikunci nomornya.');
        }

        $lockedCount = $this->schoolDocumentsQuery($madrasah)
            ->whereNotNull('number_locked_at')
            ->count();

        if ($lockedCount >= $generatedCount) {
            return back()->with('success', 'Semua nomor SK untuk sekolah ini sudah terkunci.');
        }

        $updatedCount = DB::transaction(function () use ($madrasah) {
            return $this->schoolDocumentsQuery($madrasah)
                ->whereNull('number_locked_at')
                ->update([
                    'number_locked_at' => now(),
                    'number_locked_by' => auth()->id(),
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', $updatedCount . ' nomor SK berhasil dikunci. Generate berikutnya akan melanjutkan nomor baru tanpa mengubah SK yang sudah dikunci.');
    }

    public function publishDocument(SkYayasanDocument $document): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $document->load('request');

        $payload = [
            'status' => 'published',
            'published_by' => auth()->id(),
            'published_at' => now(),
        ];

        if ($this->skYayasanDocumentNumberLockSupported()) {
            $payload['number_locked_by'] = $document->number_locked_by ?: auth()->id();
            $payload['number_locked_at'] = $document->number_locked_at ?: now();
        }

        $document->update($payload);

        $document->request->update([
            'current_status' => 'published',
        ]);

        return back()->with('success', 'SK Yayasan berhasil diterbitkan.');
    }

    public function downloadDocument(SkYayasanDocument $document)
    {
        $document->load(['request.madrasah.yayasan', 'request.employee.statusKepegawaian', 'template']);
        $this->authorizeDocumentAccess($document);

        $this->resetBrokenSkYayasanFontCache();

        $pdf = PDF::loadView('pdf.sk-yayasan-template', [
            'document' => $document,
            'submission' => $document->request,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $document->document_number . '.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function downloadSchoolDocumentsPdf(Madrasah $madrasah, Collection $documents)
    {
        $this->resetBrokenSkYayasanFontCache();

        $pdf = PDF::loadView('pdf.sk-yayasan-school-bundle', [
            'madrasah' => $madrasah,
            'documents' => $documents,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sk-yayasan-' . Str::slug($madrasah->name ?: 'sekolah') . '.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function resetBrokenSkYayasanFontCache(): void
    {
        $fontDir = storage_path('fonts');

        if (!is_dir($fontDir)) {
            @mkdir($fontDir, 0755, true);
        }

        if (!is_dir($fontDir) || !is_writable($fontDir)) {
            return;
        }

        $fontsFile = $fontDir . DIRECTORY_SEPARATOR . 'installed-fonts.json';

        if (!is_readable($fontsFile)) {
            return;
        }

        $installedFonts = json_decode((string) file_get_contents($fontsFile), true);

        if (!is_array($installedFonts) || !isset($installedFonts['cambria']) || !is_array($installedFonts['cambria'])) {
            return;
        }

        foreach ($installedFonts['cambria'] as $variantPath) {
            if (!is_string($variantPath) || $variantPath === '') {
                $this->flushSkYayasanCambriaFontCache($installedFonts, $fontsFile, $fontDir);

                return;
            }

            if (str_contains($variantPath, 'Cambria.ttc')) {
                $this->flushSkYayasanCambriaFontCache($installedFonts, $fontsFile, $fontDir);

                return;
            }

            $fontBasePath = basename($variantPath) === $variantPath
                ? $fontDir . DIRECTORY_SEPARATOR . $variantPath
                : $variantPath;

            if (!is_file($fontBasePath . '.ufm')) {
                $this->flushSkYayasanCambriaFontCache($installedFonts, $fontsFile, $fontDir);

                return;
            }
        }
    }

    private function flushSkYayasanCambriaFontCache(array $installedFonts, string $fontsFile, string $fontDir): void
    {
        unset($installedFonts['cambria']);

        file_put_contents(
            $fontsFile,
            json_encode($installedFonts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $fontCacheFiles = glob($fontDir . DIRECTORY_SEPARATOR . 'cambria_*') ?: [];

        foreach ($fontCacheFiles as $fontCacheFile) {
            if (is_file($fontCacheFile)) {
                @unlink($fontCacheFile);
            }
        }
    }

    private function persistGeneratedDocument(
        SkYayasanRequest $submission,
        SkYayasanTemplate $template,
        Carbon $issuedDate,
        array $data
    ): SkYayasanDocument {
        $submission->loadMissing(['madrasah.yayasan', 'employee.statusKepegawaian', 'document', 'importBatch.rows']);

        $existingDocument = $submission->document;
        $requestedStartNumber = max(1, (int) ($data['document_number_start'] ?? 0)) ?: null;
        $requestedNumberFormatSuffix = trim((string) ($data['number_format_suffix'] ?? ''));
        $existingDocumentNumber = $existingDocument?->document_number;
        $existingSequence = $this->extractDocumentNumberSequence($existingDocumentNumber);
        $isExistingNumberLocked = $this->skYayasanDocumentNumberLockSupported()
            && $existingDocument?->number_locked_at !== null;
        $canReuseExistingNumber = $existingDocumentNumber
            && (
                $isExistingNumberLocked
                || (
                $requestedStartNumber === null
                || ($existingSequence !== null && $existingSequence >= $requestedStartNumber)
                )
            );

        $documentNumber = $isExistingNumberLocked
            ? $existingDocumentNumber
            : (!empty($data['document_number'])
                ? $data['document_number']
                : ($canReuseExistingNumber
                    ? $existingDocumentNumber
                    : $this->generateDocumentNumber(
                        $template,
                        $submission,
                        $issuedDate,
                        $requestedStartNumber,
                        $requestedNumberFormatSuffix !== '' ? $requestedNumberFormatSuffix : null
                    )));

        $placeholders = $this->buildTemplatePlaceholders($submission, [
            'nomor_sk' => $documentNumber,
            'tanggal_terbit' => $this->formatIndonesianDate($issuedDate),
            'tanggal_mulai' => '01 Juli ' . $issuedDate->format('Y'),
            'tanggal_selesai' => '30 Juni ' . $issuedDate->copy()->addYear()->format('Y'),
            'tahun_sk' => $issuedDate->format('Y'),
            'tahun_sk_berikutnya' => $issuedDate->copy()->addYear()->format('Y'),
            'tahun_penerbitan_sk' => $data['school_year'],
            'nomor_sk_yayasan_mulai' => $data['document_number_start'] ?? '-',
            'nama_penandatangan' => $data['signer_name'],
            'jabatan_penandatangan' => $data['signer_position'] ?? 'Ketua Yayasan',
            'ditetapkan_di' => $data['established_at'],
            'tanggal_penetapan' => $this->formatIndonesianDate($issuedDate),
            'tanggal_penetapan_raw' => $issuedDate->toDateString(),
            'tembusan_1' => $data['copy_recipient_1'],
            'tembusan_2' => $data['copy_recipient_2'],
            'catatan_penerbitan' => $data['publication_notes'] ?? '-',
        ]);

        $renderedContent = $this->renderTemplate($template->body, $placeholders, [
            'name' => $template->name,
            'category' => $template->category,
            'document_title' => $template->document_title,
        ]);

        $document = SkYayasanDocument::query()->updateOrCreate(
            ['request_id' => $submission->id],
            [
                'template_id' => $template->id,
                'generated_by' => auth()->id(),
                'document_number' => $documentNumber,
                'issued_date' => $issuedDate->toDateString(),
                'signer_name' => $data['signer_name'],
                'signer_position' => $data['signer_position'] ?? 'Ketua Yayasan',
                'publication_notes' => $data['publication_notes'] ?? null,
                'meta_payload' => [
                    'school_year' => $data['school_year'],
                    'document_number_start' => $data['document_number_start'] ?? null,
                    'established_at' => $data['established_at'],
                    'copy_recipient_1' => $data['copy_recipient_1'],
                    'copy_recipient_2' => $data['copy_recipient_2'],
                ],
                'rendered_content' => $renderedContent,
                'status' => $submission->current_status === 'published' ? 'published' : 'draft',
                'generated_at' => now(),
            ]
        );

        $submission->update([
            'template_id' => $template->id,
        ]);

        return $document->fresh(['request.madrasah.yayasan', 'request.employee.statusKepegawaian', 'template']);
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

    private function syncedGenerateQueueRequestsConstraint(): Closure
    {
        return function (Builder $query) {
            $query->whereHas('importBatch', fn (Builder $batchQuery) => $batchQuery->where('status', 'synced'));
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
        $globalSettings = $this->getGlobalSkSettings();
        $issueDate = $document?->issued_date ?? Carbon::parse($globalSettings['issued_date']);
        $year = (int) $issueDate->format('Y');
        $copyRecipients = $this->resolveSchoolCopyRecipients($madrasah);

        return [
            'school_year' => $globalSettings['school_year'],
            'document_number_start' => (string) $globalSettings['number_start'],
            'signer_name' => $globalSettings['signer_name'],
            'signer_position' => $globalSettings['signer_position'],
            'established_at' => $globalSettings['established_at'],
            'issued_date' => $issueDate->format('Y-m-d'),
            'number_format_suffix' => $globalSettings['number_format_suffix'],
            'copy_recipient_1' => $copyRecipients['copy_recipient_1'],
            'copy_recipient_2' => $copyRecipients['copy_recipient_2'],
        ];
    }

    private function buildSchoolSubmissionLetterReferences(Collection $schools): array
    {
        $schoolIds = $schools->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->values();

        if ($schoolIds->isEmpty()) {
            return [];
        }

        return SkYayasanRequest::query()
            ->whereIn('madrasah_id', $schoolIds->all())
            ->whereHas('importBatch', fn (Builder $query) => $query->where('status', 'synced'))
            ->orderBy('madrasah_id')
            ->orderByRaw('CASE WHEN submission_letter_number IS NULL OR submission_letter_number = "" THEN 1 ELSE 0 END')
            ->orderByRaw('CASE WHEN submission_letter_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('id')
            ->get([
                'id',
                'madrasah_id',
                'submission_letter_number',
                'submission_letter_date',
            ])
            ->groupBy('madrasah_id')
            ->map(function (Collection $requests): array {
                $reference = $requests->first(function (SkYayasanRequest $request) {
                    return filled($request->submission_letter_number) || $request->submission_letter_date !== null;
                }) ?? $requests->first();

                return [
                    'submission_letter_number' => $reference?->submission_letter_number,
                    'submission_letter_date' => $reference?->submission_letter_date,
                ];
            })
            ->all();
    }

    private function prepareGenerateQueueSchools(
        Collection $schools,
        bool $numberLockSupported,
        ?Collection $uppmSummaries = null,
        ?string $uppmValidationPeriodKey = null
    ): Collection {
        if ($schools->isEmpty()) {
            return $schools->values();
        }

        $submissionLetterReferences = $this->buildSchoolSubmissionLetterReferences($schools);
        $readyLockRanges = $numberLockSupported
            ? $this->buildSchoolReadyLockRanges($schools)
            : [];

        return $schools->transform(function (Madrasah $school) use (
            $numberLockSupported,
            $readyLockRanges,
            $submissionLetterReferences,
            $uppmSummaries,
            $uppmValidationPeriodKey
        ) {
            $school->core_data = $this->buildSchoolSkCoreData(
                $school,
                null
            );
            $school->submission_letter_reference = $submissionLetterReferences[$school->id] ?? null;
            $school->locked_documents_count = $numberLockSupported
                ? (int) ($school->locked_documents_count ?? 0)
                : 0;
            $school->ready_lock_range = $readyLockRanges[$school->id]['range'] ?? null;
            $school->ready_lock_count = (int) ($readyLockRanges[$school->id]['count'] ?? 0);

            if ($uppmSummaries instanceof Collection) {
                $summary = $uppmSummaries->get((int) $school->id, []);
                $periodSummary = $uppmValidationPeriodKey ? ($summary['period_summaries'][$uppmValidationPeriodKey] ?? null) : null;
                $school->uppm_summary = [
                    'status_label' => $summary['status_label'] ?? 'Belum Lunas',
                    'remaining' => (float) ($summary['remaining'] ?? 0),
                    'period_is_lunas' => (bool) ($periodSummary['is_lunas'] ?? false),
                    'period_total_paid' => (float) ($periodSummary['total_paid'] ?? 0),
                    'period_target_nominal' => (float) ($periodSummary['target_nominal'] ?? 0),
                ];
            }

            return $school;
        })->values();
    }

    private function buildSubmissionAppointmentAlerts(Collection $submissions): array
    {
        if ($submissions->isEmpty()) {
            return [];
        }

        $batchIds = $submissions->pluck('import_batch_id')
            ->filter()
            ->unique()
            ->values();
        $employeeIds = $submissions->pluck('employee_id')
            ->filter()
            ->unique()
            ->values();

        if ($batchIds->isEmpty() || $employeeIds->isEmpty()) {
            return [];
        }

        $rowsByBatch = SkYayasanImportRow::query()
            ->whereIn('batch_id', $batchIds->all())
            ->whereIn('matched_user_id', $employeeIds->all())
            ->get([
                'batch_id',
                'matched_user_id',
                'source_keterangan',
                'source_tmt_pertama',
            ])
            ->groupBy('batch_id');

        $alerts = [];

        foreach ($submissions as $submission) {
            if (!$submission instanceof SkYayasanRequest) {
                continue;
            }

            $matchedRow = collect($rowsByBatch->get($submission->import_batch_id, []))
                ->first(fn (SkYayasanImportRow $row) => (int) $row->matched_user_id === (int) $submission->employee_id);

            $keterangan = $this->normalizeSkYayasanKeteranganLabel($matchedRow?->source_keterangan);
            if (!in_array($keterangan, ['Pengangkatan GTY', 'Pengangkatan PTY'], true)) {
                continue;
            }

            $tmtDate = $this->parseFlexibleDate($matchedRow?->source_tmt_pertama ?: $submission->employee?->tmt);
            if ($tmtDate === null) {
                continue;
            }

            $isTwoYearsOrMore = $tmtDate->copy()->addYears(2)->startOfDay()->lessThanOrEqualTo(now()->startOfDay());
            if ($isTwoYearsOrMore) {
                continue;
            }

            $alerts[$submission->id] = [
                'keterangan' => $keterangan,
                'tmt_date' => $tmtDate,
                'tmt_label' => $this->formatIndonesianDate($tmtDate),
                'tenure_label' => $this->formatTenureFromTmt($tmtDate, null, now()),
            ];
        }

        return $alerts;
    }

    private function latestSchoolGeneratedDocument(int $madrasahId): ?SkYayasanDocument
    {
        return SkYayasanDocument::query()
            ->with('request')
            ->whereHas('request', fn (Builder $query) => $query->where('madrasah_id', $madrasahId))
            ->latest('generated_at')
            ->latest('published_at')
            ->first();
    }

    private function schoolDocumentsQuery(Madrasah $madrasah): Builder
    {
        return SkYayasanDocument::query()
            ->whereHas('request', fn (Builder $query) => $query->where('madrasah_id', $madrasah->id));
    }

    private function generateQueueSchoolsQuery(
        ?int $uppmValidationYear = null,
        ?string $uppmValidationPeriodKey = null,
        bool $applyPaymentGate = true
    ): Builder
    {
        $baseQuery = Madrasah::query()
            ->whereHas('skYayasanImportBatches', fn (Builder $query) => $query->where('status', 'synced'));

        $uppmPaymentStatusService = app(UppmPaymentStatusService::class);
        if ($uppmValidationYear === null || $uppmValidationPeriodKey === null) {
            $paymentRequirement = $uppmPaymentStatusService->resolveSkPaymentRequirement($this->getGlobalSkSettings()['issued_date'] ?? null);
            $uppmValidationYear = (int) $paymentRequirement['year'];
            $uppmValidationPeriodKey = (string) $paymentRequirement['period_key'];
        }

        if ($applyPaymentGate && $uppmPaymentStatusService->shouldEnforceSkGenerateGate($uppmValidationYear)) {
            $eligibleSchoolIds = $uppmPaymentStatusService->eligibleSchoolIdsForPeriod(
                $baseQuery->pluck('id'),
                $uppmValidationYear,
                $uppmValidationPeriodKey
            );
            $baseQuery->whereKey($eligibleSchoolIds->all());
        }

        return $baseQuery
            ->orderByRaw("CASE WHEN scod IS NULL OR scod = '' THEN 1 ELSE 0 END")
            ->orderByRaw('CAST(COALESCE(NULLIF(scod, \'\'), \'0\') AS UNSIGNED) ASC')
            ->orderBy('name');
    }

    private function buildGenerateAppointmentRequestsTable(Collection $schools): Collection
    {
        $schoolOrder = $schools->values()->pluck('id')->mapWithKeys(
            fn ($schoolId, int $index) => [(int) $schoolId => $index]
        );

        if ($schoolOrder->isEmpty()) {
            return collect();
        }

        $requests = SkYayasanRequest::query()
            ->with([
                'madrasah:id,name,scod,kabupaten',
                'employee:id,name,nip,tanggal_lahir,tmt',
            ])
            ->whereIn('madrasah_id', $schoolOrder->keys()->all())
            ->where('current_status', '!=', 'rejected')
            ->whereHas('importBatch', fn (Builder $query) => $query->where('status', 'synced'))
            ->get([
                'id',
                'madrasah_id',
                'employee_id',
                'import_batch_id',
                'request_number',
                'submitted_at',
            ]);

        $batchIds = $requests->pluck('import_batch_id')
            ->filter()
            ->unique()
            ->values();

        $rowsByBatch = $batchIds->isEmpty()
            ? collect()
            : SkYayasanImportRow::query()
                ->whereIn('batch_id', $batchIds)
                ->get([
                    'batch_id',
                    'matched_user_id',
                    'source_keterangan',
                    'source_tanggal_lahir',
                    'source_tmt_pertama',
                ])
                ->groupBy('batch_id');

        $schoolScods = $schools->mapWithKeys(
            fn (Madrasah $school) => [(int) $school->id => $this->normalizeNipmSchoolScod($school->scod)]
        );

        $usedSequencesBySchool = [];
        $existingTeachers = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereIn('madrasah_id', $schoolOrder->keys()->all())
            ->get(['id', 'madrasah_id', 'nip']);

        foreach ($existingTeachers as $teacher) {
            $schoolId = (int) $teacher->madrasah_id;
            $sequence = $this->extractNipmSequence($teacher->nip, $schoolScods->get($schoolId));

            if ($sequence === null) {
                continue;
            }

            $usedSequencesBySchool[$schoolId][$sequence] = true;
        }

        $assignedNipmByEmployee = [];

        return $requests
            ->map(function (SkYayasanRequest $request) use ($rowsByBatch, $schoolOrder) {
                $matchedRow = collect($rowsByBatch->get($request->import_batch_id, []))
                    ->first(fn (SkYayasanImportRow $row) => (int) $row->matched_user_id === (int) $request->employee_id);

                $keterangan = $this->normalizeSkYayasanKeteranganLabel($matchedRow?->source_keterangan);

                if (!in_array($keterangan, ['Pengangkatan GTY', 'Pengangkatan PTY'], true)) {
                    return null;
                }

                return [
                    'school_id' => (int) $request->madrasah_id,
                    'school_order' => (int) ($schoolOrder->get((int) $request->madrasah_id) ?? PHP_INT_MAX),
                    'school_name' => $request->madrasah?->name ?? '-',
                    'school_scod' => $request->madrasah?->scod ?: '-',
                    'teacher_id' => (int) $request->employee_id,
                    'teacher_name' => $request->employee?->name ?? '-',
                    'keterangan' => $keterangan,
                    'existing_nipm' => $request->employee?->nip,
                    'birth_date' => $matchedRow?->source_tanggal_lahir ?: $request->employee?->tanggal_lahir,
                    'tmt_date' => $matchedRow?->source_tmt_pertama ?: $request->employee?->tmt,
                    'submission_year' => optional($request->submitted_at)->format('Y') ?: '-',
                    'submitted_at' => $request->submitted_at,
                ];
            })
            ->filter()
            ->sortBy([
                ['school_order', 'asc'],
                ['keterangan', 'asc'],
                ['teacher_name', 'asc'],
            ])
            ->map(function (array $row) use (&$usedSequencesBySchool, &$assignedNipmByEmployee, $schoolScods) {
                $teacherId = (int) $row['teacher_id'];
                $schoolId = (int) $row['school_id'];
                $normalizedScod = $schoolScods->get($schoolId, '000');

                if (isset($assignedNipmByEmployee[$teacherId])) {
                    $row['nipm_value'] = $assignedNipmByEmployee[$teacherId]['value'];
                    $row['nipm_synced'] = (bool) ($assignedNipmByEmployee[$teacherId]['synced'] ?? false);
                    $row['has_nipm_source_choice'] = (bool) ($assignedNipmByEmployee[$teacherId]['has_choice'] ?? false);
                    $row['default_nipm_mode'] = $assignedNipmByEmployee[$teacherId]['mode'] ?? 'system';
                    $row['existing_nipm_value'] = $assignedNipmByEmployee[$teacherId]['existing_value'] ?? '';
                    $row['system_nipm_value'] = $assignedNipmByEmployee[$teacherId]['system_value'] ?? $row['nipm_value'];

                    return $row;
                }

                $existingNipm = $this->normalizeNipmValue($row['existing_nipm']);
                $existingSequence = $this->extractNipmSequence($existingNipm, $normalizedScod);

                $birthDate = $this->parseFlexibleDate($row['birth_date']);
                $tmtDate = $this->parseFlexibleDate($row['tmt_date']);

                if ($birthDate === null || $tmtDate === null) {
                    $result = [
                        'value' => '',
                        'synced' => false,
                        'has_choice' => false,
                        'mode' => 'system',
                        'existing_value' => $existingNipm ?? '',
                        'system_value' => '',
                    ];

                    $assignedNipmByEmployee[$teacherId] = $result;
                    $row['nipm_value'] = $result['value'];
                    $row['nipm_synced'] = $result['synced'];
                    $row['has_nipm_source_choice'] = $result['has_choice'];
                    $row['default_nipm_mode'] = $result['mode'];
                    $row['existing_nipm_value'] = $result['existing_value'];
                    $row['system_nipm_value'] = $result['system_value'];
                    $row['tmt_is_two_years_or_more'] = false;

                    return $row;
                }

                $row['tmt_is_two_years_or_more'] = $tmtDate->copy()->addYears(2)->startOfDay()->lessThanOrEqualTo(now()->startOfDay());

                $nextSequence = $this->nextReasonableNipmSequence($usedSequencesBySchool[$schoolId] ?? []);
                $usedSequencesBySchool[$schoolId][$nextSequence] = true;
                $systemGeneratedNipm = $birthDate->format('Ymd') . $tmtDate->format('Ym') . $normalizedScod . str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);

                if ($existingNipm !== null && $existingSequence !== null) {
                    $hasChoice = $existingNipm !== $systemGeneratedNipm;
                    $result = [
                        'value' => $hasChoice ? $existingNipm : $systemGeneratedNipm,
                        'synced' => !$hasChoice,
                        'has_choice' => $hasChoice,
                        'mode' => $hasChoice ? 'existing' : 'system',
                        'existing_value' => $existingNipm,
                        'system_value' => $systemGeneratedNipm,
                    ];

                    $assignedNipmByEmployee[$teacherId] = $result;
                    $row['nipm_value'] = $result['value'];
                    $row['nipm_synced'] = $result['synced'];
                    $row['has_nipm_source_choice'] = $result['has_choice'];
                    $row['default_nipm_mode'] = $result['mode'];
                    $row['existing_nipm_value'] = $result['existing_value'];
                    $row['system_nipm_value'] = $result['system_value'];

                    return $row;
                }

                $result = [
                    'value' => $systemGeneratedNipm,
                    'synced' => false,
                    'has_choice' => false,
                    'mode' => 'system',
                    'existing_value' => '',
                    'system_value' => $systemGeneratedNipm,
                ];

                $assignedNipmByEmployee[$teacherId] = $result;
                $row['nipm_value'] = $result['value'];
                $row['nipm_synced'] = $result['synced'];
                $row['has_nipm_source_choice'] = $result['has_choice'];
                $row['default_nipm_mode'] = $result['mode'];
                $row['existing_nipm_value'] = $result['existing_value'];
                $row['system_nipm_value'] = $result['system_value'];

                return $row;
            })
            ->values();
    }

    private function normalizeNipmSchoolScod(mixed $scod): string
    {
        $digits = preg_replace('/\D+/u', '', trim((string) $scod)) ?? '';

        if ($digits === '') {
            return '000';
        }

        return str_pad(substr($digits, -3), 3, '0', STR_PAD_LEFT);
    }

    private function normalizeNipmValue(mixed $value): ?string
    {
        $digits = preg_replace('/\D+/u', '', trim((string) $value)) ?? '';

        return strlen($digits) === 20 ? $digits : null;
    }

    private function extractNipmSequence(mixed $value, ?string $expectedScod = null): ?int
    {
        $nipm = $this->normalizeNipmValue($value);

        if ($nipm === null) {
            return null;
        }

        if ($expectedScod !== null && substr($nipm, 14, 3) !== $expectedScod) {
            return null;
        }

        $sequence = (int) substr($nipm, 17, 3);

        return $sequence > 0 ? $sequence : null;
    }

    private function nextReasonableNipmSequence(array $usedSequences): int
    {
        if (empty($usedSequences)) {
            return 1;
        }

        $sequences = array_keys($usedSequences);
        sort($sequences, SORT_NUMERIC);

        $lastReasonableSequence = (int) $sequences[0];
        $outlierGapThreshold = 50;

        foreach (array_slice($sequences, 1) as $sequence) {
            $sequence = (int) $sequence;

            if (($sequence - $lastReasonableSequence) > $outlierGapThreshold) {
                break;
            }

            $lastReasonableSequence = $sequence;
        }

        return $lastReasonableSequence + 1;
    }

    private function assignTemporaryDocumentNumbers(Collection $documents): void
    {
        foreach ($documents as $document) {
            if (!$document instanceof SkYayasanDocument) {
                continue;
            }

            SkYayasanDocument::query()
                ->whereKey($document->id)
                ->update([
                    'document_number' => 'TMP-SKY-' . $document->id . '-' . Str::upper(Str::random(10)),
                    'updated_at' => now(),
                ]);
        }
    }

    private function buildAssignedDocumentNumbers(
        Collection $payloads,
        Carbon $issuedDate,
        int $startNumber,
        ?string $preferredNumberFormatSuffix = null
    ): array {
        if ($payloads->isEmpty()) {
            return [];
        }

        $renumberableRequestIds = $payloads
            ->pluck('submission.document.id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        $reservedSequences = SkYayasanDocument::query()
            ->when($renumberableRequestIds->isNotEmpty(), fn (Builder $query) => $query->whereNotIn('id', $renumberableRequestIds->all()))
            ->pluck('document_number')
            ->map(fn (?string $documentNumber) => $this->extractDocumentNumberSequence($documentNumber))
            ->filter(fn (?int $sequence) => $sequence !== null)
            ->flip();

        $nextSequence = $startNumber;
        $assignedNumbers = [];

        foreach ($payloads as $payload) {
            while ($reservedSequences->has($nextSequence)) {
                $nextSequence++;
            }

            /** @var \App\Models\SkYayasanRequest $submission */
            $submission = $payload['submission'];
            $assignedNumbers[$submission->id] = $this->formatDocumentNumberFromSequence(
                $nextSequence,
                $issuedDate,
                $preferredNumberFormatSuffix
            );
            $reservedSequences->put($nextSequence, true);
            $nextSequence++;
        }

        return $assignedNumbers;
    }

    private function buildSchoolReadyLockRanges(Collection $schools): array
    {
        $schoolIds = $schools
            ->pluck('id')
            ->filter(fn ($id) => !is_null($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($schoolIds->isEmpty()) {
            return [];
        }

        $readyCounts = DB::table('sk_yayasan_documents')
            ->join('sk_yayasan_requests', 'sk_yayasan_requests.id', '=', 'sk_yayasan_documents.request_id')
            ->whereIn('sk_yayasan_requests.madrasah_id', $schoolIds->all())
            ->whereNotNull('sk_yayasan_documents.document_number')
            ->whereNull('sk_yayasan_documents.number_locked_at')
            ->selectRaw('sk_yayasan_requests.madrasah_id, COUNT(*) as total')
            ->groupBy('sk_yayasan_requests.madrasah_id')
            ->pluck('total', 'sk_yayasan_requests.madrasah_id');

        $lockedDocumentNumbers = DB::table('sk_yayasan_documents')
            ->whereNotNull('document_number')
            ->whereNotNull('number_locked_at')
            ->pluck('document_number');

        $highestLockedSequence = $lockedDocumentNumbers
            ->map(fn ($documentNumber) => $this->extractDocumentNumberSequence($documentNumber))
            ->filter(fn ($sequence) => $sequence !== null)
            ->max();

        $unlockedDocumentNumbers = DB::table('sk_yayasan_documents')
            ->join('sk_yayasan_requests', 'sk_yayasan_requests.id', '=', 'sk_yayasan_documents.request_id')
            ->whereIn('sk_yayasan_requests.madrasah_id', $schoolIds->all())
            ->whereNotNull('sk_yayasan_documents.document_number')
            ->whereNull('sk_yayasan_documents.number_locked_at')
            ->pluck('sk_yayasan_documents.document_number');

        $lowestUnlockedSequence = $unlockedDocumentNumbers
            ->map(fn ($documentNumber) => $this->extractDocumentNumberSequence($documentNumber))
            ->filter(fn ($sequence) => $sequence !== null)
            ->min();

        $globalStartNumber = (int) $this->getGlobalSkSettings()['number_start'];
        $nextSequence = $highestLockedSequence !== null
            ? ((int) $highestLockedSequence + 1)
            : ((int) ($lowestUnlockedSequence ?? $globalStartNumber));

        $ranges = [];

        foreach ($schools as $school) {
            $count = (int) ($readyCounts[$school->id] ?? 0);

            if ($count <= 0) {
                $ranges[$school->id] = [
                    'count' => 0,
                    'range' => null,
                ];

                continue;
            }

            $startSequence = $nextSequence;
            $endSequence = $nextSequence + $count - 1;

            $ranges[$school->id] = [
                'count' => $count,
                'range' => $startSequence === $endSequence
                    ? (string) $startSequence
                    : ($startSequence . ' - ' . $endSequence),
            ];

            $nextSequence = $endSequence + 1;
        }

        return $ranges;
    }

    private function skYayasanDocumentNumberLockSupported(): bool
    {
        static $supported = null;

        if ($supported === null) {
            $supported = Schema::hasColumns('sk_yayasan_documents', [
                'number_locked_at',
                'number_locked_by',
            ]);
        }

        return $supported;
    }

    private function skYayasanDashboardSupported(): bool
    {
        static $supported = null;

        if ($supported === null) {
            $supported =
                Schema::hasTable('madrasahs')
                && Schema::hasTable('users')
                && $this->skYayasanTableHasColumns('sk_yayasan_requests', [
                    'madrasah_id',
                    'employee_id',
                    'template_id',
                    'request_type',
                    'current_status',
                    'submitted_at',
                ])
                && $this->skYayasanTableHasColumns('sk_yayasan_documents', [
                    'request_id',
                    'template_id',
                    'status',
                    'published_at',
                ])
                && $this->skYayasanTableHasColumns('sk_yayasan_import_batches', [
                    'status',
                ])
                && $this->skYayasanTableHasColumns('sk_yayasan_templates', [
                    'name',
                    'is_active',
                ]);
        }

        return $supported;
    }

    private function skYayasanTableHasColumns(string $table, array $columns): bool
    {
        return Schema::hasTable($table) && Schema::hasColumns($table, $columns);
    }

    private function emptySkYayasanDashboardPayload(?string $warning = null): array
    {
        return [
            'statusCounts' => collect(),
            'documentCounts' => collect(),
            'latestRequests' => collect(),
            'schoolSummaries' => collect(),
            'pendingImportBatches' => 0,
            'rejectedImportBatches' => 0,
            'activeTemplates' => 0,
            'publishedThisMonth' => 0,
            'dashboardWarning' => $warning,
        ];
    }

    private function resolveSchoolCopyRecipients(Madrasah $madrasah): array
    {
        $specialMadrasahIds = [6, 7, 43, 45];
        $regionLabel = $this->resolveMadrasahRegionLabel($madrasah);

        if (in_array((int) $madrasah->id, $specialMadrasahIds, true)) {
            return [
                'copy_recipient_1' => 'Kepala Kantor Wilayah Kementerian Agama DIY',
                'copy_recipient_2' => $regionLabel === 'setempat'
                    ? 'Kepala Kantor Kementerian Agama setempat'
                    : 'Kepala Kantor Kementerian Agama ' . $regionLabel,
            ];
        }

        return [
            'copy_recipient_1' => 'Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY',
            'copy_recipient_2' => $regionLabel === 'setempat'
                ? 'Kepala Balai Pendidikan Menengah setempat'
                : 'Kepala Balai Pendidikan Menengah ' . $regionLabel,
        ];
    }

    private function resolveMadrasahRegionLabel(Madrasah $madrasah): string
    {
        $kabupaten = trim((string) ($madrasah->kabupaten ?? ''));

        if ($kabupaten === '') {
            return 'setempat';
        }

        if (preg_match('/^(kabupaten|kota)\s+/iu', $kabupaten) === 1) {
            return preg_replace('/\s+/u', ' ', $kabupaten) ?? $kabupaten;
        }

        return 'Kabupaten ' . preg_replace('/\s+/u', ' ', $kabupaten);
    }

    private function getGlobalSkSettings(): array
    {
        $settings = AppSetting::getSettings();
        $issuedDate = $settings->sk_yayasan_issued_date
            ? Carbon::parse($settings->sk_yayasan_issued_date)
            : now();

        return [
            'school_year' => $settings->sk_yayasan_school_year ?: ($issuedDate->format('Y') . '-' . $issuedDate->copy()->addYear()->format('Y')),
            'number_start' => (int) ($settings->sk_yayasan_number_start ?: 1),
            'signer_name' => (string) ($settings->sk_yayasan_signer_name ?: ''),
            'signer_position' => (string) ($settings->sk_yayasan_signer_position ?: 'Ketua Yayasan'),
            'established_at' => (string) ($settings->sk_yayasan_established_at ?: 'Yogyakarta'),
            'issued_date' => $issuedDate->toDateString(),
            'number_format_suffix' => (string) ($settings->sk_yayasan_number_format_suffix ?: 'SK.02/LPM.DIY/{month_roman}/{year}'),
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

        return $this->detectEmploymentTypeFromText($source);
    }

    private function detectEmploymentTypeFromText(string $source): ?string
    {
        $source = $this->normalizeTemplateText($source);

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

    private function activeSuperAdminSubmissionQuery(): Builder
    {
        return SkYayasanRequest::query()
            ->where('current_status', '!=', 'rejected')
            ->whereDoesntHave('importBatch', fn ($query) => $query->where('status', 'rejected'));
    }

    private function buildSuperAdminPengajuanSummary(): array
    {
        $keteranganOptions = SkYayasanImportSynchronizer::allowedKeteranganOptions();
        $fallbackKeteranganLabel = 'Tanpa Keterangan';
        $schools = Madrasah::query()
            ->orderByRaw("CASE WHEN scod IS NULL OR scod = '' THEN 1 ELSE 0 END")
            ->orderBy('scod')
            ->orderBy('name')
            ->get(['id', 'scod', 'name', 'kabupaten']);

        $activeRequests = $this->activeSuperAdminSubmissionQuery()
            ->get(['id', 'madrasah_id', 'employee_id', 'import_batch_id']);

        $activeRequestBatchIds = $activeRequests->pluck('import_batch_id')
            ->filter()
            ->unique()
            ->values();

        $requestRowsByBatch = $activeRequestBatchIds->isEmpty()
            ? collect()
            : SkYayasanImportRow::query()
                ->whereIn('batch_id', $activeRequestBatchIds)
                ->get(['batch_id', 'matched_user_id', 'source_keterangan'])
                ->groupBy('batch_id');

        $activeBatches = SkYayasanImportBatch::query()
            ->whereIn('status', ['pending_review', 'synced'])
            ->orderByDesc('uploaded_at')
            ->get([
                'id',
                'madrasah_id',
                'status',
                'uploaded_at',
                'synced_at',
                'total_rows',
                'valid_rows',
                'invalid_rows',
            ]);

        $latestActiveBatches = $activeBatches
            ->unique('madrasah_id')
            ->keyBy(fn (SkYayasanImportBatch $batch) => (int) $batch->madrasah_id);

        $activeBatchCountsBySchool = $activeBatches
            ->groupBy('madrasah_id')
            ->map(fn (Collection $batches) => $batches->count());

        $latestBatchIds = $latestActiveBatches->pluck('id')->filter()->values();
        $latestBatchUnmatchedCounts = $latestBatchIds->isEmpty()
            ? collect()
            : SkYayasanImportRow::query()
                ->selectRaw('batch_id, COUNT(*) as total')
                ->whereIn('batch_id', $latestBatchIds)
                ->whereNull('matched_user_id')
                ->groupBy('batch_id')
                ->pluck('total', 'batch_id');

        $rejectedRequestCountsBySchool = SkYayasanRequest::query()
            ->where('current_status', 'rejected')
            ->selectRaw('madrasah_id, COUNT(*) as total')
            ->groupBy('madrasah_id')
            ->pluck('total', 'madrasah_id');

        $rejectedBatchCountsBySchool = SkYayasanImportBatch::query()
            ->where('status', 'rejected')
            ->selectRaw('madrasah_id, COUNT(*) as total')
            ->groupBy('madrasah_id')
            ->pluck('total', 'madrasah_id');

        $schoolSummaries = $schools->mapWithKeys(function (Madrasah $school) use ($keteranganOptions) {
            $keteranganCounts = [];

            foreach ($keteranganOptions as $option) {
                $keteranganCounts[$option] = 0;
            }

            return [
                (int) $school->id => [
                    'school_id' => (int) $school->id,
                    'scod' => $school->scod,
                    'school_name' => $school->name,
                    'kabupaten' => $school->kabupaten,
                    'total_requests' => 0,
                    'active_batch_count' => 0,
                    'latest_batch_status' => null,
                    'latest_batch_uploaded_at' => null,
                    'latest_batch_unmatched_count' => 0,
                    'rejected_requests_count' => 0,
                    'rejected_batch_count' => 0,
                    'keterangan_counts' => $keteranganCounts,
                ],
            ];
        });

        $overallKeteranganCounts = collect($keteranganOptions)
            ->mapWithKeys(fn (string $option) => [$option => 0])
            ->all();

        foreach ($activeRequests as $submission) {
            $schoolId = (int) $submission->madrasah_id;
            if (!$schoolSummaries->has($schoolId)) {
                continue;
            }

            $summary = $schoolSummaries[$schoolId];
            $summary['total_requests']++;

            $matchedRow = collect($requestRowsByBatch->get($submission->import_batch_id, []))
                ->first(fn (SkYayasanImportRow $row) => (int) $row->matched_user_id === (int) $submission->employee_id);

            $keteranganLabel = $this->normalizeSkYayasanKeteranganLabel($matchedRow?->source_keterangan)
                ?? $fallbackKeteranganLabel;

            if (!array_key_exists($keteranganLabel, $summary['keterangan_counts'])) {
                $summary['keterangan_counts'][$keteranganLabel] = 0;
            }

            if (!array_key_exists($keteranganLabel, $overallKeteranganCounts)) {
                $overallKeteranganCounts[$keteranganLabel] = 0;
            }

            $summary['keterangan_counts'][$keteranganLabel]++;
            $overallKeteranganCounts[$keteranganLabel]++;

            $schoolSummaries[$schoolId] = $summary;
        }

        foreach ($schoolSummaries as $schoolId => $summary) {
            $latestBatch = $latestActiveBatches->get((int) $schoolId);
            $summary['active_batch_count'] = (int) ($activeBatchCountsBySchool->get((int) $schoolId) ?? 0);
            $summary['latest_batch_status'] = $latestBatch?->status;
            $summary['latest_batch_uploaded_at'] = $latestBatch?->uploaded_at;
            $summary['latest_batch_unmatched_count'] = (int) ($latestBatch ? ($latestBatchUnmatchedCounts->get($latestBatch->id) ?? 0) : 0);
            $summary['rejected_requests_count'] = (int) ($rejectedRequestCountsBySchool->get((int) $schoolId) ?? 0);
            $summary['rejected_batch_count'] = (int) ($rejectedBatchCountsBySchool->get((int) $schoolId) ?? 0);

            if ($summary['total_requests'] > 0 || $summary['active_batch_count'] > 0) {
                $summary['submission_status_label'] = 'Sudah Mengajukan';
            } elseif ($summary['rejected_requests_count'] > 0 || $summary['rejected_batch_count'] > 0) {
                $summary['submission_status_label'] = 'Ditolak';
            } else {
                $summary['submission_status_label'] = 'Belum Mengajukan';
            }

            $schoolSummaries[$schoolId] = $summary;
        }

        $rows = $schoolSummaries->values();
        $submittedSchoolsCount = $rows->filter(fn (array $row) => $row['submission_status_label'] === 'Sudah Mengajukan')->count();
        $notSubmittedSchoolsCount = $rows->count() - $submittedSchoolsCount;
        $totalWithoutNuist = $rows->sum('latest_batch_unmatched_count');

        $exportHeadings = array_merge([
            'No',
            'SCOD',
            'Nama Sekolah',
            'Kabupaten',
            'Status Pengajuan',
            'Jumlah Pengajuan SK Yayasan',
            'Jumlah Batch Aktif',
            'Status Batch Terakhir',
            'Upload Batch Terakhir',
            'Jumlah Pengajuan Belum Memiliki Akun NUist',
            'Jumlah Pengajuan Ditolak',
            'Jumlah Batch Ditolak',
        ], $this->summaryKeteranganHeadings($overallKeteranganCounts));

        $exportRows = $rows->values()->map(function (array $row, int $index) use ($overallKeteranganCounts) {
            $baseColumns = [
                $index + 1,
                $row['scod'] ?: '-',
                $row['school_name'] ?: '-',
                $row['kabupaten'] ?: '-',
                $row['submission_status_label'],
                $row['total_requests'],
                $row['active_batch_count'],
                $this->formatImportBatchStatusLabel($row['latest_batch_status']),
                optional($row['latest_batch_uploaded_at'])->format('d/m/Y H:i') ?: '-',
                $row['latest_batch_unmatched_count'],
                $row['rejected_requests_count'],
                $row['rejected_batch_count'],
            ];

            $keteranganColumns = collect(array_keys($overallKeteranganCounts))
                ->map(fn (string $label) => (int) ($row['keterangan_counts'][$label] ?? 0))
                ->all();

            return array_merge($baseColumns, $keteranganColumns);
        });

        return [
            'cards' => [
                'submitted_schools' => $submittedSchoolsCount,
                'not_submitted_schools' => $notSubmittedSchoolsCount,
                'total_requests' => $activeRequests->count(),
                'requests_without_nuist_account' => $totalWithoutNuist,
            ],
            'keterangan_counts' => collect($overallKeteranganCounts)
                ->filter(fn (int $count) => $count > 0)
                ->sortKeys()
                ->all(),
            'rows' => $rows,
            'export_rows' => $exportRows,
            'export_headings' => $exportHeadings,
        ];
    }

    private function normalizeSkYayasanKeteranganLabel(?string $value): ?string
    {
        $normalized = trim((string) $value);

        if ($normalized === '') {
            return null;
        }

        foreach (SkYayasanImportSynchronizer::allowedKeteranganOptions() as $option) {
            if (Str::lower($option) === Str::lower($normalized)) {
                return $option;
            }
        }

        return $normalized;
    }

    private function summaryKeteranganHeadings(array $keteranganCounts): array
    {
        return collect(array_keys($keteranganCounts))
            ->map(fn (string $label) => 'Jumlah ' . $label)
            ->values()
            ->all();
    }

    private function formatImportBatchStatusLabel(?string $status): string
    {
        return match ($status) {
            'pending_review' => 'Pending Review',
            'synced' => 'Tersinkron',
            'rejected' => 'Ditolak',
            default => '-',
        };
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

    private function synchronizeBatchRequestsFromRows(SkYayasanImportBatch $batch): array
    {
        $batch->load(['rows', 'requests']);

        $validRows = $batch->rows
            ->filter(fn (SkYayasanImportRow $row) => $row->is_valid && $row->matched_user_id)
            ->unique(fn (SkYayasanImportRow $row) => (int) $row->matched_user_id)
            ->values();

        $employeeIds = $validRows->pluck('matched_user_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $referenceRequest = $batch->requests->sortBy('id')->first();

        $requestsToDelete = $batch->requests
            ->filter(fn (SkYayasanRequest $request) => !$employeeIds->contains((int) $request->employee_id))
            ->values();

        $deleted = 0;

        if ($requestsToDelete->isNotEmpty()) {
            $requestIds = $requestsToDelete->pluck('id')->values();

            SkYayasanDocument::query()->whereIn('request_id', $requestIds)->delete();
            SkYayasanRequest::query()->whereIn('id', $requestIds)->delete();

            $deleted = $requestIds->count();
        }

        if ($validRows->isEmpty()) {
            return [
                'created' => 0,
                'linked' => 0,
                'updated' => 0,
                'deleted' => $deleted,
                'total' => 0,
            ];
        }

        $employees = User::query()
            ->with('statusKepegawaian')
            ->whereIn('id', $employeeIds)
            ->get()
            ->keyBy('id');

        $existingRequests = SkYayasanRequest::query()
            ->where('import_batch_id', $batch->id)
            ->get()
            ->keyBy(fn (SkYayasanRequest $request) => (int) $request->employee_id);

        $orphanRequests = SkYayasanRequest::query()
            ->where('madrasah_id', (int) $batch->madrasah_id)
            ->whereNull('import_batch_id')
            ->whereIn('employee_id', $employeeIds)
            ->where('current_status', '!=', 'rejected')
            ->orderBy('submitted_at')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (SkYayasanRequest $request) => (int) $request->employee_id);

        $created = 0;
        $linked = 0;
        $updated = 0;

        foreach ($validRows as $row) {
            $employeeId = (int) $row->matched_user_id;
            $employee = $employees->get($employeeId);

            if (!$employee) {
                continue;
            }

            $request = $existingRequests->get($employeeId);

            if (!$request) {
                $candidateRequests = $orphanRequests->get($employeeId);
                $orphanRequest = $candidateRequests instanceof Collection ? $candidateRequests->shift() : null;

                if ($orphanRequest) {
                    $orphanRequest->update([
                        'import_batch_id' => $batch->id,
                    ]);

                    $request = $orphanRequest->fresh();
                    $linked++;
                } else {
                    $request = $this->createRequestWithGeneratedNumber([
                        'madrasah_id' => (int) $batch->madrasah_id,
                        'import_batch_id' => $batch->id,
                        'employee_id' => $employeeId,
                        'submitted_by' => (int) ($batch->uploaded_by ?: auth()->id()),
                        'submission_letter_number' => $referenceRequest?->submission_letter_number,
                        'submission_letter_date' => $referenceRequest?->submission_letter_date,
                        'request_type' => 'perpanjangan',
                        'employment_category' => $employee->statusKepegawaian?->name ?? $employee->ketugasan,
                        'current_status' => 'submitted',
                        'submitted_at' => $batch->uploaded_at ?: now(),
                    ]);

                    $created++;
                }

                $existingRequests->put($employeeId, $request);
            }

            $requestUpdate = [];
            $employmentCategory = $employee->statusKepegawaian?->name ?? $employee->ketugasan;

            if ((int) $request->import_batch_id !== (int) $batch->id) {
                $requestUpdate['import_batch_id'] = $batch->id;
            }

            if ((int) $request->madrasah_id !== (int) $batch->madrasah_id) {
                $requestUpdate['madrasah_id'] = (int) $batch->madrasah_id;
            }

            if ($employmentCategory && $request->employment_category !== $employmentCategory) {
                $requestUpdate['employment_category'] = $employmentCategory;
            }

            if (empty($request->submitted_by) && !empty($batch->uploaded_by)) {
                $requestUpdate['submitted_by'] = (int) $batch->uploaded_by;
            }

            if (empty($request->submitted_at) && $batch->uploaded_at) {
                $requestUpdate['submitted_at'] = $batch->uploaded_at;
            }

            if (!empty($requestUpdate)) {
                $request->update($requestUpdate);
                $updated++;
            }
        }

        return [
            'created' => $created,
            'linked' => $linked,
            'updated' => $updated,
            'deleted' => $deleted,
            'total' => $validRows->count(),
        ];
    }

    private function repairSyncedBatchesRequests(?int $madrasahId = null): void
    {
        $batches = SkYayasanImportBatch::query()
            ->with(['rows', 'requests'])
            ->where('status', 'synced')
            ->when($madrasahId !== null, fn (Builder $query) => $query->where('madrasah_id', $madrasahId))
            ->orderByDesc('synced_at')
            ->get();

        foreach ($batches as $batch) {
            $expectedRequestCount = $batch->rows
                ->filter(fn (SkYayasanImportRow $row) => $row->is_valid && $row->matched_user_id)
                ->unique(fn (SkYayasanImportRow $row) => (int) $row->matched_user_id)
                ->count();

            if ($expectedRequestCount === 0) {
                continue;
            }

            if ($batch->requests->count() >= $expectedRequestCount) {
                continue;
            }

            $this->synchronizeBatchRequestsFromRows($batch);
        }
    }

    private function createRequestWithGeneratedNumber(array $attributes): SkYayasanRequest
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $attributes['request_number'] = $this->generateRequestNumber();

                return SkYayasanRequest::query()->create($attributes);
            } catch (UniqueConstraintViolationException $exception) {
                if (!$this->isDuplicateRequestNumberException($exception) || $attempt === $maxAttempts) {
                    throw $exception;
                }
            }
        }

        throw new \RuntimeException('Gagal membuat nomor pengajuan SK Yayasan yang unik.');
    }

    private function generateRequestNumber(): string
    {
        $prefix = 'REQ-SKY/' . now()->format('Ym');
        $lastSequence = SkYayasanRequest::query()
            ->where('request_number', 'like', $prefix . '/%')
            ->lockForUpdate()
            ->pluck('request_number')
            ->map(fn (?string $requestNumber) => $this->extractRequestNumberSequence($requestNumber, $prefix))
            ->filter(fn (?int $sequence) => $sequence !== null)
            ->max();

        $sequence = str_pad((string) (($lastSequence ?? 0) + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . '/' . $sequence;
    }

    private function extractRequestNumberSequence(?string $requestNumber, string $prefix): ?int
    {
        if (!$requestNumber || !Str::startsWith($requestNumber, $prefix . '/')) {
            return null;
        }

        $sequence = Str::afterLast($requestNumber, '/');

        return ctype_digit($sequence) ? (int) $sequence : null;
    }

    private function isDuplicateRequestNumberException(UniqueConstraintViolationException $exception): bool
    {
        return str_contains($exception->getMessage(), 'sk_yayasan_requests_request_number_unique');
    }

    private function generateDocumentNumber(
        SkYayasanTemplate $template,
        SkYayasanRequest $submission,
        Carbon $issuedDate,
        ?int $preferredStartNumber = null,
        ?string $preferredNumberFormatSuffix = null
    ): string
    {
        $globalSettings = $this->getGlobalSkSettings();
        $startNumber = max(1, $preferredStartNumber ?? (int) $globalSettings['number_start']);
        $lastUsedSequence = SkYayasanDocument::query()
            ->pluck('document_number')
            ->map(function (?string $documentNumber) {
                return $this->extractDocumentNumberSequence($documentNumber);
            })
            ->filter(fn ($value) => $value !== null)
            ->max();

        $nextSequence = $lastUsedSequence !== null
            ? max($startNumber, ((int) $lastUsedSequence) + 1)
            : $startNumber;

        return $this->formatDocumentNumberFromSequence(
            $nextSequence,
            $issuedDate,
            $preferredNumberFormatSuffix ?: $globalSettings['number_format_suffix']
        );
    }

    private function formatDocumentNumberFromSequence(
        int $sequence,
        Carbon $issuedDate,
        ?string $preferredNumberFormatSuffix = null
    ): string {
        $format = '{seq}/' . ($preferredNumberFormatSuffix ?: 'SK.02/LPM.DIY/{month_roman}/{year}');

        return strtr($format, [
            '{seq}' => (string) $sequence,
            '{month}' => $issuedDate->format('m'),
            '{month_roman}' => $this->romanMonth((int) $issuedDate->format('n')),
            '{year}' => $issuedDate->format('Y'),
        ]);
    }

    private function extractDocumentNumberSequence(?string $documentNumber): ?int
    {
        if (!is_string($documentNumber)) {
            return null;
        }

        if (preg_match('/^(\d+)\//', $documentNumber, $matches) !== 1) {
            return null;
        }

        return (int) $matches[1];
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
        $issuedDate = $this->parseFlexibleDate($overrides['tanggal_penetapan_raw'] ?? $overrides['tanggal_terbit'] ?? null);
        $formattedName = $this->formatNameWithDegree(
            $importRow?->source_nama,
            $importRow?->source_gelar,
            $employee?->name,
            $employee?->gelar
        );
        $formattedBirthDate = $this->formatIndonesianDate(
            $importRow?->source_tanggal_lahir,
            $employee?->tanggal_lahir
        );
        $formattedTmt = $this->formatIndonesianDate(
            $importRow?->source_tmt_pertama,
            $employee?->tmt
        );
        $employmentType = $this->detectEmploymentType($submission)
            ?? $this->detectEmploymentTypeFromText((string) $importRow?->source_keterangan);
        $generatedPerformanceScore = $this->resolveGeneratedSkPerformanceScore(
            $employee,
            $importRow?->source_penilaian_kinerja,
            $employeeSkData?->penilaian_kinerja,
            $employmentType
        );
        $formattedTenure = $this->formatTenureFromTmt(
            $importRow?->source_tmt_pertama,
            $employee?->tmt,
            $issuedDate,
            $employee?->masa_kerja
        );

        $pick = function (...$values) {
            foreach ($values as $value) {
                $string = $this->sanitizeTemplatePlaceholderValue($value, false);

                if ($string !== '-') {
                    return $string;
                }
            }

            return '-';
        };
        $formattedDegree = $this->formatDegreePlaceholder($pick($importRow?->source_gelar, $employee?->gelar));
        $formattedEducation = $this->normalizeSarjanawiyataTamansiswaEducation(
            $pick($importRow?->source_pendidikan_terakhir, $employee->pendidikan_terakhir)
        );
        $formattedBirthPlace = $this->formatBirthPlacePlaceholder(
            $pick($importRow?->source_tempat_lahir, $employee->tempat_lahir)
        );

        $base = [
            '{{nomor_sk}}' => $overrides['nomor_sk'] ?? '-',
            '{{judul_sk}}' => optional($submission->template)->document_title ?? 'Surat Keputusan Yayasan',
            '{{nama_yayasan}}' => $yayasan?->name ?? 'Yayasan',
            '{{alamat_yayasan}}' => $yayasan?->alamat ?? '-',
            '{{nama_sekolah}}' => $madrasah->name ?? '-',
            '{{nama_pegawai}}' => $formattedName,
            '{{gelar}}' => $formattedDegree,
            '{{tempat_lahir}}' => $formattedBirthPlace,
            '{{tanggal_lahir}}' => $formattedBirthDate,
            '{{nip_maarif}}' => $this->formatNumericIdentityValue($pick($importRow?->source_nip_maarif, $employee->nip)),
            '{{nuptk}}' => $this->formatNumericIdentityValue($pick($importRow?->source_nuptk, $employee->nuptk)),
            '{{nomor_kartanu}}' => $this->formatKartanuValue($pick($importRow?->source_nomor_kartanu, $employee->kartanu)),
            '{{tmt_pertama}}' => $formattedTmt,
            '{{masa_kerja}}' => $formattedTenure,
            '{{pendidikan_terakhir}}' => $formattedEducation,
            '{{tahun_lulus}}' => $pick($importRow?->source_tahun_lulus, $employee->tahun_lulus),
            '{{program_studi}}' => $pick($importRow?->source_program_studi, $employee->program_studi),
            '{{mapel_tugas_yang_diampu}}' => $pick($importRow?->source_mapel_tugas, $employee->mengajar),
            '{{penilaian_kinerja}}' => $generatedPerformanceScore,
            '{{keterangan_sk_yayasan}}' => $pick($importRow?->source_keterangan, $employeeSkData?->keterangan),
            '{{jabatan}}' => $employee->ketugasan ?? '-',
            '{{status_kepegawaian}}' => $employee->statusKepegawaian?->name ?? ($submission->employment_category ?? '-'),
            '{{tanggal_mulai}}' => $overrides['tanggal_mulai'] ?? '01 Juli ' . now()->format('Y'),
            '{{tanggal_selesai}}' => $overrides['tanggal_selesai'] ?? '30 Juni ' . now()->addYear()->format('Y'),
            '{{tanggal_terbit}}' => $overrides['tanggal_terbit'] ?? $this->formatIndonesianDate(now()),
            '{{tahun_sk}}' => $overrides['tahun_sk'] ?? now()->format('Y'),
            '{{tahun_sk_berikutnya}}' => $overrides['tahun_sk_berikutnya'] ?? now()->addYear()->format('Y'),
            '{{tahun_penerbitan_sk}}' => $overrides['tahun_penerbitan_sk'] ?? (now()->format('Y') . '-' . now()->addYear()->format('Y')),
            '{{nomor_sk_yayasan_mulai}}' => $overrides['nomor_sk_yayasan_mulai'] ?? '-',
            '{{nama_penandatangan}}' => $overrides['nama_penandatangan'] ?? 'Ketua Yayasan',
            '{{jabatan_penandatangan}}' => $overrides['jabatan_penandatangan'] ?? 'Ketua Yayasan',
            '{{ditetapkan_di}}' => $overrides['ditetapkan_di'] ?? 'Yogyakarta',
            '{{tanggal_penetapan}}' => $overrides['tanggal_penetapan'] ?? ($overrides['tanggal_terbit'] ?? $this->formatIndonesianDate(now())),
            '{{tembusan_1}}' => $overrides['tembusan_1'] ?? '-',
            '{{tembusan_2}}' => $overrides['tembusan_2'] ?? '-',
            '{{catatan_pengajuan}}' => '-',
            '{{nomor_surat_pengajuan}}' => $pick($submission->submission_letter_number),
            '{{tanggal_surat_pengajuan}}' => $this->formatIndonesianDate($submission->submission_letter_date),
            '{{catatan_penerbitan}}' => $overrides['catatan_penerbitan'] ?? '-',
            '{{excel_no}}' => $pick($importRow?->excel_no),
            '{{source_nama}}' => $formattedName,
            '{{source_gelar}}' => $formattedDegree,
            '{{source_tempat_lahir}}' => $formattedBirthPlace,
            '{{source_tanggal_lahir}}' => $formattedBirthDate,
            '{{source_nip_maarif}}' => $this->formatNumericIdentityValue($pick($importRow?->source_nip_maarif, $employee->nip)),
            '{{source_nuptk}}' => $this->formatNumericIdentityValue($pick($importRow?->source_nuptk, $employee->nuptk)),
            '{{source_nomor_kartanu}}' => $this->formatKartanuValue($pick($importRow?->source_nomor_kartanu, $employee->kartanu)),
            '{{source_tmt_pertama}}' => $formattedTmt,
            '{{source_masa_kerja}}' => $formattedTenure,
            '{{source_pendidikan_terakhir}}' => $formattedEducation,
            '{{source_tahun_lulus}}' => $pick($importRow?->source_tahun_lulus, $employee->tahun_lulus),
            '{{source_program_studi}}' => $pick($importRow?->source_program_studi, $employee->program_studi),
            '{{source_mapel_tugas}}' => $pick($importRow?->source_mapel_tugas, $employee->mengajar),
            '{{source_penilaian_kinerja}}' => $generatedPerformanceScore,
            '{{source_keterangan}}' => $pick($importRow?->source_keterangan, $employeeSkData?->keterangan),
        ];

        foreach ($overrides as $key => $value) {
            $base['{{' . $key . '}}'] = $value;
        }

        return collect($base)
            ->map(fn ($value) => $this->sanitizeTemplatePlaceholderValue($value))
            ->all();
    }

    private function resolveGeneratedSkPerformanceScore(
        ?User $employee,
        mixed $submittedValue,
        mixed $fallbackValue = null,
        ?string $employmentType = null
    ): string {
        if (in_array($employmentType, ['pty', 'ptt'], true)) {
            return $this->formatGeneratedSkPerformanceScore($submittedValue ?? $fallbackValue);
        }

        if ($this->employeeParticipatesInMgmpResetUploads($employee)) {
            return $this->formatGeneratedSkPerformanceScore($submittedValue ?? $fallbackValue);
        }

        return $this->formatGeneratedSkPerformanceScore(60);
    }

    private function formatGeneratedSkPerformanceScore(mixed $value): string
    {
        $sanitized = $this->sanitizeTemplatePlaceholderValue($value, false);

        if ($sanitized === '-') {
            return '-';
        }

        $normalized = $sanitized;
        $normalized = preg_replace('/\s+/u', '', $normalized) ?? $normalized;

        if (str_contains($normalized, ',') && str_contains($normalized, '.')) {
            $normalized = str_replace('.', '', $normalized);
            $normalized = str_replace(',', '.', $normalized);
        } elseif (str_contains($normalized, ',')) {
            $normalized = str_replace(',', '.', $normalized);
        }

        if (!is_numeric($normalized)) {
            return $sanitized;
        }

        return number_format((float) $normalized, 2, ',', '');
    }

    private function formatNumericIdentityValue(mixed $value): string
    {
        $sanitized = $this->sanitizeTemplatePlaceholderValue($value, false);

        if ($sanitized === '-') {
            return '-';
        }

        $digitsOnly = preg_replace('/\D+/u', '', $sanitized) ?? '';

        if ($digitsOnly === '' || preg_match('/^0+$/', $digitsOnly) === 1) {
            return '-';
        }

        return $digitsOnly;
    }

    private function formatKartanuValue(mixed $value): string
    {
        $sanitized = $this->sanitizeTemplatePlaceholderValue($value, false);

        if ($sanitized === '-') {
            return '-';
        }

        $digitsOnly = preg_replace('/\D+/u', '', $sanitized) ?? '';
        $lettersOnly = preg_replace('/[^A-Za-z]+/u', '', $sanitized) ?? '';

        if ($lettersOnly === '' && $digitsOnly !== '' && preg_match('/^0+$/', $digitsOnly) === 1) {
            return '-';
        }

        return $sanitized;
    }

    private function employeeParticipatesInMgmpResetUploads(?User $employee): bool
    {
        static $cache = [];

        $employeeId = (int) ($employee?->id ?? 0);

        if ($employeeId <= 0) {
            return false;
        }

        if (array_key_exists($employeeId, $cache)) {
            return $cache[$employeeId];
        }

        $ownerIds = MgmpMember::query()
            ->join('mgmp_groups', 'mgmp_groups.id', '=', 'mgmp_members.mgmp_group_id')
            ->where('mgmp_members.user_id', $employeeId)
            ->whereNotNull('mgmp_groups.user_id')
            ->pluck('mgmp_groups.user_id')
            ->filter()
            ->unique()
            ->values();

        if ($ownerIds->isEmpty()) {
            return $cache[$employeeId] = false;
        }

        return $cache[$employeeId] = AcademicaResetUpdate::query()
            ->whereHas('proposal', fn (Builder $query) => $query->whereIn('user_id', $ownerIds->all()))
            ->exists();
    }

    private function sanitizeTemplatePlaceholderValue(mixed $value, bool $preserveLineBreaks = true): string
    {
        if ($value === null) {
            return '-';
        }

        $string = $preserveLineBreaks
            ? trim((string) $value)
            : trim(preg_replace('/\s+/u', ' ', (string) $value) ?? (string) $value);

        if ($string === '') {
            return '-';
        }

        $string = $this->normalizeMissingValueMarkers($string);
        $normalizedLower = Str::lower($string);

        if (in_array($normalizedLower, ['-', '?', 'null', '(null)', 'n/a', 'na'], true)) {
            return '-';
        }

        $string = str_replace(['&#63;', '?'], '-', $string);
        $string = preg_replace('/(?:^|,\s*)-(?:\s*,\s*-)+$/u', '-', $string) ?? $string;
        $string = preg_replace('/^-\s*,\s*(.+)$/u', '$1', $string) ?? $string;
        $string = preg_replace('/^(.+?)\s*,\s*-$/u', '$1', $string) ?? $string;
        $string = trim($string);
        $meaningful = preg_replace('/[^\pL\pN]+/u', '', $string) ?? $string;

        if ($meaningful === '') {
            return '-';
        }

        return $string === '' ? '-' : $string;
    }

    private function normalizeSarjanawiyataTamansiswaEducation(mixed $value): string
    {
        $string = $this->sanitizeTemplatePlaceholderValue($value, false);

        if ($string === '-') {
            return $string;
        }

        if (! preg_match('/\b(?:universitas|univ\.?)\s+sarjanawiyata\s+tamansiswa\b/iu', $string)) {
            return $string;
        }

        $normalized = preg_replace(
            '/\b(?:universitas|univ\.?)\s+sarjanawiyata\s+tamansiswa\b.*$/iu',
            'Univ. Sarjanawiyata Tamansiswa',
            $string
        ) ?? $string;

        return trim($normalized, " \t\n\r\0\x0B,;");
    }

    private function normalizeMissingValueMarkers(string $value): string
    {
        $normalized = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2015}\x{2212}\x{FE58}\x{FE63}\x{FF0D}]/u', '-', $normalized) ?? $normalized;
        $normalized = preg_replace('/[?？﹖¿\x{061F}]/u', '-', $normalized) ?? $normalized;
        $normalized = preg_replace('/\b(?:null|\(null\)|n\/a|na|none|undefined)\b/ui', '-', $normalized) ?? $normalized;

        return trim($normalized);
    }

    private function formatNameWithDegree(
        mixed $primaryName,
        mixed $primaryDegree = null,
        mixed $fallbackName = null,
        mixed $fallbackDegree = null
    ): string {
        $name = $this->normalizePersonName($primaryName ?? $fallbackName);
        $degree = $this->normalizeDegree($primaryDegree ?? $fallbackDegree);

        if ($name === '-') {
            return '-';
        }

        if ($degree === null || $degree === '') {
            return $name;
        }

        $degreeWithoutTrailingPeriod = rtrim($degree, '.');
        $nameWithoutDegree = preg_replace('/,\s*' . preg_quote($degreeWithoutTrailingPeriod, '/') . '\.?$/iu', '', $name) ?: $name;

        return trim($nameWithoutDegree) . ', ' . $degree;
    }

    private function normalizePersonName(mixed $value): string
    {
        $string = trim((string) $value);

        if ($string === '' || $string === '-' || $string === '?') {
            return '-';
        }

        return mb_convert_case(mb_strtolower($string), MB_CASE_TITLE, 'UTF-8');
    }

    private function normalizeDegree(mixed $value): ?string
    {
        $string = trim((string) $value);

        if ($string === '' || $string === '-' || $string === '?') {
            return null;
        }

        return str_ends_with($string, '.') ? $string : $string . '.';
    }

    private function formatDegreePlaceholder(string $value): string
    {
        $degree = $this->normalizeDegree($value);

        return $degree ?? '-';
    }

    private function formatBirthPlacePlaceholder(mixed $value): string
    {
        $string = $this->sanitizeTemplatePlaceholderValue($value, false);

        if ($string === '-') {
            return '-';
        }

        return mb_convert_case(mb_strtolower($string), MB_CASE_TITLE, 'UTF-8');
    }

    private function formatIndonesianDate(mixed ...$values): string
    {
        foreach ($values as $value) {
            $date = $this->parseFlexibleDate($value);

            if ($date !== null) {
                return $date->locale('id')->translatedFormat('d F Y');
            }
        }

        return '-';
    }

    private function parseFlexibleDate(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value->copy();
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        $string = trim((string) $value);

        if ($string === '' || $string === '-') {
            return null;
        }

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'j/n/Y', 'j-n-Y', 'd/m/y', 'd-m-y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $string)->startOfDay();
            } catch (\Throwable) {
            }
        }

        try {
            return Carbon::parse($string)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private function formatTenureFromTmt(
        mixed $primaryTmt,
        mixed $fallbackTmt,
        ?Carbon $issuedDate = null,
        mixed $fallbackTenure = null
    ): string {
        $tmtDate = $this->parseFlexibleDate($primaryTmt) ?? $this->parseFlexibleDate($fallbackTmt);

        if (!$tmtDate || !$issuedDate) {
            $fallback = trim((string) $fallbackTenure);
            return $fallback !== '' ? $fallback : '-';
        }

        if ($tmtDate->greaterThan($issuedDate)) {
            return '0 tahun 0 bulan';
        }

        $diff = $tmtDate->diff($issuedDate);

        return sprintf('%d tahun %d bulan', $diff->y, $diff->m);
    }

    private function renderTemplate(string $body, array $placeholders, array $templateContext = []): string
    {
        $body = $this->normalizeStructuredTemplatePlaceholders($body);
        $body = $this->normalizeStructuredTemplateStyles($body, $templateContext);
        $body = $this->normalizeStructuredTemplateOrgTitleLayout($body);
        $body = $this->normalizeStructuredTemplateContactEmailLayout($body);
        $body = $this->normalizeStructuredTemplateMengingatLayout($body);
        $body = $this->normalizeStructuredTemplateDecisionContentLayout($body);
        $body = $this->normalizeStructuredTemplateFooterLayout($body);
        $body = $this->normalizeStructuredTemplateSignatureSpacing($body);
        $normalizedPlaceholders = $placeholders;

        foreach ($placeholders as $key => $value) {
            if (str_starts_with($key, '{{') && str_ends_with($key, '}}')) {
                $normalizedPlaceholders['@' . $key] = $value;
            }
        }

        return $this->normalizeRenderedQuestionMarks(
            $this->normalizePersonRows(
                strtr($body, $normalizedPlaceholders)
            )
        );
    }

    private function normalizeStructuredTemplatePlaceholders(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"')) {
            return $body;
        }

        $body = preg_replace(
            '/(<div class="sk-number"[^>]*>)(.*?)(<\/div>)/su',
            '$1Nomor: @{{nomor_sk}}$3',
            $body,
            1
        ) ?? $body;

        return $body;
    }

    private function normalizeStructuredTemplateStyles(string $body, array $templateContext = []): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"')) {
            return $body;
        }

        $body = preg_replace(
            '/@page\s*\{\s*margin\s*:\s*[^;]+;\s*\}/u',
            '@page { margin: 6mm 12mm 5mm 12mm; }',
            $body
        ) ?? $body;

        $copyCellPadding = $this->templateNeedsWideCopyGap($templateContext)
            ? '54px 14px 0 0'
            : '0 14px 0 0';

        $body = preg_replace(
            '/\.sk-label\s*\{\s*width\s*:\s*\d+px\s*;\s*\}/u',
            '.sk-label { width: 112px; }',
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-table\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/width\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-table { ' . trim($styles . ' width: 100%;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-table\s+td\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-table td { ' . trim($styles . ' padding: 0 4px 1px 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-content-cell\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/padding-left\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $hasLineHeight = preg_match('/line-height\s*:/u', $styles) === 1;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                $lineHeight = $hasLineHeight ? '' : ' line-height: 1;';

                return '.sk-content-cell { ' . trim($styles . $lineHeight . ' padding-left: 3px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-person-table\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/margin\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/border-spacing\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-person-table { ' . trim($styles . ' border-spacing: 0; margin: 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-person-table\s+tr\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/margin\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-person-table tr { ' . trim($styles . ' line-height: 0.72; margin: 0; padding: 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-person-table\s+td\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-person-table td { ' . trim($styles . ' line-height: 0.72; padding: 0 1px 0 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-colon\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/width\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-colon { ' . trim($styles . ' width: 14px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-letterhead\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/margin\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-letterhead { ' . trim($styles . ' margin: 0 auto 0 auto;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-logo-cell\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/width\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-logo-cell { ' . trim($styles . ' padding: 0 24px 1px 44px; width: 112px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-logo-box\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/margin-left\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/margin-top\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/width\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/justify-content\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-logo-box { ' . trim($styles . ' height: auto; margin-left: 6px; margin-top: 2mm; width: 98px; justify-content: flex-start;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-logo-box\s+img(?:\s*,\s*\.sk-logo-image)?\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/max-width\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/margin-top\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-logo-box img, .sk-logo-image { ' . trim($styles . ' height: 114px !important; margin-top: 0 !important; max-width: 184px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-signature\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/margin-top\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/width\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-signature { ' . trim($styles . ' line-height: 1.02; margin-top: 0; width: 260px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-org-title\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/margin(?:-[a-z]+)?\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-org-title { ' . trim($styles . ' line-height: 1; margin: 0 0 0.3mm 0; padding: 0 8px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-org-subtitle\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/padding\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-org-subtitle { ' . trim($styles . ' line-height: 0.86; padding: 0 8px;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-org-meta\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-org-meta { ' . trim($styles . ' line-height: 1;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-green-line\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/margin(?:-[a-z]+)?\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-green-line { ' . trim($styles . ' margin: 1mm 0 1.5mm 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-number\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/margin(?:-[a-z]+)?\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-number { ' . trim($styles . ' line-height: 1; margin: -0.6mm 0 1.5mm 0;') . ' }';
            },
            $body
        ) ?? $body;

        $body = preg_replace_callback(
            '/\.sk-decision\s*\{([^}]*)\}/u',
            static function (array $matches): string {
                $styles = preg_replace('/margin(?:-[a-z]+)?\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-decision { ' . trim($styles . ' margin: 2px 0 0 0;') . ' }';
            },
            $body
        ) ?? $body;

        $replacements = [
            '/\.sk-copy\s*\{([^}]*)\}/u' => static function (array $matches): string {
                $styles = preg_replace('/line-height\s*:\s*[^;]+;?/u', '', $matches[1]) ?? $matches[1];
                $styles = preg_replace('/margin(?:-[a-z]+)?\s*:\s*[^;]+;?/u', '', $styles) ?? $styles;
                $styles = trim($styles);

                if ($styles !== '' && !str_ends_with($styles, ';')) {
                    $styles .= ';';
                }

                return '.sk-copy { ' . trim($styles . ' line-height: 0.9; margin: 0;') . ' }';
            },
        ];

        foreach ($replacements as $pattern => $callback) {
            $body = preg_replace_callback($pattern, $callback, $body) ?? $body;
        }

        $body = preg_replace(
            '/\.sk-footer-copy-cell\s*\{[^}]*\}/u',
            '.sk-footer-copy-cell { padding: ' . $copyCellPadding . '; }',
            $body
        ) ?? $body;

        $body = preg_replace(
            '/\.sk-footer-signature-cell\s*\{[^}]*\}/u',
            '.sk-footer-signature-cell { vertical-align: top; width: 260px; }',
            $body
        ) ?? $body;

        foreach ([
            '.sk-footer-table { border-collapse: collapse; margin-top: 20px; width: 100%; }',
            '.sk-footer-table td { vertical-align: bottom; }',
            '.sk-footer-copy-cell { padding: ' . $copyCellPadding . '; }',
            '.sk-footer-signature-cell { vertical-align: top; width: 260px; }',
            '.sk-org-title-line { display: block; line-height: 0.2; margin: 0; }',
            '.sk-org-title-line + .sk-org-title-line { margin-top: 0; }',
            '.sk-org-title-last { margin-top: 0; }',
            '.sk-org-title + .sk-org-subtitle { margin-top: 0.3mm; }',
            '.sk-org-subtitle:empty { display: none; }',
            '.sk-org-subtitle + .sk-org-meta { margin-top: 3mm; }',
            '.sk-org-meta { padding: 0 8px; }',
            '.sk-kesatu-intro { display: block; padding-bottom: 3mm; }',
            '.sk-kesatu-closing { display: block; padding-top: 1.2mm; }',
            '.sk-person-table tr { line-height: 0.72; margin: 0; padding: 0; }',
            '.sk-mengingat-list { margin: 0; padding-left: 22px; }',
            '.sk-mengingat-list li { margin: 0; padding-left: 0; }',
            '.sk-reference-row td { padding-bottom: 0; vertical-align: baseline; }',
            '.sk-reference-row .sk-label, .sk-decision-row .sk-label { width: 98px; }',
            '.sk-reference-row .sk-colon, .sk-decision-row .sk-colon { text-align: left; width: 6px; }',
            '.sk-reference-row .sk-content-cell > *, .sk-decision-row .sk-content-cell > * { margin-top: 0; margin-bottom: 0; }',
            '.sk-reference-row .sk-content-cell, .sk-reference-row .sk-menimbang-content, .sk-reference-row .sk-menimbang-item { line-height: 0.9; padding-top: 0.8mm; text-align: justify; text-align-last: auto; text-justify: inter-word; }',
            '.sk-decision-row .sk-content-cell, .sk-decision-row .sk-kedua-content, .sk-decision-row .sk-ketiga-content { line-height: 0.9; padding-top: 0.8mm; text-align: justify; text-align-last: auto; text-justify: inter-word; }',
            '.sk-decision-row td { padding-bottom: 1mm; }',
            '.sk-menimbang-row td { padding-bottom: 0; }',
            '.sk-menimbang-content { line-height: 1; text-align: justify; text-align-last: justify; text-justify: inter-word; white-space: pre-wrap; }',
            '.sk-menimbang-item { display: block; line-height: 1; margin: 0; text-align: justify; text-align-last: justify; text-justify: inter-word; white-space: pre-wrap; }',
            '.sk-kedua-content { line-height: 1; }',
            '.sk-ketiga-content { line-height: 1; }',
            '.sk-person-value { padding-left: 8px; }',
            '.sk-signature-role { display: block; padding-top: 14px; }',
            '.sk-email-link { color: #1d4ed8; }',
            '.sk-green-line ~ * { font-family: Cambria !important; }',
            '.sk-title, .sk-number, .sk-subject, .sk-table, .sk-table td, .sk-person-table, .sk-person-table td, .sk-decision, .sk-signature, .sk-signature *, .sk-copy, .sk-copy * { font-family: Cambria !important; }',
            '.sk-logo-box img, .sk-logo-image { display: block; height: 114px !important; margin-top: 0 !important; max-width: 184px; object-fit: contain; }',
        ] as $requiredStyle) {
            if (!str_contains($body, $requiredStyle)) {
                $body = str_replace('</style>', $requiredStyle . "\n</style>", $body);
            }
        }

        return $body;
    }

    private function normalizeStructuredTemplateContactEmailLayout(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"')) {
            return $body;
        }

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-root">' . $body . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        $xpath = new \DOMXPath($document);
        $metaBlocks = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " sk-org-meta ")]');

        if ($metaBlocks === false) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        foreach ($metaBlocks as $metaBlock) {
            if (!$metaBlock instanceof \DOMElement) {
                continue;
            }

            $innerHtml = '';

            foreach ($metaBlock->childNodes as $childNode) {
                $innerHtml .= $document->saveHTML($childNode);
            }

            if ($innerHtml === '' || str_contains($innerHtml, 'sk-email-link')) {
                continue;
            }

            $updatedHtml = preg_replace(
                '/(https?:\/\/[^\s<]+)/iu',
                '<span class="sk-email-link">$1</span>',
                $innerHtml
            ) ?? $innerHtml;

            $updatedHtml = preg_replace(
                '/([A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,})/iu',
                '<span class="sk-email-link">$1</span>',
                $updatedHtml
            ) ?? $updatedHtml;

            if ($updatedHtml === $innerHtml) {
                continue;
            }

            while ($metaBlock->firstChild) {
                $metaBlock->removeChild($metaBlock->firstChild);
            }

            $fragmentDocument = new \DOMDocument('1.0', 'UTF-8');
            $fragmentWrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-meta-fragment">' . $updatedHtml . '</div>';

            if ($fragmentDocument->loadHTML($fragmentWrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
                $fragmentRoot = $fragmentDocument->getElementById('sk-meta-fragment');

                if ($fragmentRoot) {
                    foreach (iterator_to_array($fragmentRoot->childNodes) as $fragmentChild) {
                        $metaBlock->appendChild($document->importNode($fragmentChild, true));
                    }
                }
            }
        }

        $root = $document->getElementById('sk-root');
        $output = '';

        if ($root) {
            foreach ($root->childNodes as $childNode) {
                $output .= $document->saveHTML($childNode);
            }
        } else {
            $output = $body;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $output;
    }

    private function normalizeStructuredTemplateOrgTitleLayout(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"') || str_contains($body, 'sk-org-title-line')) {
            return $body;
        }

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-root">' . $body . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        $xpath = new \DOMXPath($document);
        $titleBlocks = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " sk-org-title ")]');

        if ($titleBlocks === false) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        foreach ($titleBlocks as $titleBlock) {
            if (!$titleBlock instanceof \DOMElement) {
                continue;
            }

            $segments = [];
            $currentSegment = [];

            foreach (iterator_to_array($titleBlock->childNodes) as $childNode) {
                if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'br') {
                    $segments[] = $currentSegment;
                    $currentSegment = [];
                    continue;
                }

                $currentSegment[] = $childNode->cloneNode(true);
            }

            if (!empty($currentSegment)) {
                $segments[] = $currentSegment;
            }

            if (count($segments) < 2) {
                continue;
            }

            $lastSegment = array_pop($segments);

            while ($titleBlock->firstChild) {
                $titleBlock->removeChild($titleBlock->firstChild);
            }

            foreach ($segments as $segmentNodes) {
                $line = $document->createElement('span');
                $line->setAttribute('class', 'sk-org-title-line');

                foreach ($segmentNodes as $segmentNode) {
                    $line->appendChild($document->importNode($segmentNode, true));
                }

                $titleBlock->appendChild($line);
            }

            $lastLine = $document->createElement('span');
            $lastLine->setAttribute('class', 'sk-org-title-line sk-org-title-last');

            foreach ($lastSegment as $segmentNode) {
                $lastLine->appendChild($document->importNode($segmentNode, true));
            }

            $titleBlock->appendChild($lastLine);
        }

        $root = $document->getElementById('sk-root');
        $output = '';

        if ($root) {
            foreach ($root->childNodes as $childNode) {
                $output .= $document->saveHTML($childNode);
            }
        } else {
            $output = $body;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $output;
    }

    private function normalizeStructuredTemplateMengingatLayout(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"') || $this->bodyContainsClassMarkup($body, 'sk-mengingat-list')) {
            return $body;
        }

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-root">' . $body . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        $xpath = new \DOMXPath($document);
        $rows = $xpath->query('//tr[td[contains(concat(" ", normalize-space(@class), " "), " sk-label ")]]');

        if ($rows === false) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        foreach ($rows as $row) {
            $cells = [];

            foreach ($row->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'td') {
                    $cells[] = $childNode;
                }
            }

            if (count($cells) < 3) {
                continue;
            }

            $labelText = trim(preg_replace('/\s+/u', ' ', $cells[0]->textContent ?? ''));

            if ($labelText !== 'Mengingat') {
                continue;
            }

            $contentCell = $cells[count($cells) - 1];
            $groupedItems = [];
            $itemElements = [];
            $tableRows = [];

            foreach (iterator_to_array($contentCell->childNodes) as $childNode) {
                if (
                    $childNode instanceof \DOMElement
                    && strtolower($childNode->tagName) === 'div'
                    && str_contains(' ' . $childNode->getAttribute('class') . ' ', ' sk-mengingat-item ')
                ) {
                    $itemElements[] = $childNode;
                }

                if (
                    $childNode instanceof \DOMElement
                    && strtolower($childNode->tagName) === 'table'
                    && str_contains(' ' . $childNode->getAttribute('class') . ' ', ' sk-mengingat-table ')
                ) {
                    foreach ($childNode->childNodes as $tableChild) {
                        if ($tableChild instanceof \DOMElement && strtolower($tableChild->tagName) === 'tr') {
                            $tableRows[] = $tableChild;
                        }
                    }
                }
            }

            if (!empty($tableRows)) {
                foreach ($tableRows as $tableRow) {
                    $cellsInRow = [];

                    foreach ($tableRow->childNodes as $tableCell) {
                        if ($tableCell instanceof \DOMElement && strtolower($tableCell->tagName) === 'td') {
                            $cellsInRow[] = $tableCell;
                        }
                    }

                    if (count($cellsInRow) < 2) {
                        continue;
                    }

                    $groupedItems[] = trim($cellsInRow[count($cellsInRow) - 1]->textContent ?? '');
                }
            } elseif (!empty($itemElements)) {
                foreach ($itemElements as $itemElement) {
                    $groupedItems[] = trim($itemElement->textContent ?? '');
                }
            } else {
                $groupedNodes = [];
                $currentGroup = [];

                foreach (iterator_to_array($contentCell->childNodes) as $childNode) {
                    if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'br') {
                        $groupedNodes[] = $currentGroup;
                        $currentGroup = [];
                        continue;
                    }

                    $currentGroup[] = $childNode->cloneNode(true);
                }

                if (!empty($currentGroup)) {
                    $groupedNodes[] = $currentGroup;
                }

                foreach ($groupedNodes as $group) {
                    $groupText = '';

                    foreach ($group as $node) {
                        $groupText .= trim($document->saveHTML($node));
                    }

                    if (trim(strip_tags($groupText)) !== '') {
                        $groupedItems[] = $groupText;
                    }
                }
            }

            while ($contentCell->firstChild) {
                $contentCell->removeChild($contentCell->firstChild);
            }

            $mengingatList = $document->createElement('ol');
            $mengingatList->setAttribute('class', 'sk-mengingat-list');

            foreach ($groupedItems as $groupText) {
                $plainText = trim(preg_replace('/\s+/u', ' ', strip_tags($groupText)));

                if ($plainText === '') {
                    continue;
                }

                $contentText = preg_replace('/^\s*\d+[\.\)]\s*/u', '', $plainText) ?: $plainText;
                $contentText = preg_replace('/^[\s\-\?•:]+/u', '', $contentText) ?: $contentText;

                $listItem = $document->createElement('li');
                $listItem->appendChild($document->createTextNode($contentText));
                $mengingatList->appendChild($listItem);
            }

            $contentCell->appendChild($mengingatList);
        }

        $root = $document->getElementById('sk-root');
        $output = '';

        if ($root) {
            foreach ($root->childNodes as $childNode) {
                $output .= $document->saveHTML($childNode);
            }
        } else {
            $output = $body;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $output;
    }

    private function normalizeStructuredTemplateFooterLayout(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"') || $this->bodyContainsClassMarkup($body, 'sk-footer-table')) {
            return $body;
        }

        $oldFooter = <<<'HTML'
    <div class="sk-signature">
        Ditetapkan di&nbsp;&nbsp;: Yogyakarta<br>
        Pada Tanggal&nbsp;&nbsp;: @{{tanggal_terbit}}<br>
        @{{jabatan_penandatangan}}<br>
        Ketua,
        <div class="sk-signature-name">@{{nama_penandatangan}}</div>
    </div>

    <div class="sk-copy">
        <div class="sk-copy-title">Tembusan Yth:</div>
        1. Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY<br>
        2. Kepala Balai Pendidikan Menengah Kabupaten Bantul<br>
        3. Arsip
    </div>
HTML;

        $newFooter = <<<'HTML'
    <table class="sk-footer-table">
        <tr>
            <td class="sk-footer-copy-cell">
                <div class="sk-copy">
                    <div class="sk-copy-title">Tembusan Yth:</div>
                    1. Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY<br>
                    2. Kepala Balai Pendidikan Menengah Kabupaten Bantul<br>
                    3. Arsip
                </div>
            </td>
            <td class="sk-footer-signature-cell">
                <div class="sk-signature">
                    Ditetapkan di&nbsp;&nbsp;: Yogyakarta<br>
                    Pada Tanggal&nbsp;&nbsp;: @{{tanggal_terbit}}
                    <div class="sk-signature-role">@{{jabatan_penandatangan}}<br>Ketua,</div>
                    <div class="sk-signature-name">@{{nama_penandatangan}}</div>
                </div>
            </td>
        </tr>
    </table>
HTML;

        return str_replace($oldFooter, $newFooter, $body);
    }

    private function normalizeStructuredTemplateDecisionContentLayout(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"')) {
            return $body;
        }

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<?xml encoding="utf-8" ?><div id="sk-root">' . $body . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        $xpath = new \DOMXPath($document);
        $rows = $xpath->query('//tr[td[contains(concat(" ", normalize-space(@class), " "), " sk-label ")]]');

        if ($rows === false) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);

            return $body;
        }

        $lastNonEmptyLabel = null;
        $menimbangContentCell = null;
        $rowsToRemove = [];

        foreach ($rows as $row) {
            $cells = [];

            foreach ($row->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement && strtolower($childNode->tagName) === 'td') {
                    $cells[] = $childNode;
                }
            }

            if (count($cells) < 3) {
                continue;
            }

            $labelText = trim(preg_replace('/\s+/u', ' ', $cells[0]->textContent ?? ''));
            $contentCell = $cells[count($cells) - 1];
            $rowClassNames = preg_split('/\s+/u', trim((string) $row->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $existingClass = trim((string) $contentCell->getAttribute('class'));
            $classNames = preg_split('/\s+/u', $existingClass, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            if ($labelText === 'Menimbang') {
                $rowClassNames[] = 'sk-menimbang-row';
                $rowClassNames[] = 'sk-reference-row';
                $classNames[] = 'sk-menimbang-content';
                $menimbangContentCell = $contentCell;
            }

            if (in_array($labelText, ['Mengingat', 'Memperhatikan'], true)) {
                $rowClassNames[] = 'sk-reference-row';
            }

            if (in_array($labelText, ['Menetapkan', 'Kesatu', 'Kedua', 'Ketiga'], true)) {
                $rowClassNames[] = 'sk-decision-row';
            }

            if ($labelText === '' && $lastNonEmptyLabel === 'Menimbang' && $menimbangContentCell instanceof \DOMElement) {
                $menimbangContentCell->appendChild($document->createElement('br'));

                while ($contentCell->firstChild) {
                    $menimbangContentCell->appendChild($contentCell->firstChild);
                }

                $rowsToRemove[] = $row;

                continue;
            }

            if ($labelText === 'Kedua' && !in_array('sk-kedua-content', $classNames, true)) {
                $classNames[] = 'sk-kedua-content';
            }

            if ($labelText === 'Ketiga' && !in_array('sk-ketiga-content', $classNames, true)) {
                $classNames[] = 'sk-ketiga-content';
            }

            if ($labelText === 'Kesatu' && !$this->bodyContainsClassMarkup($body, 'sk-kesatu-intro')) {
                $contentNodes = iterator_to_array($contentCell->childNodes);
                $personTable = null;

                foreach ($contentNodes as $contentNode) {
                    if (
                        $contentNode instanceof \DOMElement
                        && strtolower($contentNode->tagName) === 'table'
                        && str_contains(' ' . $contentNode->getAttribute('class') . ' ', ' sk-person-table ')
                    ) {
                        $personTable = $contentNode;
                        break;
                    }
                }

                if ($personTable instanceof \DOMElement) {
                    $introNodes = [];
                    $closingNodes = [];
                    $passedPersonTable = false;

                    foreach ($contentNodes as $contentNode) {
                        if ($contentNode === $personTable) {
                            $passedPersonTable = true;
                            continue;
                        }

                        if (!$passedPersonTable) {
                            $introNodes[] = $contentNode;
                            continue;
                        }

                        $closingNodes[] = $contentNode;
                    }

                    $introText = trim(preg_replace('/\s+/u', ' ', implode('', array_map(
                        static fn ($node): string => $node instanceof \DOMNode ? $node->textContent : '',
                        $introNodes
                    ))));

                    if ($introText !== '') {
                        $introWrapper = $document->createElement('div');
                        $introWrapper->setAttribute('class', 'sk-kesatu-intro');

                        foreach ($introNodes as $introNode) {
                            $introWrapper->appendChild($introNode->cloneNode(true));
                        }

                        foreach ($introNodes as $introNode) {
                            if ($introNode->parentNode === $contentCell) {
                                $contentCell->removeChild($introNode);
                            }
                        }

                        $contentCell->insertBefore($introWrapper, $personTable);
                    }

                    $closingText = trim(preg_replace('/\s+/u', ' ', implode('', array_map(
                        static fn ($node): string => $node instanceof \DOMNode ? $node->textContent : '',
                        $closingNodes
                    ))));

                    if ($closingText !== '') {
                        $closingWrapper = $document->createElement('div');
                        $closingWrapper->setAttribute('class', 'sk-kesatu-closing');

                        foreach ($closingNodes as $closingNode) {
                            $closingWrapper->appendChild($closingNode->cloneNode(true));
                        }

                        foreach ($closingNodes as $closingNode) {
                            if ($closingNode->parentNode === $contentCell) {
                                $contentCell->removeChild($closingNode);
                            }
                        }

                        $contentCell->appendChild($closingWrapper);
                    }
                }
            }

            if (!empty($classNames)) {
                $contentCell->setAttribute('class', implode(' ', array_values(array_unique($classNames))));
            }

            if (!empty($rowClassNames)) {
                $row->setAttribute('class', implode(' ', array_values(array_unique($rowClassNames))));
            }

            if ($labelText !== '') {
                $lastNonEmptyLabel = $labelText;
            }
        }

        foreach ($rowsToRemove as $rowToRemove) {
            if ($rowToRemove instanceof \DOMNode && $rowToRemove->parentNode) {
                $rowToRemove->parentNode->removeChild($rowToRemove);
            }
        }

        $root = $document->getElementById('sk-root');
        $output = '';

        if ($root) {
            foreach ($root->childNodes as $childNode) {
                $output .= $document->saveHTML($childNode);
            }
        } else {
            $output = $body;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $output;
    }

    private function templateNeedsWideCopyGap(array $templateContext = []): bool
    {
        $haystack = $this->normalizeTemplateText(implode(' ', array_filter([
            $templateContext['name'] ?? null,
            $templateContext['category'] ?? null,
            $templateContext['document_title'] ?? null,
        ])));

        return $this->containsTemplateWord($haystack, 'gtt')
            || $this->containsTemplateWord($haystack, 'ptt');
    }

    private function normalizeStructuredTemplateSignatureSpacing(string $body): string
    {
        if (!str_contains($body, 'data-sk-full-document="1"')) {
            return $body;
        }

        $body = str_replace('@{{tanggal_terbit}}<br><br>', '@{{tanggal_terbit}}<br>', $body);

        $body = preg_replace(
            '/@\\{\\{tanggal_terbit\\}\\}<br>\s*@\\{\\{jabatan_penandatangan\\}\\}<br>\s*Ketua,/u',
            '@{{tanggal_terbit}}<div class="sk-signature-role">@{{jabatan_penandatangan}}<br>Ketua,</div>',
            $body
        ) ?? $body;

        $body = preg_replace(
            '/\.sk-signature-role\s*\{[^}]*\}/u',
            '.sk-signature-role { display: block; padding-top: 14px; }',
            $body
        ) ?? $body;

        return $body;
    }

    private function bodyContainsClassMarkup(string $body, string $className): bool
    {
        return preg_match('/class\s*=\s*["\'][^"\']*\b' . preg_quote($className, '/') . '\b[^"\']*["\']/u', $body) === 1;
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

                $normalizedValueText = $this->normalizePersonRowValueText($valueText);

                if ($normalizedValueText === '' || $normalizedValueText === '@' || str_contains($normalizedValueText, '@{{')) {
                    $normalizedValueText = '-';
                }

                $existingClass = trim((string) $cells[3]->getAttribute('class'));
                $classNames = preg_split('/\s+/u', $existingClass, -1, PREG_SPLIT_NO_EMPTY) ?: [];

                if (!in_array('sk-person-value', $classNames, true)) {
                    $classNames[] = 'sk-person-value';
                }

                $cells[3]->setAttribute('class', implode(' ', $classNames));
                $cells[3]->nodeValue = $normalizedValueText;

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

    private function normalizePersonRowValueText(string $valueText): string
    {
        $rawNormalized = trim($this->normalizeMissingValueMarkers($valueText));
        $compactNumericValue = preg_replace('/\s+/u', '', $rawNormalized) ?? $rawNormalized;

        if (preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d+)?$/u', $compactNumericValue) === 1 || preg_match('/^\d+(?:,\d+)?$/u', $compactNumericValue) === 1) {
            return preg_replace('/\s*,\s*/u', ',', $compactNumericValue) ?? $compactNumericValue;
        }

        $normalized = preg_replace('/\s*,\s*/u', ', ', $rawNormalized) ?? trim($valueText);
        $normalized = preg_replace('/^-\s*,\s*(.+)$/u', '$1', $normalized) ?? $normalized;
        $normalized = preg_replace('/^(.+?)\s*,\s*-$/u', '$1', $normalized) ?? $normalized;
        $normalized = preg_replace('/(?:^|,\s*)-(?:\s*,\s*-)+$/u', '-', $normalized) ?? $normalized;
        $normalized = preg_replace('/^-\s*,\s*$/u', '-', $normalized) ?? $normalized;
        $normalized = preg_replace('/^-\s*,\s*-\s*$/u', '-', $normalized) ?? $normalized;
        $meaningful = preg_replace('/[^\pL\pN]+/u', '', $normalized) ?? $normalized;

        if ($meaningful === '') {
            return '-';
        }

        return $normalized;
    }

    private function normalizeRenderedQuestionMarks(string $html): string
    {
        return $this->normalizeMissingValueMarkers($html);
    }

    private function templatePreviewPlaceholders(string $documentTitle, Carbon $issuedDate): array
    {
        $globalSettings = $this->getGlobalSkSettings();
        $documentNumber = strtr(
            max(1, (int) ($globalSettings['number_start'] ?? 1)) . '/' . ($globalSettings['number_format_suffix'] ?: 'SK.02/LPM.DIY/{month_roman}/{year}'),
            [
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
            '{{nip_maarif}}' => '2026001',
            '{{nuptk}}' => '1234567890123456',
            '{{nomor_kartanu}}' => 'NU.34.02.001',
            '{{tmt_pertama}}' => '01 Juli 2020',
            '{{masa_kerja}}' => '6 tahun',
            '{{pendidikan_terakhir}}' => 'S1',
            '{{tahun_lulus}}' => '2015',
            '{{program_studi}}' => 'Pendidikan Teknik Informatika',
            '{{mapel_tugas_yang_diampu}}' => 'XXX',
            '{{penilaian_kinerja}}' => '60,00',
            '{{keterangan_sk_yayasan}}' => 'Perpanjangan SK',
            '{{jabatan}}' => 'Guru',
            '{{status_kepegawaian}}' => 'Guru Tetap Yayasan',
            '{{tanggal_mulai}}' => '01 Juli ' . $issuedDate->format('Y'),
            '{{tanggal_selesai}}' => '30 Juni ' . $issuedDate->copy()->addYear()->format('Y'),
            '{{tanggal_terbit}}' => $this->formatIndonesianDate($issuedDate),
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
