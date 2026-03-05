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
        return view('talenta.rekap.index', compact('scores'));
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
}
