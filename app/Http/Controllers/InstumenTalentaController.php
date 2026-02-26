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
use App\Exports\Instumen\PesertaSheetExport;
use App\Exports\Instumen\PesertaAllExport;
use App\Models\TugasTalentaLevel1;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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

    /**
     * Download all uploaded tugas as a single merged PDF.
     * For each peserta, prepend a small PDF page with Nama Peserta and Kode Peserta.
     * This implementation attempts to use GhostScript (gs) to concatenate PDFs.
     * If gs is not available it will return a ZIP fallback containing the generated PDFs and original files.
     */
    public function downloadAllTugas(Request $request)
    {
        $area = $request->query('area', null);

        $query = TugasTalentaLevel1::whereNotNull('file_path');
        if ($area) {
            $query->where('area', $area);
        }

        $tugas = $query->orderBy('submitted_at', 'asc')->get();

        if ($tugas->isEmpty()) {
            return redirect()->route('instumen-talenta.upload-tugas', ['area' => $area])->with('error', 'Tidak ada file tugas untuk di-download.');
        }

        $tmpDir = storage_path('app/tmp/merge_tugas_' . uniqid());
        @mkdir($tmpDir, 0755, true);

        $pdfParts = [];

        foreach ($tugas as $item) {
            // Resolve peserta info
            $peserta = \App\Models\TalentaPeserta::where('user_id', $item->user_id)->first();
            $nama = $item->user->name ?? ($peserta->user->name ?? 'Unknown');
            $kode = $peserta->kode_peserta ?? '-';

            // Create header PDF
            $headerHtml = "<div style='font-family: DejaVu Sans, sans-serif; padding:20px;'>" .
                "<h2>Nama Peserta: " . e($nama) . "</h2>" .
                "<p>Kode Peserta: " . e($kode) . "</p>" .
                "<hr></div>";

            $headerPdfPath = $tmpDir . DIRECTORY_SEPARATOR . 'header_' . $item->id . '.pdf';
            try {
                Pdf::loadHTML($headerHtml)->setPaper('a4')->save($headerPdfPath);
                $pdfParts[] = $headerPdfPath;
            } catch (\Exception $e) {
                Log::error('Failed to create header PDF for tugas id ' . $item->id, ['error' => $e->getMessage()]);
                // continue, but header may be missing
            }

            // Resolve original file path (public path)
            $relative = ltrim($item->file_path, '/');
            $fullPath = public_path($relative);

            if (file_exists($fullPath) && is_file($fullPath)) {
                $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                if ($ext === 'pdf') {
                    // Use directly
                    $pdfParts[] = $fullPath;
                } elseif (in_array($ext, ['png','jpg','jpeg','gif','bmp','webp'])) {
                    // Render image into a PDF page
                    $html = "<div style='padding:10px;text-align:center;font-family: DejaVu Sans, sans-serif;'>" .
                        "<img src='file://" . e($fullPath) . "' style='max-width:100%;height:auto;'>" .
                        "</div>";
                    $imgPdf = $tmpDir . DIRECTORY_SEPARATOR . 'img_' . $item->id . '.pdf';
                    try {
                        Pdf::loadHTML($html)->setPaper('a4')->save($imgPdf);
                        $pdfParts[] = $imgPdf;
                    } catch (\Exception $e) {
                        Log::error('Failed to convert image to PDF for tugas id ' . $item->id, ['error' => $e->getMessage()]);
                        // fallback: skip
                    }
                } else {
                    // Unsupported type: generate a PDF page that contains filename and a link
                    $noticeHtml = "<div style='font-family: DejaVu Sans, sans-serif; padding:20px;'>" .
                        "<h3>File tidak dapat ditampilkan secara otomatis</h3>" .
                        "<p>Nama file: " . e(basename($fullPath)) . "</p>" .
                        "<p>Lokasi file: " . e(url($relative)) . "</p>" .
                        "</div>";
                    $noticePdf = $tmpDir . DIRECTORY_SEPARATOR . 'notice_' . $item->id . '.pdf';
                    try {
                        Pdf::loadHTML($noticeHtml)->setPaper('a4')->save($noticePdf);
                        $pdfParts[] = $noticePdf;
                    } catch (\Exception $e) {
                        Log::error('Failed to create notice PDF for tugas id ' . $item->id, ['error' => $e->getMessage()]);
                    }
                }
            } else {
                // file missing: create a small note PDF
                $missingHtml = "<div style='font-family: DejaVu Sans, sans-serif; padding:20px;'>" .
                    "<h3>File tidak ditemukan</h3>" .
                    "<p>Item ID: " . e($item->id) . "</p>" .
                    "</div>";
                $missingPdf = $tmpDir . DIRECTORY_SEPARATOR . 'missing_' . $item->id . '.pdf';
                try {
                    Pdf::loadHTML($missingHtml)->setPaper('a4')->save($missingPdf);
                    $pdfParts[] = $missingPdf;
                } catch (\Exception $e) {
                    Log::error('Failed to create missing file PDF for tugas id ' . $item->id, ['error' => $e->getMessage()]);
                }
            }
        }

        if (empty($pdfParts)) {
            return redirect()->route('instumen-talenta.upload-tugas', ['area' => $area])->with('error', 'Tidak ada file PDF yang bisa digabungkan.');
        }

        // Attempt to merge using GhostScript
        $mergedPath = $tmpDir . DIRECTORY_SEPARATOR . 'merged_' . time() . '.pdf';
        $gsPath = null;
        try {
            $which = trim(shell_exec('which gs'));
            if (!empty($which)) {
                $gsPath = $which;
            }
        } catch (\Throwable $e) {
            $gsPath = null;
        }

        if ($gsPath) {
            $partsEscaped = array_map(function ($p) { return escapeshellarg($p); }, $pdfParts);
            $cmd = escapeshellcmd($gsPath) . ' -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=' . escapeshellarg($mergedPath) . ' ' . implode(' ', $partsEscaped);
            exec($cmd, $out, $rv);
            if ($rv === 0 && file_exists($mergedPath)) {
                return response()->download($mergedPath, 'tugas_semua_' . ($area ?: 'all') . '.pdf')->deleteFileAfterSend(true);
            }
            Log::warning('GhostScript merge failed', ['cmd' => $cmd, 'rv' => $rv, 'out' => $out]);
        }

        // Fallback: create a zip with generated PDFs and original files
        $zipPath = $tmpDir . DIRECTORY_SEPARATOR . 'tugas_semua_' . ($area ?: 'all') . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            // add all pdfParts
            foreach ($pdfParts as $p) {
                $zip->addFile($p, basename($p));
            }
            // also add original files if exist
            foreach ($tugas as $item) {
                $relative = ltrim($item->file_path, '/');
                $fullPath = public_path($relative);
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $zip->addFile($fullPath, 'originals/' . basename($fullPath));
                }
            }
            $zip->close();
            return response()->download($zipPath, 'tugas_semua_' . ($area ?: 'all') . '.zip')->deleteFileAfterSend(true);
        }

        return redirect()->route('instumen-talenta.upload-tugas', ['area' => $area])->with('error', 'Gagal membuat file gabungan.');
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

        // Prepare aggregated averages per peserta (across all evaluators) for the selected materi
        $participant_averages = collect();
        foreach ($pesertas as $peserta) {
            $query = \App\Models\TalentaPenilaianPeserta::where('talenta_peserta_id', $peserta->id);
            if ($selected_materi_id !== 'all') {
                $query->where('materi_id', $selected_materi_id);
            }
            $entries = $query->get();
            if ($entries->isEmpty()) {
                // still include peserta with nulls
                $scores = [];
                foreach ($fields_peserta as $f) {
                    $scores[$f] = null;
                }
                $participant_averages->push([
                    'peserta' => $peserta,
                    'scores' => $scores,
                    'count' => 0,
                ]);
                continue;
            }

            $scores = [];
            foreach ($fields_peserta as $f) {
                $avg = $entries->avg($f);
                $scores[$f] = $avg !== null ? round($avg, 2) : null;
            }

            $participant_averages->push([
                'peserta' => $peserta,
                'scores' => $scores,
                'count' => $entries->count(),
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

    return view('instumen-talenta.instrumen-penilaian', compact('participant_details', 'fasilitator_details', 'pemateri_details', 'materis', 'selected_materi_id', 'evaluator_details', 'participant_averages'));
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

    // Peserta export (per evaluator)
    public function exportPeserta($evaluatorId, \Illuminate\Http\Request $request)
    {
        $materiId = $request->query('materi_id', null);
        return Excel::download(new PesertaSheetExport($evaluatorId, $materiId), 'peserta_evaluator_' . $evaluatorId . '.xlsx');
    }

    public function exportPesertaAll(\Illuminate\Http\Request $request)
    {
        $materiId = $request->query('materi_id', null);
        return Excel::download(new PesertaAllExport($materiId), 'peserta_all.xlsx');
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
