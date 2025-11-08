<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Madrasah;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get presensi data based on user role
        if (in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
            // Admin can see all presensi
            $presensis = Presensi::with(['user', 'madrasah'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Regular users see only their own presensi
            $presensis = Presensi::where('user_id', $user->id)
                ->with(['user', 'madrasah'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('presensi.index', compact('presensis'));
    }

    public function create()
    {
        $user = Auth::user();
        $madrasahs = Madrasah::all();

        return view('presensi.create', compact('madrasahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'lokasi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'madrasah_id' => 'required|exists:madrasahs,id',
            'keterangan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if presensi already exists for this date and user
        $existingPresensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingPresensi) {
            return redirect()->back()->with('error', 'Presensi untuk tanggal ini sudah ada.');
        }

        Presensi::create([
            'user_id' => $user->id,
            'madrasah_id' => $request->madrasah_id,
            'tanggal' => $request->tanggal,
            'waktu_masuk' => $request->waktu_masuk,
            'waktu_keluar' => $request->waktu_keluar,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => $request->keterangan,
            'status' => 'hadir',
        ]);

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil dicatat.');
    }

    public function laporan(Request $request)
    {
        $user = Auth::user();

        $query = Presensi::with(['user', 'madrasah']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter by madrasah
        if ($request->filled('madrasah_id')) {
            $query->where('madrasah_id', $request->madrasah_id);
        }

        // Filter by user role
        if (!in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
            $query->where('user_id', $user->id);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->paginate(50);
        $madrasahs = Madrasah::all();

        return view('presensi.laporan', compact('presensis', 'madrasahs'));
    }
}
