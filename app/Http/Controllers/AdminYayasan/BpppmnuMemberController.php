<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Imports\BpppmnuMembersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class BpppmnuMemberController extends Controller
{
    public function index()
    {
        $members = User::with('madrasah:id,name')->where(function ($query) {
            $query->where('role', 'pengurus_bpppmnu')->orWhere('is_bpppmnu_member', true);
        })->orderBy('name')->paginate(30);
        $teachers = User::with(['madrasah:id,name', 'madrasahTambahan:id,name'])->where('role', 'tenaga_pendidik')->where('is_active', true)->orderBy('name')->get(['id', 'name', 'email', 'jabatan', 'ketugasan', 'instansi_asal', 'madrasah_id', 'madrasah_id_tambahan']);

        return view('admin.bpppmnu.members', compact('members', 'teachers'));
    }

    public function store(Request $request)
    {
        if ($request->filled('existing_user_id')) {
            $teacher = User::where('role', 'tenaga_pendidik')->where('is_active', true)->findOrFail($request->integer('existing_user_id'));
            $request->merge([
                'name' => $teacher->name,
                'email' => $teacher->email,
                // Jabatan/instansi tenaga pendidik adalah data master users.
                // Jangan menimpa nilainya dengan data khusus kepengurusan BPPPMNU.
                'jabatan' => $teacher->jabatan,
                'ketugasan' => $teacher->ketugasan,
                'instansi_asal' => $teacher->instansi_asal,
                'bpppmnu_jabatan' => $request->input('jabatan'),
                'bpppmnu_instansi_asal' => $teacher->madrasah?->name ?: $teacher->instansi_asal,
                'is_active' => '1',
            ]);
            $teacher->is_bpppmnu_member = true;
            $teacher->save();
            return $this->save($request, $teacher);
        }
        return $this->save($request, new User);
    }

    public function update(Request $request, User $member)
    {
        abort_unless($member->role === 'pengurus_bpppmnu', 404);

        return $this->save($request, $member);
    }

    public function destroy(User $member)
    {
        abort_unless(in_array($member->role, ['pengurus_bpppmnu', 'tenaga_pendidik'], true), 404);
        abort_if($member->bpppmnuAttendances()->exists(), 403, 'Akun tidak dapat dihapus karena sudah memiliki riwayat presensi.');
        DB::transaction(function () use ($member) {
            $member->bpppmnuInvitations()->delete();
            if ($member->role === 'pengurus_bpppmnu') {
                $member->delete();
            } else {
                $member->update(['is_active' => false, 'is_bpppmnu_member' => false]);
            }
        });
        return back()->with('success', $member->role === 'pengurus_bpppmnu' ? 'Data pengurus berhasil dihapus.' : 'Akun tenaga pendidik dinonaktifkan dari agenda BPPPMNU.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120']);
        $import = new BpppmnuMembersImport;
        Excel::import($import, $request->file('file'));
        $message = "Import selesai. Ditambahkan: {$import->created}, diperbarui: {$import->updated}.";
        if ($import->skipped) $message .= ' Dilewati: '.implode(', ', $import->skipped).'.';
        return back()->with('success', $message);
    }

    private function save(Request $request, User $member)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'ketugasan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'instansi_asal' => 'nullable|string|max:255',
            'bpppmnu_jabatan' => 'nullable|string|max:255',
            'bpppmnu_instansi_asal' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'password' => [$member->exists ? 'nullable' : 'required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/', 'confirmed'],
        ]);
        if (empty($data['ketugasan']) && ! empty($data['jabatan'])) {
            $data['ketugasan'] = $data['jabatan'];
        }
        if (! array_key_exists('bpppmnu_jabatan', $data)) $data['bpppmnu_jabatan'] = $data['jabatan'] ?? null;
        if (! array_key_exists('bpppmnu_instansi_asal', $data)) $data['bpppmnu_instansi_asal'] = $data['instansi_asal'] ?? null;
        $action = $member->exists ? 'updated' : 'created';
        $reset = ! empty($data['password']);
        if ($reset) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        DB::transaction(function () use ($member, $data, $reset) {
            $member->fill($data);
            if (! $member->exists) {
                $member->role = 'pengurus_bpppmnu';
            }
            $member->is_bpppmnu_member = true;
            $member->save();
            if ($reset || ! $member->is_active) {
                $member->tokens()->delete();
            }
        });
        Log::info('BPPPMNU account '.$action, ['actor_id' => $request->user()->id, 'user_id' => $member->id, 'password_reset' => $reset, 'active' => $member->is_active]);

        return back()->with('success', 'Data pengurus berhasil disimpan.');
    }
}
