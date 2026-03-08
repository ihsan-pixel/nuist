<?php

namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolScore;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // Super admin: lihat semua hasil
    public function index()
    {
        // route already guarded by role:super_admin, so just load the scores
        $scores = SchoolScore::with('school')->paginate(60);
        return view('talenta.results.index', compact('scores'));
    }

    // Pemateri: rekap
    public function rekap()
    {
        // route already guarded by role:pemateri in routes/web.php
        $scores = SchoolScore::with('school')->paginate(60);

        // summary aggregates for the rekap view
        $totalSchools = SchoolScore::count();
        $avgStruktur = (float) number_format(SchoolScore::avg('struktur') ?? 0, 2);
        $avgKompetensi = (float) number_format(SchoolScore::avg('kompetensi') ?? 0, 2);
        $avgPerilaku = (float) number_format(SchoolScore::avg('perilaku') ?? 0, 2);
        $avgKeterpaduan = (float) number_format(SchoolScore::avg('keterpaduan') ?? 0, 2);

        $levelCounts = [
            'A' => SchoolScore::where('level_sekolah', 'A')->count(),
            'B' => SchoolScore::where('level_sekolah', 'B')->count(),
            'C' => SchoolScore::where('level_sekolah', 'C')->count(),
            'D' => SchoolScore::where('level_sekolah', 'D')->count(),
        ];

        return view('talenta.rekap.index', compact('scores', 'totalSchools', 'avgStruktur', 'avgKompetensi', 'avgPerilaku', 'avgKeterpaduan', 'levelCounts'));
    }

    /**
     * Super Admin view: Rekap Kelulusan
     * Shows high-level components used for final passing score.
     */
    public function rekapKelulusan()
    {
        // Load peserta list with penilaian (may be empty). We show peserta even if no penilaian exists.
        $pesertaList = \App\Models\TalentaPeserta::with(['user.madrasah', 'penilaian'])->paginate(60);

        // For each peserta, compute averages and weighted total so the view only renders values.
        $pesertaList->getCollection()->transform(function ($p) {
            $pen = collect($p->penilaian ?? []);

            $avgUjian = $pen->avg('nilai_ujian') ?: 0; // 0..100
            // Try to compute kelompok score from tugas_nilai (shared kelompok tasks)
            try {
                // Find kelompok ids that this peserta (user) belongs to. Members of the same
                // kelompok should inherit the same kelompok tugas scores.
                $kelompokIds = \App\Models\TalentaKelompok::whereHas('users', function ($q) use ($p) {
                    $q->where('users.id', $p->user_id);
                })->pluck('id')->toArray();

                if (!empty($kelompokIds)) {
                    $avgKelompok = (float) \App\Models\TugasNilai::whereHas('tugas', function ($q) use ($kelompokIds) {
                        $q->whereIn('kelompok_id', $kelompokIds)->where('jenis_tugas', 'kelompok');
                    })->avg('nilai') ?: 0;
                } else {
                    // no kelompok assigned -> fallback to per-peserta penilaian
                    $avgKelompok = $pen->avg('partisipasi') ?: 0;
                }
            } catch (\Throwable $e) {
                // anything goes wrong (missing table/model), fallback to penilaian field
                $avgKelompok = $pen->avg('partisipasi') ?: 0; // 1..5 fallback
            }
            $avgKehadiran = $pen->avg('kehadiran') ?: 0; // 1..5
            $avgKedisiplinan = $pen->avg('disiplin') ?: $pen->avg('sikap') ?: 0; // 1..5 fallback

            // For Onsite and Terstruktur we take the average from the tugas_nilai table
            // (TugasTalentaLevel1 -> nilai relationship). Use the TugasNilai model.
            try {
                $avgOnsite = (float) \App\Models\TugasNilai::whereHas('tugas', function ($q) use ($p) {
                    $q->where('user_id', $p->user_id)->where('jenis_tugas', 'on_site');
                })->avg('nilai') ?: 0;
            } catch (\Throwable $e) {
                // If anything goes wrong (missing table/model), fallback to penilaian field
                $avgOnsite = $pen->avg('praktik') ?: 0;
            }

            try {
                $avgTerstruktur = (float) \App\Models\TugasNilai::whereHas('tugas', function ($q) use ($p) {
                    $q->where('user_id', $p->user_id)->where('jenis_tugas', 'terstruktur');
                })->avg('nilai') ?: 0;
            } catch (\Throwable $e) {
                $avgTerstruktur = $pen->avg('tugas') ?: 0;
            }

            // Normalize ujian 0..100 -> 0..5 for weighted calculation
            $ujianNorm = $avgUjian / 20.0;

            $total = ($ujianNorm * 0.5) + ($avgOnsite * 0.1) + ($avgTerstruktur * 0.1) + ($avgKelompok * 0.1) + ($avgKehadiran * 0.1) + ($avgKedisiplinan * 0.1);

            // attach computed values onto the model instance for easy use in the view
            $p->avg_ujian = round((float) $avgUjian, 2);
            $p->avg_onsite = round((float) $avgOnsite, 2);
            $p->avg_terstruktur = round((float) $avgTerstruktur, 2);
            $p->avg_kelompok = round((float) $avgKelompok, 2);
            $p->avg_kehadiran = round((float) $avgKehadiran, 2);
            $p->avg_kedisiplinan = round((float) $avgKedisiplinan, 2);
            $p->total_score = round((float) $total, 2);

            return $p;
        });

        return view('talenta.rekap.kelulusan', compact('pesertaList'));
    }

    public function detail(SchoolScore $score)
    {
        // Try to load answers submitted by the recorded submitter first
        $answers = [];
        if ($score->submitted_by) {
            $answers = \App\Models\Answer::with('question')->where('user_id', $score->submitted_by)->get();
        }

        // Fallback: load answers from any user that belongs to this school
        if (empty($answers) || $answers->isEmpty()) {
            $userIds = \App\Models\User::where('madrasah_id', $score->school_id)->pluck('id');
            $answers = \App\Models\Answer::with('question')->whereIn('user_id', $userIds)->get();
        }

        // annotate answers with resolved choice text for easier display in view
        $answers->each(function ($a) {
            $letter = strtoupper($a->jawaban ?? '');
            $a->choice_text = isset($a->question->choice_texts[$letter]) ? $a->question->choice_texts[$letter] : null;
        });

        return view('talenta.rekap.detail', compact('score', 'answers'));
    }

    /**
     * PDF / printable view of a school's rekap (per-question answers)
     */
    public function pdf(SchoolScore $score)
    {
        // reuse detail-loading logic
        $answers = [];
        if ($score->submitted_by) {
            $answers = \App\Models\Answer::with('question')->where('user_id', $score->submitted_by)->get();
        }

        if (empty($answers) || $answers->isEmpty()) {
            $userIds = \App\Models\User::where('madrasah_id', $score->school_id)->pluck('id');
            $answers = \App\Models\Answer::with('question')->whereIn('user_id', $userIds)->get();
        }

        $answers->each(function ($a) {
            $letter = strtoupper($a->jawaban ?? '');
            $a->choice_text = isset($a->question->choice_texts[$letter]) ? $a->question->choice_texts[$letter] : null;
        });

        // compute per-dimension distribution (counts and percentages for A..E)
        $dimensions = ['Struktur','Kompetensi','Perilaku','Keterpaduan'];
        $dimensionStats = [];
        foreach ($dimensions as $dim) {
            $qIds = \App\Models\Question::where('kategori', $dim)->pluck('id')->toArray();
            $ansForDim = $answers->filter(function ($a) use ($qIds) {
                return in_array($a->question_id, $qIds);
            });
            $total = $ansForDim->count();
            $counts = [];
            foreach (['A','B','C','D','E'] as $letter) {
                $counts[$letter] = $ansForDim->where('jawaban', strtoupper($letter))->count();
            }
            $percents = [];
            foreach ($counts as $letter => $cnt) {
                $percents[$letter] = $total ? round(($cnt / $total) * 100, 2) : 0;
            }
            $dimensionStats[$dim] = [
                'total_answers' => $total,
                'counts' => $counts,
                'percents' => $percents,
            ];
        }

        return view('talenta.rekap.pdf', compact('score', 'answers', 'dimensionStats'));
    }
}
