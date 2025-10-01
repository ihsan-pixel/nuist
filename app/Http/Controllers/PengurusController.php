<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            'jabatan'  => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'pengurus',
            'jabatan' => $validated['jabatan'],
        ]);

        return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pengurus = User::where('role', 'pengurus')->findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $pengurus->id,
            'jabatan'  => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        $pengurus->name     = $validated['name'];
        $pengurus->email    = $validated['email'];
        $pengurus->jabatan = $validated['jabatan'];

        if (!empty($validated['password'])) {
            $pengurus->password = Hash::make($validated['password']);
        }

        $pengurus->save();

        return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengurus = User::where('role', 'pengurus')->findOrFail($id);
        $pengurus->delete();

        return redirect()->route('pengurus.index')->with('success', 'Pengurus berhasil dihapus.');
    }
}
