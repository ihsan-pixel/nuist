<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TalentaPeserta;
use App\Models\TalentaMateri;
use App\Models\TalentaPemateri;
use App\Models\TalentaFasilitator;
use App\Models\TalentaLayananTeknis;
use App\Models\TalentaKelompok;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Instumen\FasilitatorSheetExport;
use App\Exports\Instumen\FasilitatorAllExport;
use App\Exports\Instumen\PemateriSheetExport;
use App\Exports\Instumen\PemateriAllExport;
use App\Exports\Instumen\TeknisSheetExport;
use App\Exports\Instumen\TeknisAllExport;
use App\Models\TugasTalentaLevel1;

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
        $kelompoks = TalentaKelompok::with('users')->get();

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

        return view('instumen-talenta.input-peserta', compact('pesertas', 'kelompoks', 'nextKodePeserta'));
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

    public function createUserForFasilitator(Request $request)
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
                'role' => 'fasilitator', // Assuming fasilitator role exists
            ]);

            // Update fasilitator record with user_id
            $fasilitator = TalentaFasilitator::where('nama', $request->nama)->first();
            if ($fasilitator) {
                $fasilitator->update(['user_id' => $user->id]);
            }

            return redirect()->route('instumen-talenta.input-fasilitator')->with('success', 'Akun fasilitator berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('instumen-talenta.input-fasilitator')->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    public function storeKelompok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelompok' => 'required|string|max:255',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $kelompok = TalentaKelompok::create([
                'nama_kelompok' => $request->nama_kelompok,
            ]);

            // Attach users to kelompok
            $kelompok->users()->attach($request->user_ids);

            return redirect()->route('instumen-talenta.input-peserta')->with('success', 'Kelompok peserta berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('instumen-talenta.input-peserta')->with('error', 'Gagal membuat kelompok: ' . $e->getMessage());
        }
    }

    // Lightweight methods for the new feature pages (placeholders)
    public function kelengkapan()
    {
        // Define which user fields we consider required for "kelengkapan"
        $requiredUserFields = [
            'name',
            'email',
            'no_hp',
            'tanggal_lahir',
            'madrasah_id',
        ];

        $pesertas = TalentaPeserta::with(['user.madrasah'])->get();

        // Collect user_ids and fetch any Talenta records in bulk to avoid N+1
        $userIds = $pesertas->map(fn($p) => $p->user_id)->filter()->unique()->values()->all();
        $talentaByUser = [];
        if (!empty($userIds)) {
            $talentaRecords = \App\Models\Talenta::whereIn('user_id', $userIds)->get();
            $talentaByUser = $talentaRecords->keyBy('user_id');
        }

        $pesertas = $pesertas->map(function ($peserta) use ($requiredUserFields, $talentaByUser) {
            $user = $peserta->user;
            $isComplete = false;

            if ($user) {
                $isComplete = collect($requiredUserFields)->every(function ($field) use ($user) {
                    $value = data_get($user, $field);
                    return !is_null($value) && $value !== '';
                });
            }

            $peserta->is_complete = $isComplete;

            $peserta->in_talenta = false;
            $peserta->talenta = null;
            if ($user && isset($talentaByUser[$user->id])) {
                $peserta->in_talenta = true;
                $peserta->talenta = $talentaByUser[$user->id];
            }

            return $peserta;
        });

        return view('instumen-talenta.kelengkapan', compact('pesertas'));
    }

    public function uploadTugas(Request $request)
    {
        // Ambil daftar tugas dari tabel tugas_talenta_level1 beserta relasinya
        $tugasQuery = TugasTalentaLevel1::with(['user', 'kelompok', 'nilai']);

        // Optional: bisa tambahkan filter di sini (mis. berdasarkan area atau kelompok)
        if ($request->filled('area')) {
            $tugasQuery->where('area', $request->area);
        }

        if ($request->filled('kelompok_id')) {
            $tugasQuery->where('kelompok_id', $request->kelompok_id);
        }

        $tugas = $tugasQuery->orderBy('submitted_at', 'desc')->get();

        // Ambil daftar unique area untuk navigasi
        $areas = TugasTalentaLevel1::select('area')
            ->whereNotNull('area')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        // Ambil semua kelompok untuk opsi filter (opsional)
        $kelompoks = TalentaKelompok::all();

        $selectedArea = $request->query('area', null);
        $selectedKelompok = $request->query('kelompok_id', null);

        return view('instumen-talenta.upload-tugas', compact('tugas', 'areas', 'kelompoks', 'selectedArea', 'selectedKelompok'));
    }

    public function instrumenPenilaian(\Illuminate\Http\Request $request)
    {
        // Load peserta and related user
        $pesertas = TalentaPeserta::with('user')->orderBy('id')->get();

        // Load list of materi for navigation
        $materis = TalentaMateri::orderBy('tanggal_materi', 'asc')->get();
        $selected_materi_id = $request->query('materi_id', 'all');

        // Prepare participant-level detailed breakdown per materi and per evaluator
        $participant_details = collect();

        $fields_peserta = ['kehadiran','partisipasi','disiplin','tugas','pemahaman','praktik','sikap'];

        foreach ($pesertas as $peserta) {
            // fetch all penilaian entries for this peserta grouped by materi_id
            $entries_by_materi = \App\Models\TalentaPenilaianPeserta::with('user')
                ->where('talenta_peserta_id', $peserta->id)
                ->get()
                ->groupBy('materi_id');

            $by_materi = collect();
            foreach ($entries_by_materi as $materi_id => $entries) {
                // group entries for this materi by evaluator (user_id)
                $groups = $entries->groupBy('user_id');
                $evaluators = collect();
                foreach ($groups as $evaluatorId => $group) {
                    $user = $group->first()->user;
                    $scores = [];
                    foreach ($fields_peserta as $f) {
                        $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                    }
                    $evaluators->push([
                        'evaluator_id' => $evaluatorId,
                        'evaluator' => $user,
                        'scores' => $scores,
                        'count' => $group->count(),
                    ]);
                }

                $materiModel = $materis->firstWhere('id', $materi_id);
                $by_materi->put($materi_id, [
                    'materi' => $materiModel,
                    'evaluators' => $evaluators,
                ]);
            }

            $participant_details->push([
                'peserta' => $peserta,
                'by_materi' => $by_materi,
            ]);
        }

        // Prepare fasilitator-level breakdown
        $fasilitator_details = collect();
        $fields_fasilitator = ['fasilitasi','pendampingan','respons','koordinasi','monitoring','waktu'];
        $fasilitators = TalentaFasilitator::all();
        foreach ($fasilitators as $fasilitator) {
            $entries = \App\Models\TalentaPenilaianFasilitator::with('user')
                ->where('talenta_fasilitator_id', $fasilitator->id)
                ->get()
                ->groupBy('user_id');

            $evaluators = collect();
            foreach ($entries as $evaluatorId => $group) {
                $user = $group->first()->user;
                $scores = [];
                foreach ($fields_fasilitator as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $evaluators->push([
                    'evaluator_id' => $evaluatorId,
                    'evaluator' => $user,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $fasilitator_details->push([
                'fasilitator' => $fasilitator,
                'evaluators' => $evaluators,
            ]);
        }

        // Prepare pemateri-level breakdown
        $pemateri_details = collect();
        $fields_trainer = ['kualitas_materi','penyampaian','integrasi_kasus','penjelasan','umpan_balik','waktu'];
        $pemateris = TalentaPemateri::all();
        foreach ($pemateris as $pemateri) {
            $entries = \App\Models\TalentaPenilaianTrainer::with('user')
                ->where('talenta_pemateri_id', $pemateri->id)
                ->get()
                ->groupBy('user_id');

            $evaluators = collect();
            foreach ($entries as $evaluatorId => $group) {
                $user = $group->first()->user;
                $scores = [];
                foreach ($fields_trainer as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $evaluators->push([
                    'evaluator_id' => $evaluatorId,
                    'evaluator' => $user,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $pemateri_details->push([
                'pemateri' => $pemateri,
                'evaluators' => $evaluators,
            ]);
        }

        // Prepare evaluator-centric view for peserta (list of evaluators -> table of peserta scores)
        $evaluator_details = collect();
        // base query for peserta penilaian
        $penilaianQuery = \App\Models\TalentaPenilaianPeserta::with(['user','peserta']);
        if ($selected_materi_id !== 'all') {
            $penilaianQuery = $penilaianQuery->where('materi_id', $selected_materi_id);
        }
        $allEntries = $penilaianQuery->get()->groupBy('user_id');

        foreach ($allEntries as $evaluatorId => $entries) {
            $user = $entries->first()->user;
            // group by peserta
            $by_peserta = collect();
            $groups = $entries->groupBy('talenta_peserta_id');
            foreach ($groups as $pesertaId => $group) {
                $pesertaModel = TalentaPeserta::find($pesertaId);
                $scores = [];
                foreach ($fields_peserta as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $by_peserta->push([
                    'peserta_id' => $pesertaId,
                    'peserta' => $pesertaModel,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $evaluator_details->push([
                'evaluator_id' => $evaluatorId,
                'evaluator' => $user,
                'by_peserta' => $by_peserta,
            ]);
        }

        return view('instumen-talenta.instrumen-penilaian', compact('participant_details', 'fasilitator_details', 'pemateri_details', 'materis', 'selected_materi_id', 'evaluator_details'));
    }

    // Export helpers for fasilitator/pemateri/teknis
    public function exportFasilitator($id)
    {
        $fasil = \App\Models\TalentaFasilitator::find($id);
        $name = $fasil ? ($fasil->nama ?? 'fasilitator_'.$id) : 'fasilitator_'.$id;
        return Excel::download(new FasilitatorSheetExport($id), 'fasilitator_' . preg_replace('/[^A-Za-z0-9_\-]/','_', $name) . '.xlsx');
    }

    public function exportFasilitatorAll()
    {
        return Excel::download(new FasilitatorAllExport(), 'fasilitator_all.xlsx');
    }

    public function exportPemateri($id)
    {
        $pem = \App\Models\TalentaPemateri::find($id);
        $name = $pem ? ($pem->nama ?? 'pemateri_'.$id) : 'pemateri_'.$id;
        return Excel::download(new PemateriSheetExport($id), 'pemateri_' . preg_replace('/[^A-Za-z0-9_\-]/','_', $name) . '.xlsx');
    }

    public function exportPemateriAll()
    {
        return Excel::download(new PemateriAllExport(), 'pemateri_all.xlsx');
    }

    public function exportTeknis($id)
    {
        $lay = \App\Models\TalentaLayananTeknis::find($id);
        $name = $lay ? ($lay->nama_layanan_teknis ?? 'layanan_'.$id) : 'layanan_'.$id;
        return Excel::download(new TeknisSheetExport($id), 'teknis_' . preg_replace('/[^A-Za-z0-9_\-]/','_', $name) . '.xlsx');
    }

    public function exportTeknisAll()
    {
        return Excel::download(new TeknisAllExport(), 'teknis_all.xlsx');
    }

    // New separate pages for fasilitator, pemateri, teknis
    public function instrumenPenilaianFasilitator()
    {
        $fasilitator_details = collect();
        $fields_fasilitator = ['fasilitasi','pendampingan','respons','koordinasi','monitoring','waktu'];
        $fasilitators = TalentaFasilitator::all();
        foreach ($fasilitators as $fasilitator) {
            $entries = \App\Models\TalentaPenilaianFasilitator::with('user')
                ->where('talenta_fasilitator_id', $fasilitator->id)
                ->get()
                ->groupBy('user_id');

            $evaluators = collect();
            foreach ($entries as $evaluatorId => $group) {
                $user = $group->first()->user;
                $scores = [];
                foreach ($fields_fasilitator as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $evaluators->push([
                    'evaluator_id' => $evaluatorId,
                    'evaluator' => $user,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $fasilitator_details->push([
                'fasilitator' => $fasilitator,
                'evaluators' => $evaluators,
            ]);
        }

        return view('instumen-talenta.instrumen-penilaian-fasilitator', compact('fasilitator_details'));
    }

    public function instrumenPenilaianPemateri()
    {
        $pemateri_details = collect();
        $fields_trainer = ['kualitas_materi','penyampaian','integrasi_kasus','penjelasan','umpan_balik','waktu'];
        $pemateris = TalentaPemateri::all();
        foreach ($pemateris as $pemateri) {
            $entries = \App\Models\TalentaPenilaianTrainer::with('user')
                ->where('talenta_pemateri_id', $pemateri->id)
                ->get()
                ->groupBy('user_id');

            $evaluators = collect();
            foreach ($entries as $evaluatorId => $group) {
                $user = $group->first()->user;
                $scores = [];
                foreach ($fields_trainer as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $evaluators->push([
                    'evaluator_id' => $evaluatorId,
                    'evaluator' => $user,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $pemateri_details->push([
                'pemateri' => $pemateri,
                'evaluators' => $evaluators,
            ]);
        }

        return view('instumen-talenta.instrumen-penilaian-pemateri', compact('pemateri_details'));
    }

    public function instrumenPenilaianTeknis()
    {
        $teknis_details = collect();
        $fields_teknis = ['kehadiran','partisipasi','disiplin','kualitas_tugas','pemahaman_materi','implementasi_praktik'];
        $layanans = TalentaLayananTeknis::all();
        foreach ($layanans as $layanan) {
            $entries = \App\Models\TalentaPenilaianTeknis::with('user')
                ->where('talenta_layanan_teknis_id', $layanan->id)
                ->get()
                ->groupBy('user_id');

            $evaluators = collect();
            foreach ($entries as $evaluatorId => $group) {
                $user = $group->first()->user;
                $scores = [];
                foreach ($fields_teknis as $f) {
                    $scores[$f] = $group->avg($f) !== null ? round($group->avg($f), 2) : null;
                }
                $evaluators->push([
                    'evaluator_id' => $evaluatorId,
                    'evaluator' => $user,
                    'scores' => $scores,
                    'count' => $group->count(),
                ]);
            }

            $teknis_details->push([
                'layanan' => $layanan,
                'evaluators' => $evaluators,
            ]);
        }

        return view('instumen-talenta.instrumen-penilaian-teknis', compact('teknis_details'));
    }

    public function nilaiTugas()
    {
        return view('instumen-talenta.nilai-tugas');
    }

    public function uploadSertifikat()
    {
        return view('instumen-talenta.upload-sertifikat');
    }
}
