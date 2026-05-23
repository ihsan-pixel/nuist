<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use App\Services\ApprovedIzinSyncService;
use App\Services\ExternalTeachingPermissionService;
use App\Services\UserPushNotificationService;
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
        app(UserPushNotificationService::class)->notifyUser(
            $user,
            'izin_submitted',
            'Izin Diajukan',
            'Pengajuan izin Anda telah dikirim dan sedang menunggu persetujuan.',
            [
                'tanggal' => $tanggal,
                'keterangan' => $request->input('keterangan'),
                'url' => route('mobile.kelola-izin'),
            ]
        );

        return redirect()->route('presensi.index')->with('success', 'Surat izin berhasil diunggah dan menunggu persetujuan.');
    }

    public function index()
    {
        $user = Auth::user();

        // Get izin requests from both presensis and izins tables
        $presensiQuery = Presensi::where('status', 'izin')->with('user');
        $izinQuery = \App\Models\Izin::with('user');

        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            $presensiQuery->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
            $izinQuery->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
        }

        $presensiIzinRequests = $presensiQuery->get();
        $izinTableRequests = $izinQuery->get();

        // Combine and sort by tanggal desc
        $izinRequests = $presensiIzinRequests->concat($izinTableRequests)->sortByDesc('tanggal');

        return view('izin.index', compact('izinRequests'));
    }

    public function approve($id)
    {
        // Check if this is a presensi record or izin record
        $presensi = Presensi::find($id);
        $izin = \App\Models\Izin::find($id);

        if ($presensi) {
            // Handle presensi-based izin approval
            $this->authorize('approve', $presensi);

            // For terlambat in presensis table, delete the record to keep data only in izins
            if (str_contains(strtolower($presensi->keterangan), 'terlambat')) {
                $presensi->delete();

                // Create notification for deletion
                app(UserPushNotificationService::class)->notifyUserId(
                    $presensi->user_id,
                    'izin_rejected',
                    'Izin Ditolak',
                    'Pengajuan izin terlambat Anda pada tanggal ' . $presensi->tanggal->format('d F Y') . ' telah dihapus. Silakan ajukan ulang melalui aplikasi mobile.',
                    [
                        'tanggal' => $presensi->tanggal->format('Y-m-d'),
                        'rejected_by' => Auth::user()->name,
                        'url' => route('mobile.kelola-izin', ['status' => 'rejected']),
                    ]
                );
            } else {
                $presensi->update([
                    'status_izin' => 'approved',
                    'approved_by' => Auth::id(),
                ]);

                // If this is a tugas_luar approval from presensis table, auto-fill waktu_keluar
                if (str_contains($presensi->keterangan, 'Lokasi:') && str_contains($presensi->keterangan, 'Waktu:')) {
                    // Find existing presensi record for the same date and user with status 'hadir'
                    $existingPresensi = Presensi::where('user_id', $presensi->user_id)
                        ->where('tanggal', $presensi->tanggal)
                        ->where('status', 'hadir')
                        ->first();

                    if ($existingPresensi && !$existingPresensi->waktu_keluar) {
                        // Extract waktu_keluar from izin keterangan (format: "deskripsi\nLokasi: lokasi\nWaktu: masuk - keluar")
                        $lines = explode('\n', $presensi->keterangan);
                        $waktuLine = collect($lines)->first(function ($line) {
                            return str_starts_with($line, 'Waktu:');
                        });

                        if ($waktuLine) {
                            $waktuParts = explode(' - ', str_replace('Waktu: ', '', $waktuLine));
                            if (count($waktuParts) === 2) {
                                $waktuKeluar = trim($waktuParts[1]);
                                // Update existing presensi with waktu_keluar
                                $existingPresensi->update([
                                    'waktu_keluar' => $presensi->tanggal . ' ' . $waktuKeluar . ':00',
                                ]);
                            }
                        }
                    }
                }
            }
        } elseif ($izin) {
            // Handle izin table approval
            $izin->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            if ($izin->type === ExternalTeachingPermissionService::TYPE) {
                ExternalTeachingPermissionService::syncApprovedNoPresencePresensi($izin, now('Asia/Jakarta'));
            } elseif ($izin->type !== 'terlambat') {
                ApprovedIzinSyncService::syncApprovedIzinPresensi($izin);
            }
        } else {
            abort(404, 'Izin request not found.');
        }

        // If this is a cuti approval, create presensi records for each day in the range
        if ($presensi && str_contains($presensi->keterangan, 'Tanggal:')) {
            // Extract date range from keterangan (format: "alasan\nTanggal: start sampai end")
            $lines = explode('\n', $presensi->keterangan);
            $tanggalLine = collect($lines)->first(function ($line) {
                return str_starts_with($line, 'Tanggal:');
            });

            if ($tanggalLine) {
                $dates = str_replace('Tanggal: ', '', $tanggalLine);
                $dateParts = explode(' sampai ', $dates);

                if (count($dateParts) === 2) {
                    $startDate = trim($dateParts[0]);
                    $endDate = trim($dateParts[1]);

                    $start = \Carbon\Carbon::parse($startDate);
                    $end = \Carbon\Carbon::parse($endDate);

                    // Create presensi records for each day in the range (except the original record date)
                    $current = $start->copy();
                    while ($current->lte($end)) {
                        if ($current->format('Y-m-d') !== $presensi->tanggal) {
                            // Check if presensi already exists for this date
                            $existing = Presensi::where('user_id', $presensi->user_id)
                                ->where('tanggal', $current->format('Y-m-d'))
                                ->first();

                            if (!$existing) {
                                Presensi::create([
                                    'user_id' => $presensi->user_id,
                                    'tanggal' => $current->format('Y-m-d'),
                                    'status' => 'izin',
                                    'keterangan' => 'Cuti - ' . $startDate . ' sampai ' . $endDate,
                                    'surat_izin_path' => $presensi->surat_izin_path,
                                    'status_izin' => 'approved',
                                    'status_kepegawaian_id' => $presensi->status_kepegawaian_id,
                                    'approved_by' => Auth::id(),
                                ]);
                            }
                        }
                        $current->addDay();
                    }
                }
            }
        }

        // Create notification for user about approval
        if ($presensi) {
            app(UserPushNotificationService::class)->notifyUserId(
                $presensi->user_id,
                'izin_approved',
                'Izin Disetujui',
                'Pengajuan izin Anda pada tanggal ' . $presensi->tanggal->format('d F Y') . ' telah disetujui.',
                [
                    'presensi_id' => $presensi->id,
                    'tanggal' => $presensi->tanggal->format('Y-m-d'),
                    'approved_by' => Auth::user()->name,
                    'url' => route('mobile.kelola-izin', ['status' => 'approved']),
                ]
            );
        } elseif ($izin) {
            app(UserPushNotificationService::class)->notifyUserId(
                $izin->user_id,
                'izin_approved',
                'Izin Disetujui',
                'Pengajuan izin Anda pada tanggal ' . $izin->tanggal->format('d F Y') . ' telah disetujui.',
                [
                    'izin_id' => $izin->id,
                    'tanggal' => $izin->tanggal->format('Y-m-d'),
                    'approved_by' => Auth::user()->name,
                    'url' => route('mobile.kelola-izin', ['status' => 'approved']),
                ]
            );
        }

        return redirect()->route('mobile.kelola-izin')->with('success', 'Izin disetujui.');
    }

    public function reject($id)
    {
        // Check if this is a presensi record or izin record
        $presensi = Presensi::find($id);
        $izin = \App\Models\Izin::find($id);

        if ($presensi) {
            // Handle presensi-based izin rejection
            $this->authorize('reject', $presensi);

            $presensi->update([
                'status_izin' => 'rejected',
                'status' => 'alpha',
                'approved_by' => Auth::id(),
            ]);

            // Create notification for user about rejection
            app(UserPushNotificationService::class)->notifyUserId(
                $presensi->user_id,
                'izin_rejected',
                'Izin Ditolak',
                'Pengajuan izin Anda pada tanggal ' . $presensi->tanggal->format('d F Y') . ' telah ditolak.',
                [
                    'presensi_id' => $presensi->id,
                    'tanggal' => $presensi->tanggal->format('Y-m-d'),
                    'rejected_by' => Auth::user()->name,
                    'url' => route('mobile.kelola-izin', ['status' => 'rejected']),
                ]
            );
        } elseif ($izin) {
            // Handle izin table rejection
            $izin->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Create notification for user about rejection
            app(UserPushNotificationService::class)->notifyUserId(
                $izin->user_id,
                'izin_rejected',
                'Izin Ditolak',
                'Pengajuan izin Anda pada tanggal ' . $izin->tanggal->format('d F Y') . ' telah ditolak.',
                [
                    'izin_id' => $izin->id,
                    'tanggal' => $izin->tanggal->format('Y-m-d'),
                    'rejected_by' => Auth::user()->name,
                    'url' => route('mobile.kelola-izin', ['status' => 'rejected']),
                ]
            );
        } else {
            abort(404, 'Izin request not found.');
        }

        return redirect()->route('mobile.kelola-izin')->with('success', 'Izin ditolak.');
    }
}
