<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Exports\PendataanGtkExport;
use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PendataanGtkController extends Controller
{
    public function export(?Madrasah $madrasah = null)
    {
        $this->authorizeAccess();

        $gtk = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->when($madrasah, fn ($query) => $query->where('madrasah_id', $madrasah->id))
            ->with(['madrasah', 'gtkPendataan', 'mgmpMemberships.mgmpGroup'])
            ->orderBy('madrasah_id')
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ketugasan, ''))) LIKE '%kepala%' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ketugasan, ''))) = 'kepala madrasah/sekolah' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $scope = $madrasah ? 'sekolah-'.$madrasah->id : 'semua-sekolah';

        return Excel::download(new PendataanGtkExport($gtk), 'pendataan-gtk-'.$scope.'-'.now()->format('Ymd-His').'.xlsx');
    }

    public function index()
    {
        $this->authorizeAccess();

        $madrasahs = Madrasah::query()
            ->orderByRaw("CAST(COALESCE(NULLIF(scod, ''), '0') AS UNSIGNED) ASC")
            ->orderBy('name')
            ->select(['id', 'name', 'scod', 'kabupaten', 'alamat', 'logo'])
            ->withCount('tenagaPendidikUsers')
            ->get();

        return view('masterdata.pendataan-gtk.index', compact('madrasahs'));
    }

    public function show(Madrasah $madrasah)
    {
        $this->authorizeAccess();

        $statusKepegawaian = StatusKepegawaian::query()->orderBy('name')->get(['id', 'name']);

        $gtk = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            ->with(['statusKepegawaian', 'madrasah', 'gtkPendataan', 'mgmpMemberships.mgmpGroup', 'simfoni'])
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ketugasan, ''))) LIKE '%kepala%' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ketugasan, ''))) = 'kepala madrasah/sekolah' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('masterdata.pendataan-gtk.show', compact('madrasah', 'gtk', 'statusKepegawaian'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAccess();
        $this->authorizeUserBelongsToCurrentSchool($user);

        $validated = $request->validate([
            'gelar' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'nuist_id' => 'nullable|string|max:50',
            'madrasah_id' => 'required|exists:madrasahs,id',
            'email_aktif' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'status_kepegawaian_id' => 'nullable|exists:status_kepegawaian,id',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nik' => 'nullable|string|max:32',
            'gol_darah' => 'nullable|string|in:A,B,AB,O',
            'status_pernikahan' => 'nullable|string|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'nuptk' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'kartanu' => 'nullable|string|max:100',
            'tmt' => 'nullable|date',
            'tmt_sk_pertama' => 'nullable|date',
            'tmt_sk_terakhir' => 'nullable|date',
            'nomor_sk_pertama' => 'nullable|string|max:100',
            'tahun_sk_pertama' => 'nullable|integer|min:1900|max:2100',
            'keterangan_sk' => 'nullable|string|max:5000',
            'gaji_satpen' => 'nullable|numeric|min:0',
            'nomor_sertifikasi_pendidik' => 'nullable|string|max:100',
            'gaji_sertifikasi' => 'nullable|numeric|min:0',
            'tunjangan_rerata_bulanan' => 'nullable|numeric|min:0',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|digits:4',
            'program_studi' => 'nullable|string|max:255',
            'ketugasan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'nama_mgmp' => 'nullable|string|max:255',
            'produk_kerja_kolaboratif' => 'nullable|string|max:5000',
            'masa_kerja' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'mengajar' => 'nullable|string|max:255',
            'catatan_step_1' => 'nullable|string|max:5000',
            'catatan_step_2' => 'nullable|string|max:5000',
            'catatan_step_3' => 'nullable|string|max:5000',
            'catatan_step_4' => 'nullable|string|max:5000',
            'catatan_step_5' => 'nullable|string|max:5000',
        ]);

        if ($validated['madrasah_id'] != $user->madrasah_id) {
            abort(403, 'Unauthorized access');
        }

        DB::transaction(function () use ($user, $validated) {
            $user->fill([
                'gelar' => $validated['gelar'] ?? null,
                'name' => $validated['name'],
                'nuist_id' => $validated['nuist_id'] ?? $user->nuist_id,
                'madrasah_id' => $validated['madrasah_id'],
                'email' => $validated['email_aktif'] ?? $user->email,
                'status_kepegawaian_id' => $validated['status_kepegawaian_id'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'nuptk' => $validated['nuptk'] ?? null,
                'nip' => $validated['nip'] ?? null,
                'kartanu' => $validated['kartanu'] ?? null,
                'tmt' => $validated['tmt'] ?? null,
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
                'tahun_lulus' => $validated['tahun_lulus'] ?? null,
                'program_studi' => $validated['program_studi'] ?? null,
                'ketugasan' => $validated['ketugasan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'is_active' => $validated['is_active'],
                'masa_kerja' => $validated['masa_kerja'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'mengajar' => $validated['mengajar'] ?? null,
            ]);

            $user->save();

            $user->simfoni()->updateOrCreate(
                ['user_id' => $user->id],
                ['status_pernikahan' => $validated['status_pernikahan'] ?? null]
            );

            $user->gtkPendataan()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik' => $validated['nik'] ?? null,
                    'gol_darah' => $validated['gol_darah'] ?? null,
                    'email_aktif' => $validated['email_aktif'] ?? $user->email,
                    'tmt_sk_pertama' => $validated['tmt_sk_pertama'] ?? null,
                    'tmt_sk_terakhir' => $validated['tmt_sk_terakhir'] ?? null,
                    'nomor_sk_pertama' => $validated['nomor_sk_pertama'] ?? null,
                    'tahun_sk_pertama' => $validated['tahun_sk_pertama'] ?? null,
                    'keterangan_sk' => $validated['keterangan_sk'] ?? null,
                    'gaji_satpen' => $validated['gaji_satpen'] ?? null,
                    'nomor_sertifikasi_pendidik' => $validated['nomor_sertifikasi_pendidik'] ?? null,
                    'gaji_sertifikasi' => $validated['gaji_sertifikasi'] ?? null,
                    'tunjangan_rerata_bulanan' => $validated['tunjangan_rerata_bulanan'] ?? null,
                    'nama_mgmp' => $validated['nama_mgmp'] ?? null,
                    'produk_kerja_kolaboratif' => $validated['produk_kerja_kolaboratif'] ?? null,
                    'catatan_step_1' => $validated['catatan_step_1'] ?? null,
                    'catatan_step_2' => $validated['catatan_step_2'] ?? null,
                    'catatan_step_3' => $validated['catatan_step_3'] ?? null,
                    'catatan_step_4' => $validated['catatan_step_4'] ?? null,
                    'catatan_step_5' => $validated['catatan_step_5'] ?? null,
                ]
            );
        });

        return back()->with('success', 'Data GTK berhasil diperbarui.');
    }

    public function updateDocuments(Request $request, User $user)
    {
        $this->authorizeAccess();
        $this->authorizeUserBelongsToCurrentSchool($user);

        $request->validate([
            'sk_awal' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'sk_akhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'foto_guru' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'foto_bebas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if (! collect(['sk_awal', 'sk_akhir', 'ktp', 'foto_guru', 'foto_bebas'])->contains(fn ($field) => $request->hasFile($field))) {
            return back()->withErrors(['berkas' => 'Pilih minimal satu berkas untuk diunggah.']);
        }

        $gtkPendataan = $user->gtkPendataan;
        $oldFiles = [];
        $newFiles = [];
        $documentPaths = [];

        try {
            foreach (['sk_awal', 'sk_akhir', 'ktp', 'foto_bebas'] as $field) {
                if (! $request->hasFile($field)) {
                    continue;
                }

                $pathColumn = $field.'_path';
                $documentPaths[$pathColumn] = $this->storeGtkDocument($request->file($field), $user, $field, 'local');
                $newFiles[] = ['local', $documentPaths[$pathColumn]];
                if ($gtkPendataan?->{$pathColumn}) {
                    $oldFiles[] = ['local', $gtkPendataan->{$pathColumn}];
                }
            }

            $avatarPath = null;
            if ($request->hasFile('foto_guru')) {
                $avatarPath = $this->storeGtkDocument($request->file('foto_guru'), $user, 'foto_resmi', 'public');
                $newFiles[] = ['public', $avatarPath];
                if ($user->avatar) {
                    $oldFiles[] = ['public', $user->avatar];
                }
            }

            DB::transaction(function () use ($user, $documentPaths, $avatarPath) {
                if ($avatarPath) {
                    $user->update(['avatar' => $avatarPath]);
                }

                if ($documentPaths !== []) {
                    $user->gtkPendataan()->updateOrCreate(['user_id' => $user->id], $documentPaths);
                }
            });
        } catch (\Throwable $exception) {
            foreach ($newFiles as [$disk, $path]) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        foreach ($oldFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        return back()->with('success', 'Berkas GTK berhasil diperbarui.');
    }

    public function manageDocuments(Madrasah $madrasah)
    {
        $this->authorizeAccess();

        $gtk = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            ->with('gtkPendataan')
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ketugasan, ''))) LIKE '%kepala%' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('masterdata.pendataan-gtk.documents-manage', compact('madrasah', 'gtk'));
    }

    public function storeManagedDocuments(Request $request, Madrasah $madrasah)
    {
        $this->authorizeAccess();

        $request->validate([
            'documents' => 'required|array|max:200',
            'documents.*' => 'array',
            'documents.*.ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'documents.*.sk_awal' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'documents.*.sk_akhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'documents.*.foto_resmi' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'documents.*.foto_bebas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $uploaded = $request->file('documents', []);
        $allowedTypes = ['ktp', 'sk_awal', 'sk_akhir', 'foto_resmi', 'foto_bebas'];
        foreach ($uploaded as $files) {
            abort_if(array_diff(array_keys($files), $allowedTypes) !== [], 422, 'Jenis berkas GTK tidak valid.');
        }
        $userIds = collect(array_keys($uploaded))->map(fn ($id) => (int) $id)->filter()->unique()->values();
        $users = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            ->whereIn('id', $userIds)
            ->with('gtkPendataan')
            ->get()
            ->keyBy('id');

        abort_if($users->count() !== $userIds->count(), 422, 'Terdapat GTK yang tidak valid untuk madrasah ini.');

        $newFiles = [];
        $oldFiles = [];
        $storedAssignments = [];

        try {
            foreach ($uploaded as $userId => $files) {
                $user = $users->get((int) $userId);
                foreach ($files as $type => $file) {
                    if (! $file) {
                        continue;
                    }

                    $disk = $type === 'foto_resmi' ? 'public' : 'local';
                    $path = $this->storeGtkDocument($file, $user, $type, $disk);
                    $newFiles[] = [$disk, $path];
                    $storedAssignments[] = compact('user', 'type', 'path');

                    $oldPath = $type === 'foto_resmi'
                        ? $user->avatar
                        : $user->gtkPendataan?->{$type.'_path'};
                    if ($oldPath) {
                        $oldFiles[] = [$disk, $oldPath];
                    }
                }
            }

            DB::transaction(function () use ($storedAssignments) {
                foreach ($storedAssignments as $assignment) {
                    if ($assignment['type'] === 'foto_resmi') {
                        $assignment['user']->update(['avatar' => $assignment['path']]);
                    } else {
                        $assignment['user']->gtkPendataan()->updateOrCreate(
                            ['user_id' => $assignment['user']->id],
                            [$assignment['type'].'_path' => $assignment['path']]
                        );
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($newFiles as [$disk, $path]) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        foreach ($oldFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        $message = count($storedAssignments).' berkas GTK berhasil disimpan atau diperbarui.';

        return $request->expectsJson()
            ? response()->json(['message' => $message, 'uploaded' => count($storedAssignments)])
            : redirect()
                ->route('pendataan-gtk.documents.manage', $madrasah)
                ->with('success', $message);
    }

    public function destroyManagedDocument(Request $request, Madrasah $madrasah, User $user, string $type)
    {
        $this->authorizeAccess();
        abort_unless(
            (int) $user->madrasah_id === (int) $madrasah->id
            && trim(strtolower((string) $user->role)) === 'tenaga_pendidik',
            404
        );

        $documentColumns = [
            'ktp' => 'ktp_path',
            'sk_awal' => 'sk_awal_path',
            'sk_akhir' => 'sk_akhir_path',
            'foto_bebas' => 'foto_bebas_path',
        ];

        if ($type === 'foto_resmi') {
            $path = $user->avatar;
            $disk = 'public';
            $user->update(['avatar' => null]);
        } else {
            $column = $documentColumns[$type] ?? abort(404);
            $path = $user->gtkPendataan?->{$column};
            $disk = 'local';
            if ($user->gtkPendataan) {
                $user->gtkPendataan->update([$column => null]);
            }
        }

        if ($path) {
            Storage::disk($disk)->delete($path);
        }

        $message = 'Berkas '.str_replace('_', ' ', $type).' milik '.$user->name.' berhasil dihapus.';

        return $request->expectsJson()
            ? response()->json(['message' => $message])
            : back()->with('success', $message);
    }

    public function bulkUpdateDocuments(Request $request, Madrasah $madrasah)
    {
        $this->authorizeAccess();

        $request->validate([
            'ktp_files' => 'nullable|array|max:200',
            'ktp_files.*' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'sk_awal_files' => 'nullable|array|max:200',
            'sk_awal_files.*' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'sk_akhir_files' => 'nullable|array|max:200',
            'sk_akhir_files.*' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'foto_resmi_files' => 'nullable|array|max:200',
            'foto_resmi_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'foto_bebas_files' => 'nullable|array|max:200',
            'foto_bebas_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $fileGroups = [
            'ktp_files' => 'ktp',
            'sk_awal_files' => 'sk_awal',
            'sk_akhir_files' => 'sk_akhir',
            'foto_resmi_files' => 'foto_resmi',
            'foto_bebas_files' => 'foto_bebas',
        ];
        if (! collect(array_keys($fileGroups))->contains(fn ($field) => $request->hasFile($field))) {
            return back()->withErrors(['files' => 'Pilih minimal satu berkas untuk diunggah.']);
        }

        $users = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            ->with('gtkPendataan')
            ->get();
        $lookup = [];
        foreach ($users as $user) {
            foreach (array_filter([$user->nuist_id, $user->name]) as $identifier) {
                $lookup[$this->normalizeDocumentIdentifier($identifier)][] = $user;
            }
        }

        $assignments = [];
        $failures = [];
        $seen = [];

        foreach ($fileGroups as $field => $type) {
            foreach ($request->file($field, []) as $file) {
                $originalName = $file->getClientOriginalName();
                $identifier = pathinfo($originalName, PATHINFO_FILENAME);
                $identifier = preg_replace('/[\s_-]+(sk[\s_-]*awal|sk[\s_-]*akhir|ktp|foto[\s_-]*resmi|foto[\s_-]*bebas)$/i', '', $identifier);
                $matchedUsers = collect($lookup[$this->normalizeDocumentIdentifier($identifier)] ?? [])->unique('id')->values();
                if ($matchedUsers->count() !== 1) {
                    $reason = $matchedUsers->isEmpty() ? 'GTK tidak ditemukan' : 'nama GTK ambigu, gunakan NUIST ID';
                    $failures[] = "{$originalName}: {$reason}";

                    continue;
                }

                $user = $matchedUsers->first();
                $key = $user->id.':'.$type;
                if (isset($seen[$key])) {
                    $failures[] = "{$originalName}: jenis berkas untuk GTK ini dipilih lebih dari sekali";

                    continue;
                }
                $seen[$key] = true;
                $assignments[] = compact('user', 'type', 'file', 'originalName');
            }
        }

        if ($assignments === []) {
            return back()->withErrors(['files' => 'Tidak ada file yang dapat dipasangkan ke GTK.'])->with('bulk_upload_failures', $failures);
        }

        $newFiles = [];
        $oldFiles = [];
        $storedAssignments = [];

        try {
            foreach ($assignments as $assignment) {
                $user = $assignment['user'];
                $type = $assignment['type'];
                $disk = $type === 'foto_resmi' ? 'public' : 'local';
                $path = $this->storeGtkDocument($assignment['file'], $user, $type, $disk);
                $newFiles[] = [$disk, $path];
                $storedAssignments[] = compact('user', 'type', 'disk', 'path');

                $oldPath = $type === 'foto_resmi'
                    ? $user->avatar
                    : $user->gtkPendataan?->{$type.'_path'};
                if ($oldPath) {
                    $oldFiles[] = [$disk, $oldPath];
                }
            }

            DB::transaction(function () use ($storedAssignments) {
                foreach ($storedAssignments as $assignment) {
                    $user = $assignment['user'];
                    if ($assignment['type'] === 'foto_resmi') {
                        $user->update(['avatar' => $assignment['path']]);
                    } else {
                        $user->gtkPendataan()->updateOrCreate(
                            ['user_id' => $user->id],
                            [$assignment['type'].'_path' => $assignment['path']]
                        );
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($newFiles as [$disk, $path]) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        foreach ($oldFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        return back()
            ->with('success', count($storedAssignments).' berkas berhasil dipasangkan dan diunggah.')
            ->with('bulk_upload_failures', $failures);
    }

    public function downloadDocument(User $user, string $document)
    {
        $this->authorizeAccess();
        $this->authorizeUserBelongsToCurrentSchool($user);

        $documents = [
            'sk-awal' => 'sk_awal_path',
            'sk-akhir' => 'sk_akhir_path',
            'ktp' => 'ktp_path',
            'foto-bebas' => 'foto_bebas_path',
        ];
        $column = $documents[$document] ?? abort(404);
        $label = $document;
        $path = $user->gtkPendataan?->{$column};

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '-', trim($user->name)) ?: 'gtk';

        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf';

        return Storage::disk('local')->download($path, "{$label}-{$safeName}.{$extension}");
    }

    private function normalizeDocumentIdentifier(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/', '', Str::lower(Str::ascii(trim($value)))) ?? '';
    }

    private function storeGtkDocument($file, User $user, string $type, string $disk): string
    {
        $directory = $type === 'foto_resmi'
            ? "tenaga_pendidik/{$user->id}"
            : "pendataan-gtk/{$user->id}/documents";
        $typeName = str_replace('_', '-', $type);
        $teacherName = Str::slug($user->name) ?: 'gtk-'.$user->id;
        $identifier = Str::slug((string) $user->nuist_id);
        $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension() ?: 'bin');
        $convertToPdf = in_array($type, ['sk_awal', 'sk_akhir'], true) && $extension !== 'pdf';
        if ($convertToPdf) {
            $extension = 'pdf';
        }
        $timestamp = now()->format('Ymd-His-v');
        $filename = implode('-', array_filter([$typeName, $identifier, $teacherName, $timestamp])).'.'.$extension;

        if ($convertToPdf) {
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType() ?: 'image/jpeg';
            $dimensions = @getimagesize($file->getRealPath());
            $orientation = $dimensions && $dimensions[0] > $dimensions[1] ? 'landscape' : 'portrait';
            $html = '<!doctype html><html><head><meta charset="utf-8"><style>'
                .'@page{margin:10mm}html,body{margin:0;padding:0;width:100%;height:100%}'
                .'table{border-collapse:collapse;width:100%;height:100%}td{text-align:center;vertical-align:middle}'
                .'img{max-width:100%;max-height:257mm;object-fit:contain}'
                .'</style></head><body><table><tr><td><img src="data:'
                .$mimeType.';base64,'.$imageData.'"></td></tr></table></body></html>';
            $pdfContent = Pdf::loadHTML($html)->setPaper('a4', $orientation)->output();
            $path = $directory.'/'.$filename;

            if (! Storage::disk($disk)->put($path, $pdfContent)) {
                throw new \RuntimeException('Gagal menyimpan hasil konversi PDF.');
            }

            return $path;
        }

        return $file->storeAs($directory, $filename, $disk);
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();

        if (! $user || trim(strtolower((string) $user->role)) !== 'admin_yayasan') {
            abort(403, 'Unauthorized access');
        }
    }

    private function authorizeUserBelongsToCurrentSchool(User $user): void
    {
        if (trim(strtolower((string) $user->role)) !== 'tenaga_pendidik') {
            abort(404);
        }
    }
}
