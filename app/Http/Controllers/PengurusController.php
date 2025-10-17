<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PengurusController extends Controller
{
    public function index()
    {
        // Always fetch only users with role 'pengurus'
        $penguruses = User::where('role', 'pengurus')->get();

        return view('masterdata.pengurus.index', compact('penguruses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'jabatan'  => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'pengurus',
            ];

            // Only add jabatan if column exists
            if (Schema::hasColumn('users', 'jabatan')) {
                $userData['jabatan'] = $validated['jabatan'];
            }

            User::create($userData);

            DB::commit();
            return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pengurus: ' . $e->getMessage());
            return redirect()->route('pengurus.index')->with('error', 'Gagal menambahkan pengurus: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $pengurus = User::where('role', 'pengurus')->findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $pengurus->id,
            'jabatan'  => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            $pengurus->name     = $validated['name'];
            $pengurus->email    = $validated['email'];

            // Only update jabatan if column exists
            if (Schema::hasColumn('users', 'jabatan')) {
                $pengurus->jabatan = $validated['jabatan'];
            }

            if (!empty($validated['password'])) {
                $pengurus->password = Hash::make($validated['password']);
            }

            $pengurus->save();

            DB::commit();
            return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pengurus: ' . $e->getMessage());
            return redirect()->route('pengurus.index')->with('error', 'Gagal memperbarui pengurus: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pengurus = User::where('role', 'pengurus')->findOrFail($id);
        $pengurus->delete();

        return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil dihapus.');
    }
}
