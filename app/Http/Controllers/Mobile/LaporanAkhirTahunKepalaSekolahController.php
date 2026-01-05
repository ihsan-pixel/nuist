<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhirTahunKepalaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanAkhirTahunKepalaSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporans = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->orderBy('tahun_pelaporan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile.laporan-akhir-tahun.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Development notice - prevent creation
        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('warning', 'Fitur pembuatan laporan akhir tahun sedang dalam pengembangan. Silakan hubungi administrator untuk informasi lebih lanjut.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Development notice - prevent storing
        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('warning', 'Fitur penyimpanan laporan akhir tahun sedang dalam pengembangan. Silakan hubungi administrator untuk informasi lebih lanjut.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        return view('mobile.laporan-akhir-tahun.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Development notice - prevent editing
        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('warning', 'Fitur pengeditan laporan akhir tahun sedang dalam pengembangan. Silakan hubungi administrator untuk informasi lebih lanjut.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Development notice - prevent updating
        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('warning', 'Fitur pembaruan laporan akhir tahun sedang dalam pengembangan. Silakan hubungi administrator untuk informasi lebih lanjut.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        // Development notice - prevent deletion
        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('warning', 'Fitur penghapusan laporan akhir tahun sedang dalam pengembangan. Silakan hubungi administrator untuk informasi lebih lanjut.');
    }
}
