<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdminImport;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $admins = User::where('role', 'admin')
                ->where('madrasah_id', $user->madrasah_id)
                ->with('madrasah')
                ->get();
        } elseif ($user->role === 'pengurus' || $user->role === 'super_admin') {
            $admins = User::where('role', 'admin')->with('madrasah')->get();
        } else {
            abort(403, 'Unauthorized access');
        }
        $madrasahs = Madrasah::all(); // ambil semua madrasah
        return view('masterdata.admin.index', compact('admins', 'madrasahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|string|email|max:255|unique:users,email',
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'madrasah_id'  => 'nullable|exists:madrasahs,id',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            try {
                \Log::info('Avatar file received in store method.');
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                \Log::info('Avatar stored at: ' . $avatarPath);
            } catch (\Exception $e) {
                \Log::error('Avatar upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Avatar upload failed: ' . $e->getMessage());
            }
        }

        User::create([
            'name'        => $validated['name'] ?? null,
            'email'       => $validated['email'] ?? null,
            'password'    => !empty($validated['password']) ? Hash::make($validated['password']) : Hash::make('password123'),
            'role'        => 'admin',
            'avatar'      => $avatarPath,
            'madrasah_id' => $validated['madrasah_id'] ?? null,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|string|email|max:255|unique:users,email,' . $admin->id,
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'madrasah_id'  => 'nullable|exists:madrasahs,id',
        ]);

        if ($request->hasFile('avatar')) {
            try {
                if ($admin->avatar) \Storage::disk('public')->delete($admin->avatar);
                $admin->avatar = $request->file('avatar')->store('avatars', 'public');
            } catch (\Exception $e) {
                \Log::error('Avatar upload failed: ' . $e->getMessage());
            }
        }

        $admin->name        = $validated['name'] ?? $admin->name;
        $admin->email       = $validated['email'] ?? $admin->email;
        $admin->madrasah_id = $validated['madrasah_id'] ?? $admin->madrasah_id;

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        if ($admin->avatar) {
            \Storage::disk('public')->delete($admin->avatar);
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AdminImport, $request->file('file'));
            return redirect()->route('admin.index')->with('success', 'Data admin berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
