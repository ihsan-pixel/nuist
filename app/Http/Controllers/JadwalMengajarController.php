<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalMengajar;
use App\Models\TenagaPendidik;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     * Admin can see jadwal mengajar for tenaga pendidik in their madrasah.
     */
    public function index()
    {
        $user = Auth::user();

        \Log::info('JadwalMengajarController@index accessed by user role: ' . $user->role);

        if ($user->role === 'super_admin') {
            $jadwals = JadwalMengajar::with(['tenagaPendidik', 'madrasah'])->get();
        } elseif ($user->role === 'admin') {
            $madrasahId = $user->madrasah_id;
            $jadwals = JadwalMengajar::with(['tenagaPendidik', 'madrasah'])
                ->where('madrasah_id', $madrasahId)
                ->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('jadwal-mengajar.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new jadwal mengajar.
     */
    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        if ($user->role === 'admin') {
            $tenagaPendidiks = TenagaPendidik::where('madrasah_id', $user->madrasah_id)->get();
            $madrasahId = $user->madrasah_id;
        } else {
            // super_admin can see all tenaga pendidik
            $tenagaPendidiks = TenagaPendidik::all();
            $madrasahId = null;
        }

        return view('jadwal-mengajar.create', compact('tenagaPendidiks', 'madrasahId'));
    }

    /**
     * Store a newly created jadwal mengajar in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'tenaga_pendidik_id' => 'required|exists:tenaga_pendidiks,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'mata_pelajaran' => 'required|string|max:255',
            'madrasah_id' => 'nullable|exists:madrasahs,id',
        ]);

        if ($user->role === 'admin') {
            $validated['madrasah_id'] = $user->madrasah_id;
        } else {
            // super_admin can set madrasah_id from input
            if (empty($validated['madrasah_id'])) {
                return back()->withErrors(['madrasah_id' => 'Madrasah harus dipilih untuk super admin'])->withInput();
            }
        }

        JadwalMengajar::create($validated);

        return redirect()->route('jadwal-mengajar.index')->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }
}
