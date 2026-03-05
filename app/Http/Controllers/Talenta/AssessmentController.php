<?php

namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\SchoolScore;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:tenaga_pendidik']);
    }

    public function fill()
    {
        $user = auth()->user();
    // load questions ordered by id so the fill view shows questions in id order
    $questions = Question::orderBy('id')->get();

    // load existing answers for this user to prefill the form (jawaban stores the letter A/B/C/D/E)
    $existingAnswers = Answer::where('user_id', $user->id)->pluck('jawaban', 'question_id')->toArray();

    return view('talenta.assessment.fill', compact('questions', 'existingAnswers'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $answers = $request->input('answers', []);

        // categories / dimensions used in the instrument
        $dimensions = [
            'Struktur' => 'struktur',
            'Kompetensi' => 'kompetensi',
            'Perilaku' => 'perilaku',
            'Keterpaduan' => 'keterpaduan',
        ];

        DB::transaction(function () use ($answers, $user, $dimensions) {
            foreach ($answers as $questionId => $jawaban) {
                $q = Question::find($questionId);
                if (!$q) continue;

                // determine numeric score from question's choice_scores mapping
                $map = $q->choice_scores ?? null;
                $skor = null;
                if (is_array($map) && isset($map[strtoupper($jawaban)])) {
                    $skor = (int) $map[strtoupper($jawaban)];
                }

                // fallback: if map missing, leave skor 0
                $skor = $skor ?? 0;

                Answer::updateOrCreate(
                    ['user_id' => $user->id, 'question_id' => $q->id],
                    ['jawaban' => strtoupper($jawaban), 'skor' => $skor]
                );
            }

            // aggregate per-dimension
            $scores = [];
            foreach ($dimensions as $label => $column) {
                $questionIds = Question::where('kategori', $label)->pluck('id');
                $sum = Answer::where('user_id', $user->id)->whereIn('question_id', $questionIds)->sum('skor');
                $scores[$column] = $sum;
            }

            $total = array_sum($scores);

            // persist aggregated score to school_scores
            SchoolScore::updateOrCreate(
                ['school_id' => optional($user->madrasah)->id ?? null],
                array_merge($scores, ['total_skor' => $total, 'level_sekolah' => $this->determineLevel($total), 'submitted_by' => $user->id])
            );
        });

        return redirect()->route('talenta.assessment.fill')->with('success','Jawaban disimpan dan skor dihitung');
    }

    public function myResults(Request $request)
    {
        $user = $request->user();
        $schoolScore = SchoolScore::where('school_id', optional($user->madrasah)->id ?? null)->latest()->first();
        return view('talenta.assessment.my_results', compact('schoolScore'));
    }

    protected function determineLevel($total)
    {
        if ($total >= 80) return 'A';
        if ($total >= 60) return 'B';
        if ($total >= 40) return 'C';
        return 'D';
    }
}
