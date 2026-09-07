<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PendataanGtkController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        $madrasahs = Madrasah::query()
            ->orderByRaw("CAST(COALESCE(NULLIF(scod, ''), '0') AS UNSIGNED) ASC")
            ->orderBy('name')
            ->get(['id', 'name', 'scod', 'kabupaten', 'alamat', 'logo']);

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
            'nuptk' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'kartanu' => 'nullable|string|max:100',
            'tmt' => 'nullable|date',
            'tmt_sk_pertama' => 'nullable|date',
            'tmt_sk_terakhir' => 'nullable|date',
            'nomor_sk_pertama' => 'nullable|string|max:100',
            'tahun_sk_pertama' => 'nullable|integer|min:1900|max:2100',
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
            'npk' => 'nullable|string|max:50',
            'mengajar' => 'nullable|string|max:255',
            'madrasah_id_tambahan' => 'nullable|exists:madrasahs,id',
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
                'npk' => $validated['npk'] ?? null,
                'mengajar' => $validated['mengajar'] ?? null,
                'madrasah_id_tambahan' => $validated['madrasah_id_tambahan'] ?? null,
            ]);

            $user->save();

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
                    'gaji_satpen' => $validated['gaji_satpen'] ?? null,
                    'nomor_sertifikasi_pendidik' => $validated['nomor_sertifikasi_pendidik'] ?? null,
                    'gaji_sertifikasi' => $validated['gaji_sertifikasi'] ?? null,
                    'tunjangan_rerata_bulanan' => $validated['tunjangan_rerata_bulanan'] ?? null,
                    'nama_mgmp' => $validated['nama_mgmp'] ?? null,
                    'produk_kerja_kolaboratif' => $validated['produk_kerja_kolaboratif'] ?? null,
                ]
            );
        });

        return back()->with('success', 'Data GTK berhasil diperbarui.');
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();

        if (!$user || trim(strtolower((string) $user->role)) !== 'admin_yayasan') {
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
