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

        // Check if user has pending izin that hasn't been approved yet (both in presensis and izins tables)
        $pendingIzinPresensi = Presensi::where('user_id', $user->id)
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->first();

        $pendingIzinTable = \App\Models\Izin::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingIzinPresensi || $pendingIzinTable) {
            $msg = 'Anda masih memiliki pengajuan izin yang belum disetujui. Harap tunggu persetujuan kepala sekolah terlebih dahulu.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

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

        switch ($type) {
            case 'sakit':
                $request->validate([
                    'tanggal' => 'required|date',
                    'keterangan' => 'required|string',
                    'surat_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('keterangan');
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

                $keterangan = $request->input('alasan');
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

                $keterangan = $request->input('alasan') . ' (Waktu masuk: ' . $request->input('waktu_masuk') . ')';
                if ($request->hasFile('file_izin')) {
                    $filePath = $this->uploadSuratIzin($request->file('file_izin'));
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
                    $filePath = $this->uploadSuratIzin($request->file('file_tugas'));
                }

                // For tugas_luar, use the separate izins table instead of presensis to avoid unique constraint violation
                $izin = \App\Models\Izin::create([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                    'type' => 'tugas_luar',
                    'deskripsi_tugas' => $request->input('deskripsi_tugas'),
                    'lokasi_tugas' => $request->input('lokasi_tugas'),
                    'waktu_masuk' => $request->input('waktu_masuk'),
                    'waktu_keluar' => $request->input('waktu_keluar'),
                    'file_path' => $filePath,
                    'status' => 'pending',
                ]);

                // Notify user
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'type' => 'izin_submitted',
                    'title' => 'Izin Tugas Luar Diajukan',
                    'message' => 'Pengajuan izin tugas luar Anda telah dikirim dan menunggu persetujuan.',
                    'data' => [
                        'izin_id' => $izin->id,
                        'tanggal' => $tanggal,
                        'type' => $type,
                    ]
                ]);

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Izin tugas luar berhasil diajukan dan menunggu persetujuan.',
                        'type' => $type,
                        'tanggal' => $tanggal
                    ]);
                }

                return redirect()->route('mobile.riwayat-presensi')->with('success', 'Izin tugas luar berhasil diajukan dan menunggu persetujuan.');
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
                    $filePath = $this->uploadSuratIzin($request->file('file_izin'));
                }
                break;

            default:
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Tipe izin tidak dikenali.'], 422);
                }
                return redirect()->back()->with('error', 'Tipe izin tidak dikenali.');
        }

        // Create Presensi record with status 'izin' (only for non-tugas_luar types)
        if ($type !== 'tugas_luar') {
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'status' => 'izin',
                'keterangan' => $keterangan,
                'surat_izin_path' => $filePath,
                'status_izin' => 'pending',
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

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
                return response()->json([
                    'success' => true,
                    'message' => 'Izin berhasil diajukan dan menunggu persetujuan.',
                    'type' => $type,
                    'tanggal' => $tanggal
                ]);
            }

            return redirect()->route('mobile.riwayat-presensi')->with('success', 'Izin berhasil diajukan dan menunggu persetujuan.');
        }
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
            // Get izin requests from both presensis and izins tables
            $presensiIzinQuery = Presensi::with('user')
                ->where('status', 'izin')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                });

            $izinTableQuery = \App\Models\Izin::with('user')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                });

            if ($status !== 'all') {
                $presensiIzinQuery->where('status_izin', $status);
                $izinTableQuery->where('status', $status);
            }

            $presensiIzinRequests = $presensiIzinQuery->get()->map(function ($item) {
                $item->model_type = 'presensi';
                return $item;
            });
            $izinTableRequests = $izinTableQuery->get()->map(function ($item) {
                $item->model_type = 'izin';
                return $item;
            });

            // Combine and sort by tanggal desc
            $combinedRequests = $presensiIzinRequests->concat($izinTableRequests)->sortByDesc('tanggal');

            // Manual pagination for combined collection
            $perPage = 10;
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
            $items = $combinedRequests->forPage($currentPage, $perPage);
            $izinRequests = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $combinedRequests->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Regular tenaga_pendidik: show only their own izin requests
        if ($user->role === 'tenaga_pendidik') {
            // Get izin requests from both tables
            $presensiIzinQuery = Presensi::with('user')
                ->where('user_id', $user->id)
                ->where('status', 'izin');

            $izinTableQuery = \App\Models\Izin::with('user')
                ->where('user_id', $user->id);

            if ($status !== 'all') {
                $presensiIzinQuery->where('status_izin', $status);
                $izinTableQuery->where('status', $status);
            }

            $presensiIzinRequests = $presensiIzinQuery->get();
            $izinTableRequests = $izinTableQuery->get();

            // Combine and sort by tanggal desc
            $combinedRequests = $presensiIzinRequests->concat($izinTableRequests)->sortByDesc('tanggal');

            // Manual pagination for combined collection
            $perPage = 10;
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
            $items = $combinedRequests->forPage($currentPage, $perPage);
            $izinRequests = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $combinedRequests->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Fallback: unauthorized for other roles
        abort(403, 'Unauthorized.');
    }

    /**
     * Approve the izin request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin', 'pengurus', 'tenaga_pendidik'])) {
            abort(403, 'Unauthorized');
        }

        // Try to find the izin in Izin or Presensi table
        $izin = \App\Models\Izin::where('id', $id)->first();

        if ($izin && $izin->type === 'tugas_luar') {
            $izin->status = 'approved';
            $izin->save();
        } else {
            $presensi = \App\Models\Presensi::where('id', $id)->where('status', 'izin')->first();

            if ($presensi) {
                $presensi->status_izin = 'approved';
                $presensi->save();
            } else {
                return redirect()->back()->with('error', 'Pengajuan izin tidak ditemukan.');
            }
        }

        // Optionally create notification about approval here

        return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Reject the izin request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin', 'pengurus', 'tenaga_pendidik'])) {
            abort(403, 'Unauthorized');
        }

        // Try to find the izin in Presensi or Izin table
        $presensi = \App\Models\Presensi::where('id', $id)->where('status', 'izin')->first();
        $izin = \App\Models\Izin::where('id', $id)->first();

        if ($presensi) {
            $presensi->status_izin = 'rejected';
            $presensi->save();
        } elseif ($izin) {
            $izin->status = 'rejected';
            $izin->save();
        } else {
            return redirect()->back()->with('error', 'Pengajuan izin tidak ditemukan.');
        }

        // Optionally create notification about rejection here

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}
