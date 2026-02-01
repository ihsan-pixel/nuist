<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PendingRegistrationController extends Controller
{
    /**
     * Display a listing of pending registrations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pendingRegistrations = PendingRegistration::where('status', 'pending')
            ->with('madrasah')
            ->orderBy('submitted_at', 'desc')
            ->paginate(15);

        return view('admin.pending-registrations.index', compact('pendingRegistrations'));
    }

    /**
     * Approve a pending registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $pendingRegistration = PendingRegistration::findOrFail($id);

        // Create the user
        User::create([
            'name' => $pendingRegistration->name,
            'email' => $pendingRegistration->email,
            'password' => $pendingRegistration->password, // Already hashed
            'role' => $pendingRegistration->role,
            'jabatan' => $pendingRegistration->jabatan,
            'asal_sekolah' => $pendingRegistration->asal_sekolah,
        ]);

        // Delete the pending registration
        $pendingRegistration->delete();

        return redirect()->route('admin.pending-registrations.index')
            ->with('success', 'Registration approved successfully.');
    }

    /**
     * Reject a pending registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $pendingRegistration = PendingRegistration::findOrFail($id);

        // Delete the pending registration
        $pendingRegistration->delete();

        return redirect()->route('admin.pending-registrations.index')
            ->with('success', 'Registration rejected successfully.');
    }
}
