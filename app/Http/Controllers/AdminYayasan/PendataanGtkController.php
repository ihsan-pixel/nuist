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
            'sk_awal' => 'nullable|file|mimes:pdf|max:10240',
            'sk_akhir' => 'nullable|file|mimes:pdf|max:10240',
            'foto_guru' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validated['madrasah_id'] != $user->madrasah_id) {
            abort(403, 'Unauthorized access');
        }

        $oldGtkPendataan = $user->gtkPendataan;
        $oldFiles = [];
        $newFiles = [];

        try {
            if ($request->hasFile('sk_awal')) {
                $validated['sk_awal_path'] = $request->file('sk_awal')->store("pendataan-gtk/{$user->id}/sk", 'local');
                $newFiles[] = ['local', $validated['sk_awal_path']];
                if ($oldGtkPendataan?->sk_awal_path) {
                    $oldFiles[] = ['local', $oldGtkPendataan->sk_awal_path];
                }
            }
            if ($request->hasFile('sk_akhir')) {
                $validated['sk_akhir_path'] = $request->file('sk_akhir')->store("pendataan-gtk/{$user->id}/sk", 'local');
                $newFiles[] = ['local', $validated['sk_akhir_path']];
                if ($oldGtkPendataan?->sk_akhir_path) {
                    $oldFiles[] = ['local', $oldGtkPendataan->sk_akhir_path];
                }
            }
            if ($request->hasFile('foto_guru')) {
                $validated['foto_guru_path'] = $request->file('foto_guru')->store("tenaga_pendidik/{$user->id}", 'public');
                $newFiles[] = ['public', $validated['foto_guru_path']];
                if ($user->avatar) {
                    $oldFiles[] = ['public', $user->avatar];
                }
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
                    'avatar' => $validated['foto_guru_path'] ?? $user->avatar,
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
                        'sk_awal_path' => $validated['sk_awal_path'] ?? $user->gtkPendataan?->sk_awal_path,
                        'sk_akhir_path' => $validated['sk_akhir_path'] ?? $user->gtkPendataan?->sk_akhir_path,
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
        } catch (\Throwable $exception) {
            foreach ($newFiles as [$disk, $path]) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        foreach ($oldFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        return back()->with('success', 'Data GTK berhasil diperbarui.');
    }

    public function downloadDocument(User $user, string $document)
    {
        $this->authorizeAccess();
        $this->authorizeUserBelongsToCurrentSchool($user);

        $column = $document === 'sk-awal' ? 'sk_awal_path' : 'sk_akhir_path';
        $label = $document === 'sk-awal' ? 'sk-awal' : 'sk-akhir';
        $path = $user->gtkPendataan?->{$column};

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '-', trim($user->name)) ?: 'gtk';

        return Storage::disk('local')->download($path, "{$label}-{$safeName}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);
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
