<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TalentaPeserta;
use App\Models\TalentaMateri;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
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

        return view('instumen-talenta.index', compact('totalPeserta', 'totalPemateri', 'totalMateri', 'totalFasilitator'));
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'pilih_materi' => 'required|exists:talenta_materi,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate kode pemateri
        $count = TalentaPemateri::count() + 1;
        $kodePemateri = 'T-P-01.' . str_pad($count, 3, '0', STR_PAD_LEFT);

        TalentaPemateri::create([
            'kode_pemateri' => $kodePemateri,
            'nama' => $request->nama,
            'materi_id' => $request->pilih_materi,
        ]);

        return redirect()->route('instumen-talenta.input-pemateri')->with('success', 'Data pemateri berhasil disimpan.');
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'pilih_materi' => 'required|exists:talenta_materi,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate kode fasilitator
        $count = TalentaFasilitator::count() + 1;
        $kodeFasilitator = 'T-F-01.' . str_pad($count, 3, '0', STR_PAD_LEFT);

        TalentaFasilitator::create([
            'kode_fasilitator' => $kodeFasilitator,
            'nama' => $request->nama,
            'materi_id' => $request->pilih_materi,
        ]);

        return redirect()->route('instumen-talenta.input-fasilitator')->with('success', 'Data fasilitator berhasil disimpan.');
    }
}
