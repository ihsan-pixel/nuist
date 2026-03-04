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
        $questions = Question::orderBy('kategori')->get();

        // load existing answers for this user to prefill the form
        $existingAnswers = Answer::where('user_id', $user->id)->pluck('jawaban', 'question_id')->toArray();

        return view('talenta.assessment.fill', compact('questions', 'existingAnswers'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $answers = $request->input('answers', []);

        DB::transaction(function () use ($answers, $user) {
            foreach ($answers as $questionId => $jawaban) {
                $q = Question::find($questionId);
                if (!$q) continue;
                $skor = (strtolower($jawaban) === 'ya') ? $q->skor_ya : $q->skor_tidak;
                Answer::updateOrCreate(
                    ['user_id' => $user->id, 'question_id' => $q->id],
                    ['jawaban' => $jawaban, 'skor' => $skor]
                );
            }

            // hitung skor per kategori dan simpan ke school_scores (simple aggregate)
            $categories = ['layanan','tata_kelola','jumlah_siswa','jumlah_penghasilan','jumlah_prestasi','jumlah_talenta'];
            $scores = [];
            foreach ($categories as $cat) {
                $catQuestions = Question::where('kategori', $cat)->pluck('id');
                $sum = Answer::where('user_id', $user->id)->whereIn('question_id', $catQuestions)->sum('skor');
                $scores[$cat] = $sum;
            }

            $total = array_sum($scores);
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
