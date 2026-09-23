<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Exports\PendataanGtkExport;
use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\User;
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
            'sk_awal' => 'nullable|file|mimes:pdf|max:10240',
            'sk_akhir' => 'nullable|file|mimes:pdf|max:10240',
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
                $documentPaths[$pathColumn] = $request->file($field)->store("pendataan-gtk/{$user->id}/documents", 'local');
                $newFiles[] = ['local', $documentPaths[$pathColumn]];
                if ($gtkPendataan?->{$pathColumn}) {
                    $oldFiles[] = ['local', $gtkPendataan->{$pathColumn}];
                }
            }

            $avatarPath = null;
            if ($request->hasFile('foto_guru')) {
                $avatarPath = $request->file('foto_guru')->store("tenaga_pendidik/{$user->id}", 'public');
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

    public function bulkUpdateDocuments(Request $request, Madrasah $madrasah)
    {
        $this->authorizeAccess();

        $request->validate([
            'files' => 'required|array|min:1|max:200',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ]);

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

        $typeAliases = [
            'skawal' => 'sk_awal',
            'skakhir' => 'sk_akhir',
            'ktp' => 'ktp',
            'fotoresmi' => 'foto_resmi',
            'fotobebas' => 'foto_bebas',
        ];
        $assignments = [];
        $failures = [];
        $seen = [];

        foreach ($request->file('files', []) as $file) {
            $originalName = $file->getClientOriginalName();
            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
            if (! preg_match('/^(.+?)[\s_-]+(sk[\s_-]*awal|sk[\s_-]*akhir|ktp|foto[\s_-]*resmi|foto[\s_-]*bebas)$/i', $baseName, $matches)) {
                $failures[] = "{$originalName}: format nama file tidak dikenali";

                continue;
            }

            $identifier = $this->normalizeDocumentIdentifier($matches[1]);
            $type = $typeAliases[$this->normalizeDocumentIdentifier($matches[2])] ?? null;
            $matchedUsers = collect($lookup[$identifier] ?? [])->unique('id')->values();
            if ($matchedUsers->count() !== 1) {
                $reason = $matchedUsers->isEmpty() ? 'GTK tidak ditemukan' : 'nama GTK ambigu, gunakan NUIST ID';
                $failures[] = "{$originalName}: {$reason}";

                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($type, ['sk_awal', 'sk_akhir'], true) && $extension !== 'pdf') {
                $failures[] = "{$originalName}: SK harus berformat PDF";

                continue;
            }
            if (in_array($type, ['foto_resmi', 'foto_bebas'], true) && ! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $failures[] = "{$originalName}: foto harus berformat JPG, PNG, atau WebP";

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
                $directory = $type === 'foto_resmi'
                    ? "tenaga_pendidik/{$user->id}"
                    : "pendataan-gtk/{$user->id}/documents";
                $path = $assignment['file']->store($directory, $disk);
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
