<?php

namespace App\Http\Controllers\Mobile\Profile;

use App\Models\MgmpMember;
use App\Models\MgmpReport;
use Carbon\Carbon;
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

        // Prepare user info array expected by the view (same as dashboard)
        $userInfo = [
            'nuist_id' => $user->nuist_id ?? '-',
            'status_kepegawaian' => $user->statusKepegawaian?->name ?? '-',
            'ketugasan' => $user->ketugasan ?? '-',
            'tempat_lahir' => $user->tempat_lahir ?? '-',
            'tanggal_lahir' => $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-',
            'tmt' => $user->tmt ? \Carbon\Carbon::parse($user->tmt)->format('d-m-Y') : '-',
            'nuptk' => $user->nuptk ?? '-',
            'npk' => $user->npk ?? '-',
            'kartanu' => $user->kartanu ?? '-',
            'nip' => $user->nip ?? '-',
            'pendidikan_terakhir' => $user->pendidikan_terakhir ?? '-',
            'program_studi' => $user->program_studi ?? '-',
        ];

        $mgmpMemberships = MgmpMember::with('mgmpGroup')
            ->where('user_id', $user->id)
            ->get();
        $mgmpGroupIds = $mgmpMemberships->pluck('mgmp_group_id')->filter()->unique()->values();
        $now = Carbon::now('Asia/Jakarta');

        $mgmpActivities = $mgmpGroupIds->isEmpty()
            ? collect()
            : MgmpReport::with([
                'mgmpGroup',
                'attendances' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
                ->whereIn('mgmp_group_id', $mgmpGroupIds)
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', '!=', 'cancelled');
                })
                ->whereDate('tanggal', '>=', $now->toDateString())
                ->orderBy('tanggal')
                ->orderBy('waktu_mulai')
                ->limit(6)
                ->get()
                ->map(function ($activity) use ($now) {
                    $date = $activity->tanggal ? Carbon::parse($activity->tanggal)->format('Y-m-d') : null;
                    $activity->starts_at = $date && $activity->waktu_mulai
                        ? Carbon::parse($date . ' ' . $activity->waktu_mulai, 'Asia/Jakarta')
                        : null;
                    $activity->ends_at = $date && $activity->waktu_selesai
                        ? Carbon::parse($date . ' ' . $activity->waktu_selesai, 'Asia/Jakarta')
                        : null;
                    $activity->user_attendance = $activity->attendances->first();
                    $activity->attendance_state = $activity->user_attendance
                        ? 'hadir'
                        : ($activity->starts_at && $activity->ends_at && $now->betweenIncluded($activity->starts_at, $activity->ends_at)
                            ? 'berlangsung'
                            : ($activity->starts_at && $now->lt($activity->starts_at) ? 'akan_datang' : 'selesai'));

                    return $activity;
                });

        // pass the authenticated user and userInfo to the view
        return view('mobile.profile', compact('user', 'userInfo', 'mgmpMemberships', 'mgmpActivities'));
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

            $user->avatar = 'avatars/' . $filename;
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
