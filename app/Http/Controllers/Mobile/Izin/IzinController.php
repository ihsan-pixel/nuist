<?php

namespace App\Http\Controllers\Mobile\Izin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;

class IzinController extends \App\Http\Controllers\Controller
{
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

        // Cek apakah tanggal sudah ada di presensi, kecuali jika type adalah 'tugas_luar'
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


        // Validate and map input per type
        $filePath = null;
        $keterangan = '';
        $tanggal = $request->input('tanggal');

        // Prevent duplicate presensi records on same date
        if ($tanggal) {
            $existing = Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
            if ($existing) {
                $msg = 'Anda sudah memiliki catatan kehadiran pada tanggal ini.';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        switch ($type) {
            case 'sakit':
                $request->validate([
                    'tanggal' => 'required|date',
                    'keterangan' => 'required|string',
                    'surat_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('keterangan');
                if ($request->hasFile('surat_izin')) {
                    $filePath = $request->file('surat_izin')->store('surat_izin', 'public');
                }
                break;

            case 'tidak_masuk':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('alasan');
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            case 'terlambat':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'waktu_masuk' => 'required',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('alasan') . ' (Waktu masuk: ' . $request->input('waktu_masuk') . ')';
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            case 'tugas_luar':
                $request->validate([
                    'tanggal' => 'required|date',
                    'deskripsi_tugas' => 'required|string',
                    'lokasi_tugas' => 'required|string',
                    'waktu_masuk' => 'required',
                    'waktu_keluar' => 'required',
                    'file_tugas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('deskripsi_tugas') . '\nLokasi: ' . $request->input('lokasi_tugas') . '\n' . 'Waktu: ' . $request->input('waktu_masuk') . ' - ' . $request->input('waktu_keluar');
                if ($request->hasFile('file_tugas')) {
                    $filePath = $request->file('file_tugas')->store('surat_izin', 'public');
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
                $keterangan = $request->input('alasan') . '\nTanggal: ' . $request->input('tanggal_mulai') . ' sampai ' . $request->input('tanggal_selesai');
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            default:
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Tipe izin tidak dikenali.'], 422);
                }
                return redirect()->back()->with('error', 'Tipe izin tidak dikenali.');
        }

        // Create Presensi record with status 'izin'
        $presensi = Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'status' => 'izin',
            'keterangan' => $keterangan,
            'surat_izin_path' => $filePath,
            'status_izin' => 'pending',
            'status_kepegawaian_id' => $user->status_kepegawaian_id,
        ]);

        // Jika tipe tugas luar, otomatis buat presensi pulang
        if ($type === 'tugas_luar') {
            // Misal presensi keluar otomatis tercatat dengan status 'pulang'
            Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'status' => 'pulang_otomatis',
                'keterangan' => 'Pulang otomatis dari tugas luar (' . $request->input('lokasi_tugas') . ')',
                'waktu_keluar' => $request->input('waktu_keluar'),
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);
        }


        // Notify user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'izin_submitted',
            'title' => 'Izin Diajukan',
            'message' => 'Pengajuan izin Anda telah dikirim dan menunggu persetujuan.',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $tanggal,
                'type' => $type,
            ]
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Izin berhasil diajukan dan menunggu persetujuan.']);
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
            // Only show presensi records that are izin submissions
            $izinQuery = Presensi::with('user')
                ->where('status', 'izin')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                })
                ->orderBy('tanggal', 'desc');

            if ($status !== 'all') {
                $izinQuery->where('status_izin', $status);
            }

            $izinRequests = $izinQuery->paginate(10);

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Regular tenaga_pendidik: show only their own izin requests
        if ($user->role === 'tenaga_pendidik') {
            // Regular tenaga_pendidik should only see their own izin submissions
            $izinQuery = Presensi::with('user')
                ->where('user_id', $user->id)
                ->where('status', 'izin')
                ->orderBy('tanggal', 'desc');

            if ($status !== 'all') {
                $izinQuery->where('status_izin', $status);
            }

            $izinRequests = $izinQuery->paginate(10);
            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Fallback: unauthorized for other roles
        abort(403, 'Unauthorized.');
    }
}
