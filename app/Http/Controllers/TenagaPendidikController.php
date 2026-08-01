<?php

namespace App\Http\Controllers;

use App\Exports\TenagaPendidikSchoolSummaryExport;
use App\Imports\TenagaPendidikImport;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class TenagaPendidikController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $this->ensureAuthorizedRole($user);

        $madrasahs = Madrasah::query()
            ->orderBy('name')
            ->get(['id', 'name']);
        $statusKepegawaian = StatusKepegawaian::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('masterdata.tenaga-pendidik.index', [
            'madrasahs' => $madrasahs,
            'statusKepegawaian' => $statusKepegawaian,
            'userRole' => trim(strtolower($user->role)),
            'isSuperAdmin' => trim(strtolower($user->role)) === 'super_admin',
        ]);
    }

    public function data(Request $request)
    {
        $user = auth()->user();
        $this->ensureAuthorizedRole($user);

        $query = $this->tenagaPendidikDataQuery($user);
        $isAdmin = trim(strtolower($user->role)) === 'admin';

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('nuist_id', fn ($row) => $row->nuist_id ?: '-')
            ->editColumn('kartanu', fn ($row) => $row->kartanu ?: '-')
            ->editColumn('nuptk', fn ($row) => $row->nuptk ?: '-')
            ->editColumn('pendidikan_terakhir', fn ($row) => $row->pendidikan_terakhir ?: '-')
            ->editColumn('madrasah_name', fn ($row) => $row->madrasah_name ?: '-')
            ->editColumn('status_kepegawaian_name', fn ($row) => $row->status_kepegawaian_name ?: '-')
            ->editColumn('ketugasan', fn ($row) => $row->ketugasan ?: '-')
            ->editColumn('mengajar', fn ($row) => $row->mengajar ?: '-')
            ->editColumn('alamat', fn ($row) => $row->alamat ?: '-')
            ->addColumn('avatar_url', function ($row) {
                return $row->avatar ? asset('storage/' . $row->avatar) : null;
            })
            ->addColumn('tanggal_lahir_form', function ($row) {
                return $row->tanggal_lahir ? Carbon::parse($row->tanggal_lahir)->format('Y-m-d') : '';
            })
            ->addColumn('tanggal_lahir_display', function ($row) {
                return $row->tanggal_lahir ? Carbon::parse($row->tanggal_lahir)->translatedFormat('j F Y') : '-';
            })
            ->addColumn('tmt_form', function ($row) {
                return $row->tmt ? Carbon::parse($row->tmt)->format('Y-m-d') : '';
            })
            ->addColumn('tmt_display', function ($row) {
                return $row->tmt ? Carbon::parse($row->tmt)->translatedFormat('j F Y') : '-';
            })
            ->addColumn('pemenuhan_beban_kerja_lain_label', function ($row) {
                return (int) $row->pemenuhan_beban_kerja_lain === 1 ? 'Ya' : 'Tidak';
            })
            ->addColumn('action', function ($row) use ($isAdmin) {
                $buttons = [];

                if ($isAdmin) {
                    $buttons[] = sprintf(
                        '<button type="button" class="btn btn-sm btn-info view-tenaga-pendidik-btn" data-id="%d"><i class="bx bx-show"></i> View</button>',
                        $row->id
                    );
                    $buttons[] = sprintf(
                        '<button type="button" class="btn btn-sm btn-warning edit-tenaga-pendidik-btn" data-id="%d"><i class="bx bx-edit"></i> Edit</button>',
                        $row->id
                    );

                    return implode(' ', $buttons);
                }

                $buttons[] = sprintf(
                    '<button type="button" class="btn btn-sm btn-warning edit-tenaga-pendidik-btn" data-id="%d"><i class="bx bx-edit"></i> Edit</button>',
                    $row->id
                );
                $buttons[] = sprintf(
                    '<button type="button" class="btn btn-sm btn-danger delete-tenaga-pendidik-btn" data-id="%d" data-name="%s">Delete</button>',
                    $row->id,
                    e($row->name)
                );

                return implode(' ', $buttons);
            })
            ->filterColumn('madrasah_name', function ($query, $keyword) {
                $query->where('madrasahs.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('status_kepegawaian_name', function ($query, $keyword) {
                $query->where('status_kepegawaian.name', 'like', '%' . $keyword . '%');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function exportSchoolSummary()
    {
        $user = auth()->user();
        $this->ensureAuthorizedRole($user);

        $rows = $this->schoolSummaryRows($user);
        $fileName = 'rekap_tenaga_pendidik_per_sekolah_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new TenagaPendidikSchoolSummaryExport($rows), $fileName);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'madrasah_id' => 'nullable|exists:madrasahs,id',
            'ketugasan' => 'nullable|in:tenaga pendidik,penjaga sekolah,kepala madrasah/sekolah',
            'mengajar' => 'nullable|string|max:255',
        ]);

        $avatarPath = $request->hasFile('avatar')
            ? $request->file('avatar')->store('tenaga_pendidik', 'public')
            : null;

        $inputPassword = $validated['password'];

        $tmtDate = null;
        if ($request->tmt) {
            try {
                $tmtDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tmt)->format('Y-m-d');
            } catch (\Exception $e) {
                $tmtDate = $request->tmt;
            }
        }

        $user = User::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['nama'],
                'password' => Hash::make($inputPassword),
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'kartanu' => $request->kartanu,
                'nip' => $request->nip,
                'nuptk' => $request->nuptk,
                'npk' => $request->npk,
                'madrasah_id' => $request->madrasah_id,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tahun_lulus' => $request->tahun_lulus,
                'program_studi' => $request->program_studi,
                'status_kepegawaian_id' => $request->status_kepegawaian_id,
                'tmt' => $tmtDate,
                'ketugasan' => $request->ketugasan,
                'mengajar' => $request->mengajar,
                'avatar' => $avatarPath,
                'alamat' => $request->alamat,
                'pemenuhan_beban_kerja_lain' => $request->pemenuhan_beban_kerja_lain,
                'madrasah_id_tambahan' => $request->madrasah_id_tambahan,
                'role' => 'tenaga_pendidik',
            ]
        );

        return redirect()->route('tenaga-pendidik.index')->with('success', 'Tenaga pendidik berhasil ditambahkan. Mohon ganti password setelah login.');
    }

    public function update(Request $request, $id)
    {
        $actor = auth()->user();
        $user = User::where('role', 'tenaga_pendidik')->findOrFail($id);

        if ($actor->role === 'admin' && (int) $user->madrasah_id !== (int) $actor->madrasah_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'madrasah_id' => 'nullable|exists:madrasahs,id',
            'ketugasan' => 'nullable|in:tenaga pendidik,penjaga sekolah,kepala madrasah/sekolah',
            'mengajar' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('tenaga_pendidik', 'public');
        }

        $madrasahId = $actor->role === 'super_admin'
            ? $request->madrasah_id
            : $user->madrasah_id;

        $user->name = $validated['nama'];
        $user->email = $validated['email'];
        if(!empty($validated['password'])){
            $user->password = Hash::make($validated['password']);
        }
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->no_hp = $request->no_hp;
        $user->kartanu = $request->kartanu;
        $user->nip = $request->nip;
        $user->nuptk = $request->nuptk;
        $user->npk = $request->npk;
        $user->madrasah_id = $madrasahId;
        $user->pendidikan_terakhir = $request->pendidikan_terakhir;
        $user->tahun_lulus = $request->tahun_lulus;
        $user->program_studi = $request->program_studi;
        $user->status_kepegawaian_id = $request->status_kepegawaian_id;
        $user->tmt = $request->tmt;
        $user->ketugasan = $request->ketugasan;
        $user->mengajar = $request->mengajar;
        $user->alamat = $request->alamat;
        $user->pemenuhan_beban_kerja_lain = $request->pemenuhan_beban_kerja_lain;
        $user->madrasah_id_tambahan = $request->madrasah_id_tambahan;
        $user->save();

        return redirect()->route('tenaga-pendidik.index')->with('success', 'Tenaga pendidik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $actor = auth()->user();
        $user = User::where('role', 'tenaga_pendidik')->findOrFail($id);

        if ($actor->role === 'admin' && (int) $user->madrasah_id !== (int) $actor->madrasah_id) {
            abort(403, 'Unauthorized access');
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('tenaga-pendidik.index')->with('success', 'Tenaga pendidik berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TenagaPendidikImport, $request->file('file'));
            return redirect()->route('tenaga-pendidik.index')->with('success', 'Data tenaga pendidik berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: '.$e->getMessage());
        }
    }

    private function ensureAuthorizedRole(User $user): void
    {
        if (!in_array(trim(strtolower($user->role)), ['super_admin', 'admin', 'pengurus'], true)) {
            abort(403, 'Unauthorized access');
        }
    }

    private function tenagaPendidikDataQuery(User $user)
    {
        $query = User::query()
            ->leftJoin('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
            ->leftJoin('madrasahs as madrasah_tambahan', 'users.madrasah_id_tambahan', '=', 'madrasah_tambahan.id')
            ->leftJoin('status_kepegawaian', 'users.status_kepegawaian_id', '=', 'status_kepegawaian.id')
            ->where('users.role', 'tenaga_pendidik')
            ->whereNotNull('users.madrasah_id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.nuist_id',
                'users.kartanu',
                'users.nip',
                'users.nuptk',
                'users.npk',
                'users.tempat_lahir',
                'users.tanggal_lahir',
                'users.no_hp',
                'users.pendidikan_terakhir',
                'users.tahun_lulus',
                'users.program_studi',
                'users.tmt',
                'users.ketugasan',
                'users.mengajar',
                'users.alamat',
                'users.avatar',
                'users.status_kepegawaian_id',
                'users.madrasah_id',
                'users.pemenuhan_beban_kerja_lain',
                'users.madrasah_id_tambahan',
                'madrasahs.name as madrasah_name',
                'madrasahs.scod as madrasah_scod',
                'status_kepegawaian.name as status_kepegawaian_name',
                'madrasah_tambahan.name as madrasah_tambahan_name',
            ])
            ->orderBy('madrasahs.scod')
            ->orderBy('users.name');

        if (trim(strtolower($user->role)) === 'admin') {
            $query->where('users.madrasah_id', $user->madrasah_id);
        }

        return $query;
    }

    private function schoolSummaryRows(User $user)
    {
        $query = Madrasah::query()
            ->leftJoin('users', function ($join) {
                $join->on('madrasahs.id', '=', 'users.madrasah_id')
                    ->where('users.role', '=', 'tenaga_pendidik');
            })
            ->select([
                'madrasahs.id',
                'madrasahs.scod',
                'madrasahs.name',
                DB::raw('SUM(CASE WHEN users.status_kepegawaian_id IN (3, 4, 5, 6) THEN 1 ELSE 0 END) as jumlah_guru'),
                DB::raw('SUM(CASE WHEN users.status_kepegawaian_id IN (7, 8) THEN 1 ELSE 0 END) as jumlah_karyawan'),
                DB::raw('SUM(CASE WHEN users.status_kepegawaian_id IN (3, 4, 5, 6, 7, 8) THEN 1 ELSE 0 END) as total'),
            ])
            ->groupBy('madrasahs.id', 'madrasahs.scod', 'madrasahs.name')
            ->orderBy('madrasahs.scod')
            ->orderBy('madrasahs.name');

        if (trim(strtolower($user->role)) === 'admin') {
            $query->where('madrasahs.id', $user->madrasah_id);
        }

        return $query->get()->map(function ($row, $index) {
            return [
                'no' => $index + 1,
                'scod' => $row->scod,
                'madrasah' => $row->name,
                'jumlah_guru' => (int) $row->jumlah_guru,
                'jumlah_karyawan' => (int) $row->jumlah_karyawan,
                'total' => (int) $row->total,
            ];
        });
    }
}
