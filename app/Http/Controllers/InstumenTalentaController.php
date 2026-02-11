<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TalentaPeserta;
use App\Models\TalentaMateri;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaLayananTeknis;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstumenTalentaController extends Controller
{
    public function index()
    {
        $totalPeserta = TalentaPeserta::count();
        $totalPemateri = TalentaPemateri::count();
        $totalMateri = TalentaMateri::count();
        $totalFasilitator = TalentaFasilitator::count();
        $totalLayananTeknis = TalentaLayananTeknis::count();

        return view('instumen-talenta.index', compact('totalPeserta', 'totalPemateri', 'totalMateri', 'totalFasilitator', 'totalLayananTeknis'));
    }

    public function inputPeserta()
    {
        $pesertas = TalentaPeserta::with('user')->get();

        // Generate next kode peserta
        $existingCodes = TalentaPeserta::where('kode_peserta', 'like', 'T-01.%')
            ->pluck('kode_peserta')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $nextKodePeserta = 'T-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('instumen-talenta.input-peserta', compact('pesertas', 'nextKodePeserta'));
    }

    public function storePeserta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::with('madrasah')->find($request->user_id);

        // Generate next kode peserta
        $existingCodes = TalentaPeserta::where('kode_peserta', 'like', 'T-01.%')
            ->pluck('kode_peserta')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $kodePeserta = 'T-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        TalentaPeserta::create([
            'kode_peserta' => $kodePeserta,
            'user_id' => $request->user_id,
            'asal_sekolah' => $user->madrasah->name ?? 'N/A',
        ]);

        return redirect()->route('instumen-talenta.input-peserta')->with('success', 'Data peserta berhasil disimpan.');
    }

    public function inputMateri()
    {
        // Generate next kode materi
        $existingCodes = TalentaMateri::where('kode_materi', 'like', 'M-01.%')
            ->pluck('kode_materi')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $nextKodeMateri = 'M-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('instumen-talenta.input-materi', compact('nextKodeMateri'));
    }

    public function storeMateri(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_materi' => 'required|string|unique:talenta_materi,kode_materi',
            'judul_materi' => 'required|string|max:255',
            'level_materi' => 'required|in:1,2,3',
            'tanggal_materi' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TalentaMateri::create([
            'kode_materi' => $request->kode_materi,
            'judul_materi' => $request->judul_materi,
            'level_materi' => $request->level_materi,
            'tanggal_materi' => $request->tanggal_materi,
        ]);

        return redirect()->route('instumen-talenta.input-materi')->with('success', 'Data materi berhasil disimpan.');
    }

    public function inputPemateri()
    {
        $materis = TalentaMateri::all();

        // Generate next kode pemateri
        $existingCodes = TalentaPemateri::where('kode_pemateri', 'like', 'T-P-01.%')
            ->pluck('kode_pemateri')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $nextKodePemateri = 'T-P-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('instumen-talenta.input-pemateri', compact('materis', 'nextKodePemateri'));
    }

    public function storePemateri(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pilih_materi' => 'required|array',
            'pilih_materi.*' => 'exists:talenta_materi,id',
        ]);

        $count = TalentaPemateri::count() + 1;
        $kodePemateri = 'T-P-01.' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $pemateri = TalentaPemateri::create([
            'kode_pemateri' => $kodePemateri,
            'nama' => $request->nama,
        ]);

        // Simpan ke pivot table
        $pemateri->materis()->attach($request->pilih_materi);

        return redirect()
            ->route('instumen-talenta.input-pemateri')
            ->with('success', 'Data pemateri berhasil disimpan.');
    }

    public function inputFasilitator()
    {
        $materis = TalentaMateri::all();

        // Generate next kode fasilitator
        $existingCodes = TalentaFasilitator::where('kode_fasilitator', 'like', 'T-F-01.%')
            ->pluck('kode_fasilitator')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $nextKodeFasilitator = 'T-F-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('instumen-talenta.input-fasilitator', compact('materis', 'nextKodeFasilitator'));
    }

    public function storeFasilitator(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pilih_materi' => 'required|array',
            'pilih_materi.*' => 'exists:talenta_materi,id',
        ]);

        $count = TalentaFasilitator::count() + 1;
        $kodeFasilitator = 'T-F-01.' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $fasilitator = TalentaFasilitator::create([
            'kode_fasilitator' => $kodeFasilitator,
            'nama' => $request->nama,
        ]);

        // Simpan ke pivot table
        $fasilitator->materis()->attach($request->pilih_materi);

        return redirect()
            ->route('instumen-talenta.input-fasilitator')
            ->with('success', 'Data fasilitator berhasil disimpan.');
    }

    public function penilaianPemateri()
    {
        // Get active pemateri (those with materi that have tanggal_materi = today)
        $pemateris = TalentaPemateri::with('materi')
            ->whereHas('materi', function($query) {
                $query->where('tanggal_materi', '=', now()->toDateString());
            })
            ->get();

        return view('instumen-talenta.penilaian-pemateri', compact('pemateris'));
    }

    public function penilaianFasilitator()
    {
        // Get active fasilitator (those with materi that have tanggal_materi = today)
        $fasilitators = TalentaFasilitator::with('materis')
            ->whereHas('materis', function($query) {
                $query->where('tanggal_materi', '=', now()->toDateString());
            })
            ->get();

        return view('instumen-talenta.penilaian-fasilitator', compact('fasilitators'));
    }

    public function penilaianTeknis()
    {
        // Get active materi (tanggal_materi = today)
        $materis = TalentaMateri::where('tanggal_materi', '=', now()->toDateString())
            ->orderBy('tanggal_materi', 'asc')
            ->get();

        return view('instumen-talenta.penilaian-teknis', compact('materis'));
    }

    public function inputLayananTeknis()
    {
        // Generate next kode layanan teknis
        $existingCodes = TalentaLayananTeknis::where('kode_layanan_teknis', 'like', 'LT-01.%')
            ->pluck('kode_layanan_teknis')
            ->map(function($code) {
                return (int) substr($code, -3);
            })
            ->sort()
            ->toArray();

        $nextNumber = 1;
        foreach ($existingCodes as $num) {
            if ($num == $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        $nextKodeLayananTeknis = 'LT-01.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('instumen-talenta.input-layanan-teknis', compact('nextKodeLayananTeknis'));
    }

    public function storeLayananTeknis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_layanan_teknis' => 'required|string|unique:talenta_layanan_teknis,kode_layanan_teknis',
            'nama_layanan_teknis' => 'required|string|max:255',
            'tugas_layanan_teknis' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TalentaLayananTeknis::create([
            'kode_layanan_teknis' => $request->kode_layanan_teknis,
            'nama_layanan_teknis' => $request->nama_layanan_teknis,
            'tugas_layanan_teknis' => $request->tugas_layanan_teknis,
        ]);

        return redirect()->route('instumen-talenta.input-layanan-teknis')->with('success', 'Data layanan teknis berhasil disimpan.');
    }

    public function createUserForPemateri(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'pemateri', // Assuming pemateri role exists
            ]);

            // Update pemateri record with user_id
            $pemateri = TalentaPemateri::where('nama', $request->nama)->first();
            if ($pemateri) {
                $pemateri->update(['user_id' => $user->id]);
            }

            return redirect()->route('instumen-talenta.input-pemateri')->with('success', 'Akun pemateri berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('instumen-talenta.input-pemateri')->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }
}
