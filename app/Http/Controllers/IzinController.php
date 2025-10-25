<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'surat_izin' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $tanggal = $request->input('tanggal');

        // Check if there is already a presensi record for the user on the given date
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($presensi) {
            return redirect()->back()->with('error', 'Anda sudah memiliki catatan kehadiran pada tanggal ini.');
        }

        $filePath = $request->file('surat_izin')->store('surat_izin', 'public');

        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'status' => 'izin',
            'keterangan' => $request->input('keterangan'),
            'surat_izin_path' => $filePath,
            'status_izin' => 'pending',
            'status_kepegawaian_id' => $user->status_kepegawaian_id,
        ]);

        // Create notification for izin submission
        Notification::create([
            'user_id' => $user->id,
            'type' => 'izin_submitted',
            'title' => 'Izin Diajukan',
            'message' => 'Pengajuan izin Anda telah dikirim dan sedang menunggu persetujuan.',
            'data' => [
                'tanggal' => $tanggal,
                'keterangan' => $request->input('keterangan')
            ]
        ]);

        return redirect()->route('presensi.index')->with('success', 'Surat izin berhasil diunggah dan menunggu persetujuan.');
    }

    public function index()
    {
        $user = Auth::user();
        $query = Presensi::where('status', 'izin')->with('user');

        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
        }

        $izinRequests = $query->get();

        return view('izin.index', compact('izinRequests'));
    }

    public function approve(Presensi $presensi)
    {
        $this->authorize('approve', $presensi);

        $presensi->update([
            'status_izin' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Create notification for user about approval
        Notification::create([
            'user_id' => $presensi->user_id,
            'type' => 'izin_approved',
            'title' => 'Izin Disetujui',
            'message' => 'Pengajuan izin Anda pada tanggal ' . $presensi->tanggal->format('d F Y') . ' telah disetujui.',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $presensi->tanggal,
                'approved_by' => Auth::user()->name
            ]
        ]);

        return redirect()->route('izin.index')->with('success', 'Izin disetujui.');
    }

    public function reject(Presensi $presensi)
    {
        $this->authorize('reject', $presensi);

        $presensi->update([
            'status_izin' => 'rejected',
            'status' => 'alpha',
            'approved_by' => Auth::id(),
        ]);

        // Create notification for user about rejection
        Notification::create([
            'user_id' => $presensi->user_id,
            'type' => 'izin_rejected',
            'title' => 'Izin Ditolak',
            'message' => 'Pengajuan izin Anda pada tanggal ' . $presensi->tanggal->format('d F Y') . ' telah ditolak.',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $presensi->tanggal,
                'rejected_by' => Auth::user()->name
            ]
        ]);

        return redirect()->route('izin.index')->with('success', 'Izin ditolak.');
    }
}
