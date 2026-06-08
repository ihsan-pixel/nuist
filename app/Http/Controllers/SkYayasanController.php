<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\SkYayasanDocument;
use App\Models\SkYayasanRequest;
use App\Models\SkYayasanTemplate;
use App\Models\User;
use App\Models\Yayasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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
            ->with(['employee.statusKepegawaian', 'document'])
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

        return view('sk-yayasan.sekolah-index', [
            'submissions' => $submissions,
            'employees' => $employees,
            'statusCounts' => $statusCounts,
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee'])
                ->whereHas('request', fn ($query) => $query->where('madrasah_id', $madrasahId))
                ->where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get(),
        ]);
    }

    public function storeSchoolSubmission(Request $request): RedirectResponse
    {
        $user = $this->ensureSchoolAdmin();
        $madrasahId = (int) $user->madrasah_id;

        $validated = $request->validate([
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['required', 'integer', 'distinct'],
            'effective_start_date' => ['required', 'date'],
            'effective_end_date' => ['required', 'date', 'after_or_equal:effective_start_date'],
            'submission_notes' => ['nullable', 'string'],
        ]);

        $employees = User::query()
            ->with('statusKepegawaian')
            ->whereIn('id', $validated['employee_ids'])
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        if ($employees->count() !== count($validated['employee_ids'])) {
            return back()
                ->withErrors(['employee_ids' => 'Sebagian pegawai yang dipilih tidak valid untuk sekolah ini.'])
                ->withInput();
        }

        $openRequestEmployeeIds = SkYayasanRequest::query()
            ->whereIn('employee_id', $employees->keys())
            ->whereIn('current_status', ['submitted', 'reviewed', 'approved'])
            ->pluck('employee_id')
            ->all();

        $createdCount = 0;
        $skippedEmployees = [];

        DB::transaction(function () use ($employees, $openRequestEmployeeIds, $validated, $madrasahId, $user, &$createdCount, &$skippedEmployees) {
            foreach ($employees as $employee) {
                if (in_array($employee->id, $openRequestEmployeeIds, true)) {
                    $skippedEmployees[] = $employee->name;
                    continue;
                }

                SkYayasanRequest::create([
                    'madrasah_id' => $madrasahId,
                    'employee_id' => $employee->id,
                    'submitted_by' => $user->id,
                    'request_number' => $this->generateRequestNumber(),
                    'request_type' => 'perpanjangan',
                    'employment_category' => $employee->statusKepegawaian?->name ?? $employee->ketugasan,
                    'effective_start_date' => $validated['effective_start_date'],
                    'effective_end_date' => $validated['effective_end_date'],
                    'current_status' => 'submitted',
                    'submission_notes' => $validated['submission_notes'] ?? null,
                    'submitted_at' => now(),
                ]);

                $createdCount++;
            }
        });

        if ($createdCount === 0) {
            return back()
                ->withErrors([
                    'employee_ids' => 'Semua pegawai yang dipilih masih memiliki pengajuan SK Yayasan yang belum selesai: ' . implode(', ', $skippedEmployees) . '.',
                ])
                ->withInput();
        }

        $message = $createdCount . ' pengajuan perpanjangan SK berhasil dikirim ke Yayasan.';

        if (!empty($skippedEmployees)) {
            $message .= ' Pegawai yang dilewati karena masih memiliki pengajuan aktif: ' . implode(', ', $skippedEmployees) . '.';
        }

        return back()->with('success', $message);
    }

    public function superAdminPengajuan(Request $request): View
    {
        $this->ensureSuperAdmin();

        $submissions = SkYayasanRequest::query()
            ->with(['madrasah', 'employee.statusKepegawaian', 'submitter', 'reviewer', 'template', 'document'])
            ->when($request->filled('status'), fn ($query) => $query->where('current_status', $request->string('status')->toString()))
            ->when($request->filled('madrasah_id'), fn ($query) => $query->where('madrasah_id', (int) $request->madrasah_id))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = trim((string) $request->q);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('request_number', 'like', '%' . $keyword . '%')
                        ->orWhereHas('employee', fn ($employee) => $employee->where('name', 'like', '%' . $keyword . '%'))
                        ->orWhereHas('madrasah', fn ($madrasah) => $madrasah->where('name', 'like', '%' . $keyword . '%'));
                });
            })
            ->latest('submitted_at')
            ->paginate(12)
            ->withQueryString();

        return view('sk-yayasan.pengajuan-index', [
            'submissions' => $submissions,
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
            'templates' => SkYayasanTemplate::query()->latest()->get(),
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

    public function destroyTemplate(SkYayasanTemplate $template): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($template->requests()->exists() || $template->documents()->exists()) {
            return back()->with('error', 'Template sudah dipakai pada pengajuan atau dokumen SK Yayasan.');
        }

        $template->delete();

        return back()->with('success', 'Template SK Yayasan berhasil dihapus.');
    }

    public function generateIndex(Request $request): View
    {
        $this->ensureSuperAdmin();

        $requests = SkYayasanRequest::query()
            ->with(['madrasah', 'employee.statusKepegawaian', 'template', 'document'])
            ->whereIn('current_status', ['approved', 'published'])
            ->latest('reviewed_at')
            ->paginate(12)
            ->withQueryString();

        return view('sk-yayasan.generate-index', [
            'requests' => $requests,
            'templates' => SkYayasanTemplate::query()->where('is_active', true)->orderBy('name')->get(),
            'publishedDocuments' => SkYayasanDocument::query()
                ->with(['request.employee', 'request.madrasah'])
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
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_position' => ['nullable', 'string', 'max:255'],
            'publication_notes' => ['nullable', 'string'],
        ]);

        $submission = SkYayasanRequest::query()
            ->with(['madrasah.yayasan', 'employee.statusKepegawaian', 'document'])
            ->whereKey($validated['request_id'])
            ->firstOrFail();

        if (!in_array($submission->current_status, ['approved', 'published'], true)) {
            return back()->with('error', 'Dokumen hanya bisa digenerate dari pengajuan yang sudah disetujui.');
        }

        $template = SkYayasanTemplate::query()->findOrFail($validated['template_id']);
        $issuedDate = Carbon::parse($validated['issued_date']);
        $documentNumber = !empty($validated['document_number'])
            ? $validated['document_number']
            : $this->generateDocumentNumber($template, $submission, $issuedDate);

        DB::transaction(function () use ($submission, $template, $validated, $issuedDate, $documentNumber) {
            $placeholders = $this->buildTemplatePlaceholders($submission, [
                'nomor_sk' => $documentNumber,
                'tanggal_terbit' => $issuedDate->translatedFormat('d F Y'),
                'nama_penandatangan' => $validated['signer_name'],
                'jabatan_penandatangan' => $validated['signer_position'] ?? 'Ketua Yayasan',
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
            '{year}' => $issuedDate->format('Y'),
            '{school_code}' => $schoolCode,
        ]);
    }

    private function buildTemplatePlaceholders(SkYayasanRequest $submission, array $overrides = []): array
    {
        $submission->loadMissing(['madrasah.yayasan', 'employee.statusKepegawaian']);

        $employee = $submission->employee;
        $madrasah = $submission->madrasah;
        $yayasan = $madrasah->yayasan ?: Yayasan::query()->first();

        $base = [
            '{{nomor_sk}}' => $overrides['nomor_sk'] ?? '-',
            '{{judul_sk}}' => optional($submission->template)->document_title ?? 'Surat Keputusan Yayasan',
            '{{nama_yayasan}}' => $yayasan?->name ?? 'Yayasan',
            '{{alamat_yayasan}}' => $yayasan?->alamat ?? '-',
            '{{nama_sekolah}}' => $madrasah->name ?? '-',
            '{{nama_pegawai}}' => $employee->name ?? '-',
            '{{jabatan}}' => $employee->ketugasan ?? '-',
            '{{status_kepegawaian}}' => $employee->statusKepegawaian?->name ?? ($submission->employment_category ?? '-'),
            '{{tanggal_mulai}}' => optional($submission->effective_start_date)->translatedFormat('d F Y') ?? '-',
            '{{tanggal_selesai}}' => optional($submission->effective_end_date)->translatedFormat('d F Y') ?? '-',
            '{{tanggal_terbit}}' => $overrides['tanggal_terbit'] ?? now()->translatedFormat('d F Y'),
            '{{tahun_sk}}' => optional($submission->effective_start_date)->format('Y') ?? now()->format('Y'),
            '{{nama_penandatangan}}' => $overrides['nama_penandatangan'] ?? 'Ketua Yayasan',
            '{{jabatan_penandatangan}}' => $overrides['jabatan_penandatangan'] ?? 'Ketua Yayasan',
            '{{catatan_pengajuan}}' => $submission->submission_notes ?: '-',
            '{{catatan_penerbitan}}' => $overrides['catatan_penerbitan'] ?? '-',
        ];

        foreach ($overrides as $key => $value) {
            $base['{{' . $key . '}}'] = $value;
        }

        return $base;
    }

    private function renderTemplate(string $body, array $placeholders): string
    {
        return strtr($body, $placeholders);
    }
}
