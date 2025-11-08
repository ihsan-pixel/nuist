<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Madrasah;

class FaceEnrollmentController extends Controller
{
    // List users and show face enrollment status with filtering and pagination
    public function index(Request $request)
    {
        $query = User::with('madrasah')
            ->where('role', 'tenaga_pendidik');

        // Filter by madrasah
        if ($request->filled('madrasah_id')) {
            $query->where('madrasah_id', $request->madrasah_id);
        }

        // Filter by face registration status
        if ($request->filled('face_status')) {
            if ($request->face_status === 'registered') {
                $query->whereNotNull('face_registered_at');
            } elseif ($request->face_status === 'not_registered') {
                $query->whereNull('face_registered_at');
            }
        }

        $users = $query->orderBy('name')->paginate(20);
        $madrasahs = Madrasah::orderBy('nama')->get();

        return view('admin.face-enrollment-list', compact('users', 'madrasahs'));
    }

    // Show face enrollment form for a specific user
    public function show(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Ensure only admin or the user themselves can access (for security)
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.face-enrollment', compact('user'));
    }

    // Delete face data for a user
    public function destroy(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Only admin can delete face data
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        try {
            $user->update([
                'face_data' => null,
                'face_id' => null,
                'face_registered_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data wajah berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data wajah: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle face verification requirement for a user
    public function toggleVerification(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Only admin can toggle verification requirement
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $user->face_verification_required = !$user->face_verification_required;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status verifikasi wajah berhasil diubah',
            'face_verification_required' => $user->face_verification_required
        ]);
    }
}
