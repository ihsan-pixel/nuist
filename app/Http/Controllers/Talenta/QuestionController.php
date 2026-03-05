<?php

namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:super_admin']);
    }

    public function index()
    {
        $questions = Question::orderBy('kategori')->paginate(20);
        return view('talenta.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('talenta.questions.form', ['question' => new Question]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'required|string',
            'pertanyaan' => 'required|string',
            'choice_texts' => 'nullable|array',
            'choice_scores' => 'nullable|array',
            'choice_scores.A' => 'nullable|integer',
            'choice_scores.B' => 'nullable|integer',
            'choice_scores.C' => 'nullable|integer',
            'choice_scores.D' => 'nullable|integer',
            'choice_scores.E' => 'nullable|integer',
        ]);

        Question::create([
            'kategori' => $data['kategori'],
            'pertanyaan' => $data['pertanyaan'],
            'choice_texts' => $data['choice_texts'] ?? null,
            'choice_scores' => $data['choice_scores'] ?? null,
        ]);
        return redirect()->route('talenta.questions.index')->with('success','Soal berhasil disimpan');
    }

    public function edit(Question $question)
    {
        return view('talenta.questions.form', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'kategori' => 'required|string',
            'pertanyaan' => 'required|string',
            'choice_texts' => 'nullable|array',
            'choice_scores' => 'nullable|array',
            'choice_scores.A' => 'nullable|integer',
            'choice_scores.B' => 'nullable|integer',
            'choice_scores.C' => 'nullable|integer',
            'choice_scores.D' => 'nullable|integer',
            'choice_scores.E' => 'nullable|integer',
        ]);

        $question->update([
            'kategori' => $data['kategori'],
            'pertanyaan' => $data['pertanyaan'],
            'choice_texts' => $data['choice_texts'] ?? $question->choice_texts,
            'choice_scores' => $data['choice_scores'] ?? $question->choice_scores,
        ]);
        return redirect()->route('talenta.questions.index')->with('success','Soal berhasil diperbarui');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('talenta.questions.index')->with('success','Soal dihapus');
    }
}
