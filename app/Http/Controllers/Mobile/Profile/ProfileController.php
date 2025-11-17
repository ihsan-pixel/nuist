<?php

namespace App\Http\Controllers\Mobile\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends \App\Http\Controllers\Controller
{
    // Profile and account management stubs
    public function profile()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // pass the authenticated user to the view so blade can reference $user
        return view('mobile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_hp = $request->input('phone');
        $user->tempat_lahir = $request->input('tempat_lahir');
        $user->tanggal_lahir = $request->input('tanggal_lahir') ?: null;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $_SERVER['DOCUMENT_ROOT'];
            $uploadDir = $path . '/storage/avatars/';

            // Ensure the directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // delete old avatar if exists
            if ($user->avatar && file_exists($path . '/' . $user->avatar)) {
                unlink($path . '/' . $user->avatar);
            }

            // Generate unique filename
            $filename = time() . '_' . $user->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($uploadDir, $filename);

            $user->avatar = 'uploads/' . $filename;
            $user->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui', 'avatar' => $user->avatar]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Password lama tidak sesuai'], 422);
            }
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->password = Hash::make($validated['password']);
        // mark that the user has changed the password if such column exists
        if (isset($user->password_changed)) {
            $user->password_changed = true;
        }
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }

    // Account change
    public function ubahAkun()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        return view('mobile.ubah-akun', compact('user'));
    }
}
