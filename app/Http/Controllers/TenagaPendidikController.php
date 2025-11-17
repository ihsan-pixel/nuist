<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TenagaPendidikImport;

class TenagaPendidikController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $tenagaPendidiks = User::with('madrasah')
                ->where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->get();
        } elseif ($user->role === 'pengurus' || $user->role === 'super_admin') {
            $tenagaPendidiks = User::with('madrasah')
                ->where('role', 'tenaga_pendidik')
                ->get();
        } else {
            abort(403, 'Unauthorized access');
        }
        $madrasahs = Madrasah::all();
        $statusKepegawaian = StatusKepegawaian::all();
        return view('masterdata.tenaga-pendidik.index', compact('tenagaPendidiks', 'madrasahs', 'statusKepegawaian'));
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
        $user = User::where('role', 'tenaga_pendidik')->findOrFail($id);

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
        $user->madrasah_id = $request->madrasah_id;
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
        $user = User::where('role', 'tenaga_pendidik')->findOrFail($id);

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
}
