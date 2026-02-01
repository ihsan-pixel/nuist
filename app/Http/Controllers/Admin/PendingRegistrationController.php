<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationApprovedNotification;

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

        // Generate an 8-character password with mixed letters and numbers
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $plainPassword = '';
        for ($i = 0; $i < 8; $i++) {
            $plainPassword .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Create the user with the new password
        $user = User::create([
            'name' => $pendingRegistration->name,
            'email' => $pendingRegistration->email,
            'password' => Hash::make($plainPassword),
            'role' => $pendingRegistration->role,
            'ketugasan' => $pendingRegistration->jabatan,
            'madrasah_id' => $pendingRegistration->asal_sekolah,
        ]);

        // Send approval email with the plain password
        try {
            Mail::to($user->email)->send(new RegistrationApprovedNotification($user, $plainPassword));
        } catch (\Exception $e) {
            // Log the error but don't fail the approval process
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        // Delete the pending registration
        $pendingRegistration->delete();

        return redirect()->route('admin.pending-registrations.index')
            ->with('success', 'Registration approved successfully. An email with login credentials has been sent to the user.');
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
