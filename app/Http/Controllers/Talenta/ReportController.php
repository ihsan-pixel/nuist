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
        $scores = SchoolScore::with('school')->paginate(30);
        return view('talenta.results.index', compact('scores'));
    }

    // Pemateri: rekap
    public function rekap()
    {
        // route already guarded by role:pemateri in routes/web.php
        $scores = SchoolScore::with('school')->paginate(30);

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
