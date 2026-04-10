<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Madrasah;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $madrasahOptions = $user->role === 'admin'
            ? Madrasah::query()->whereKey($user->madrasah_id)->orderBy('name')->get()
            : Madrasah::query()->orderBy('name')->get();

        $selectedMadrasahId = $user->role === 'admin'
            ? $user->madrasah_id
            : $request->integer('madrasah_id');

        $query = Siswa::query()->with('madrasah')->latest();

        if ($selectedMadrasahId) {
            $query->where('madrasah_id', $selectedMadrasahId);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', 'like', '%' . trim((string) $request->kelas) . '%');
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nis', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_orang_tua_wali', 'like', '%' . $keyword . '%');
            });
        }

        $siswas = $query->paginate(15)->withQueryString();

        $statsQuery = Siswa::query();
        if ($selectedMadrasahId) {
            $statsQuery->where('madrasah_id', $selectedMadrasahId);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('is_active', true)->count(),
            'madrasah' => $selectedMadrasahId ? 1 : $madrasahOptions->count(),
            'kelas' => (clone $statsQuery)->distinct('kelas')->count('kelas'),
        ];

        return view('data-sekolah.data-siswa', [
            'siswas' => $siswas,
            'madrasahOptions' => $madrasahOptions,
            'selectedMadrasahId' => $selectedMadrasahId,
            'stats' => $stats,
            'userRole' => $user->role,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $this->validateSiswa($request, $user);
        $madrasah = $this->resolveMadrasah($validated['madrasah_id'], $user);

        Siswa::create([
            'madrasah_id' => $madrasah->id,
            'nis' => $validated['nis'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_orang_tua_wali' => $validated['nama_orang_tua_wali'],
            'email' => $validated['email'],
            'email_orang_tua_wali' => $validated['email_orang_tua_wali'],
            'no_hp' => $validated['no_hp'],
            'no_hp_orang_tua_wali' => $validated['no_hp_orang_tua_wali'],
            'kelas' => $validated['kelas'],
            'nama_madrasah' => $madrasah->name,
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['nis']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Data siswa berhasil ditambahkan. Password default siswa adalah NIS.');
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeSiswaAccess($siswa, $user);

        $validated = $this->validateSiswa($request, $user, $siswa);
        $madrasah = $this->resolveMadrasah($validated['madrasah_id'], $user);

        $siswa->update([
            'madrasah_id' => $madrasah->id,
            'nis' => $validated['nis'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_orang_tua_wali' => $validated['nama_orang_tua_wali'],
            'email' => $validated['email'],
            'email_orang_tua_wali' => $validated['email_orang_tua_wali'],
            'no_hp' => $validated['no_hp'],
            'no_hp_orang_tua_wali' => $validated['no_hp_orang_tua_wali'],
            'kelas' => $validated['kelas'],
            'nama_madrasah' => $madrasah->name,
            'alamat' => $validated['alamat'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->authorizeSiswaAccess($siswa, auth()->user());
        $siswa->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $forcedMadrasah = $user->role === 'admin'
            ? Madrasah::query()->findOrFail($user->madrasah_id)
            : null;

        $import = new SiswaImport($forcedMadrasah);

        DB::transaction(function () use ($request, $import) {
            Excel::import($import, $request->file('file'));
        });

        return back()->with(
            'success',
            "Import selesai. {$import->created} data baru ditambahkan, {$import->updated} data diperbarui. Password default akun siswa adalah NIS."
        );
    }

    public function template()
    {
        return Excel::download(new SiswaTemplateExport(), 'template-import-data-siswa.xlsx');
    }

    private function validateSiswa(Request $request, $user, ?Siswa $siswa = null): array
    {
        $madrasahRule = $user->role === 'admin'
            ? Rule::in([$user->madrasah_id])
            : Rule::exists('madrasahs', 'id');

        return $request->validate([
            'madrasah_id' => ['required', 'integer', $madrasahRule],
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')->ignore($siswa?->id),
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_orang_tua_wali' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('siswa', 'email')->ignore($siswa?->id),
            ],
            'email_orang_tua_wali' => ['required', 'email', 'max:255'],
            'no_hp' => ['required', 'string', 'max:25'],
            'no_hp_orang_tua_wali' => ['required', 'string', 'max:25'],
            'kelas' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
        ]);
    }

    private function resolveMadrasah(int $madrasahId, $user): Madrasah
    {
        if ($user->role === 'admin') {
            return Madrasah::query()->whereKey($user->madrasah_id)->firstOrFail();
        }

        return Madrasah::query()->findOrFail($madrasahId);
    }

    private function authorizeSiswaAccess(Siswa $siswa, $user): void
    {
        abort_if($user->role === 'admin' && (int) $user->madrasah_id !== (int) $siswa->madrasah_id, 403);
    }
}
