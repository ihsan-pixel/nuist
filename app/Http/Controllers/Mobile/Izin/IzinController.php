<?php

namespace App\Http\Controllers\Mobile\Izin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;

class IzinController extends \App\Http\Controllers\Controller
{
    private function uploadSuratIzin($file)
    {
        // Path to public_html/storage/surat_izin using DOCUMENT_ROOT for production compatibility
        $path = $_SERVER['DOCUMENT_ROOT'] . '/storage/surat_izin';

        // Pastikan folder sudah ada
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Nama file unik
        $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Pindahkan file ke path
        $file->move($path, $namaFile);

        // Return path yang disimpan ke database
        return 'surat_izin/' . $namaFile;
    }

    public function storeIzin(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $type = $request->input('type');

        // Common: tanggal presence
        if (empty($type)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tipe izin tidak diketahui.'], 422);
            }
            return redirect()->back()->with('error', 'Tipe izin tidak diketahui.');
        }

        // Normalize type
        $type = strtolower($type);



        // Validate and map input per type
        $filePath = null;
        $keterangan = '';
        $tanggal = $request->input('tanggal');

        // Prevent duplicate presensi records on same date, except for tugas_luar which can be submitted even with existing presensi masuk
        if ($tanggal && $type !== 'tugas_luar') {
            $existing = Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
            if ($existing) {
                $msg = 'Anda sudah memiliki catatan kehadiran pada tanggal ini.';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        // Check for pending izin across all types
        $pendingIzin = \App\Models\Izin::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingIzin) {
            $msg = 'Anda masih memiliki pengajuan izin yang belum disetujui. Harap tunggu persetujuan kepala sekolah terlebih dahulu.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

        switch ($type) {
            case 'sakit':
                $request->validate([
                    'tanggal' => 'required|date',
                    'keterangan' => 'required|string',
                    'surat_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $alasan = $request->input('keterangan');
                if ($request->hasFile('surat_izin')) {
                    $filePath = $this->uploadSuratIzin($request->file('surat_izin'));
                }
                break;

            case 'tidak_masuk':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $alasan = $request->input('alasan');
                if ($request->hasFile('file_izin')) {
                    $filePath = $this->uploadSuratIzin($request->file('file_izin'));
                }
                break;

            case 'terlambat':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'waktu_masuk' => 'required',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $alasan = $request->input('alasan');
                $waktuMasuk = $request->input('waktu_masuk');
                if ($request->hasFile('file_izin')) {
                    $filePath = $this->uploadSuratIzin($request->file('file_izin'));
                }
                break;

            case 'tugas_luar':
                // Check for duplicate tugas_luar on the same date
                $existingTugasLuar = \App\Models\Izin::where('user_id', $user->id)
                    ->where('tanggal', $tanggal)
                    ->where('type', 'tugas_luar')
                    ->first();
                if ($existingTugasLuar) {
                    $msg = 'Anda sudah memiliki pengajuan izin tugas luar pada tanggal ini.';
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 400);
                    }
                    return redirect()->back()->with('error', $msg);
                }

                $request->validate([
                    'tanggal' => 'required|date',
                    'deskripsi_tugas' => 'required|string',
                    'lokasi_tugas' => 'required|string',
                    'waktu_masuk' => 'required',
                    'waktu_keluar' => 'required',
                    'file_tugas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $alasan = $request->input('deskripsi_tugas');
                $deskripsiTugas = $request->input('deskripsi_tugas');
                $lokasiTugas = $request->input('lokasi_tugas');
                $waktuMasuk = $request->input('waktu_masuk');
                $waktuKeluar = $request->input('waktu_keluar');
                if ($request->hasFile('file_tugas')) {
                    $filePath = $this->uploadSuratIzin($request->file('file_tugas'));
                }
                break;

            case 'cuti':
                $request->validate([
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'alasan' => 'required|string',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $tanggal = $request->input('tanggal_mulai');
                $alasan = $request->input('alasan') . ' (Tanggal: ' . $request->input('tanggal_mulai') . ' sampai ' . $request->input('tanggal_selesai') . ')';
                $tanggalSelesai = $request->input('tanggal_selesai');
                if ($request->hasFile('file_izin')) {
                    $filePath = $this->uploadSuratIzin($request->file('file_izin'));
                }
                break;

            default:
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Tipe izin tidak dikenali.'], 422);
                }
                return redirect()->back()->with('error', 'Tipe izin tidak dikenali.');
        }

        // Create Izin record for all types
        $izinData = [
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'type' => $type,
            'alasan' => $alasan ?? '',
            'file_path' => $filePath,
            'status' => 'pending',
        ];

        if (isset($deskripsiTugas)) $izinData['deskripsi_tugas'] = $deskripsiTugas;
        if (isset($lokasiTugas)) $izinData['lokasi_tugas'] = $lokasiTugas;
        if (isset($waktuMasuk)) $izinData['waktu_masuk'] = $waktuMasuk;
        if (isset($waktuKeluar)) $izinData['waktu_keluar'] = $waktuKeluar;
        if (isset($tanggalSelesai)) $izinData['tanggal_selesai'] = $tanggalSelesai; // Assuming we add this field or store in alasan

        $izin = \App\Models\Izin::create($izinData);

        // Notify user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'izin_submitted',
            'title' => 'Izin Diajukan',
            'message' => 'Pengajuan izin Anda telah dikirim dan menunggu persetujuan.',
            'data' => [
                'izin_id' => $izin->id,
                'tanggal' => $tanggal,
                'type' => $type,
            ]
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil diajukan dan menunggu persetujuan.',
                'type' => $type,
                'tanggal' => $tanggal
            ]);
        }

        return redirect()->route('mobile.riwayat-presensi')->with('success', 'Izin berhasil diajukan dan menunggu persetujuan.');
    }

    public function izin()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $type = request('type');

        // If no specific type requested, show menu
        if (!$type) {
            return view('mobile.izin');
        }

        // Normalize
        $type = strtolower($type);

        switch ($type) {
            case 'sakit':
                return view('mobile.izin-sakit');
            case 'terlambat':
                return view('mobile.izin-terlambat');
            case 'tidak_masuk':
            case 'tidak-masuk':
                return view('mobile.izin-tidak-masuk');
            case 'tugas_luar':
            case 'tugas-luar':
                return view('mobile.izin-tugas-luar');
            case 'cuti':
                return view('mobile.izin-cuti');
            default:
                // Unknown type -> show menu with flash
                return redirect()->route('mobile.izin')->with('error', 'Tipe izin tidak dikenali.');
        }
    }

