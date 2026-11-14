<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', 'karyawan');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('admin.karyawan.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:150'],
            'instansi_asal' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['role'] = 'karyawan';
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'karyawan', 404);
        $user->update(['is_active' => false]);

        return back()->with('success', 'Akun karyawan berhasil dinonaktifkan.');
    }
}