    public function kelolaIzin()
    {
        $user = Auth::user();

        $status = request('status', 'pending');

        // Kepala madrasah: show requests for the whole madrasah
        if ((($user->role === 'tenaga_pendidik') || ($user->role === 'admin')) && $user->ketugasan === 'kepala madrasah/sekolah') {
            // Get izin requests from izins table only
            $izinRequests = \App\Models\Izin::with('user')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                });

            if ($status !== 'all') {
                $izinRequests->where('status', $status);
            }

            $izinRequests = $izinRequests->orderBy('tanggal', 'desc')->paginate(10);

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Regular tenaga_pendidik: show only their own izin requests
        if ($user->role === 'tenaga_pendidik') {
            // Get izin requests from izins table only
            $izinRequests = \App\Models\Izin::with('user')
                ->where('user_id', $user->id);

            if ($status !== 'all') {
                $izinRequests->where('status', $status);
            }

            $izinRequests = $izinRequests->orderBy('tanggal', 'desc')->paginate(10);

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Fallback: unauthorized for other roles
        abort(403, 'Unauthorized.');
    }



    /**
     * Approve Izin model (tugas_luar) request.

     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Izin $izin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveIzinModel(Request $request, \App\Models\Izin $izin)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin', 'pengurus', 'tenaga_pendidik'])) {
            abort(403, 'Unauthorized');
        }

        $izin->status = 'approved';
        $izin->approved_by = $user->id;
        $izin->approved_at = now();
        $izin->save();

        // Auto-fill presensi record for all approved izin types
        $existingPresensi = Presensi::where('user_id', $izin->user_id)
            ->where('tanggal', $izin->tanggal)
            ->first();

        if ($existingPresensi) {
            // If presensi exists, update waktu_keluar if not set, status remains unchanged
            if (!$existingPresensi->waktu_keluar) {
                $existingPresensi->update([
                    'waktu_keluar' => $izin->waktu_keluar,
                    'status_izin' => 'approved',
                ]);
            }
        } else {
            // If no presensi exists, create new presensi with izin
            Presensi::create([
                'user_id' => $izin->user_id,
                'madrasah_id' => $izin->user->madrasah_id,
                'tanggal' => $izin->tanggal,
                'waktu_masuk' => $izin->waktu_masuk,
                'waktu_keluar' => $izin->waktu_keluar,
                'status' => 'izin',
                'keterangan' => $izin->alasan,
                'status_izin' => 'approved',
                'status_kepegawaian_id' => $izin->user->status_kepegawaian_id,
                'approved_by' => $user->id,
                'surat_izin_path' => $izin->file_path,
            ]);
        }

        // Create notification for user about approval
        \App\Models\Notification::create([
            'user_id' => $izin->user_id,
            'type' => 'izin_approved',
            'title' => 'Izin Disetujui',
            'message' => 'Pengajuan izin Anda pada tanggal ' . $izin->tanggal->format('d F Y') . ' telah disetujui.',
            'data' => [
                'izin_id' => $izin->id,
                'tanggal' => $izin->tanggal,
                'approved_by' => $user->name
            ]
        ]);

        return redirect()->back()->with('success', 'Izin berhasil disetujui.');
    }

    /**
     * Reject Izin model request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Izin $izin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectIzinModel(Request $request, \App\Models\Izin $izin)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin', 'pengurus', 'tenaga_pendidik'])) {
            abort(403, 'Unauthorized');
        }

        $izin->status = 'rejected';
        $izin->approved_by = $user->id;
        $izin->approved_at = now();
        $izin->save();

        // Create notification for user about rejection
        \App\Models\Notification::create([
            'user_id' => $izin->user_id,
            'type' => 'izin_rejected',
            'title' => 'Izin Ditolak',
            'message' => 'Pengajuan izin Anda pada tanggal ' . $izin->tanggal->format('d F Y') . ' telah ditolak.',
            'data' => [
                'izin_id' => $izin->id,
                'tanggal' => $izin->tanggal,
                'rejected_by' => $user->name
            ]
        ]);

        return redirect()->back()->with('success', 'Izin berhasil ditolak.');
    }
}
