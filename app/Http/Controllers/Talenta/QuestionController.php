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
            'skor_ya' => 'required|integer',
            'skor_tidak' => 'required|integer',
        ]);

        Question::create($data);
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
            'skor_ya' => 'required|integer',
            'skor_tidak' => 'required|integer',
        ]);

        $question->update($data);
        return redirect()->route('talenta.questions.index')->with('success','Soal berhasil diperbarui');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('talenta.questions.index')->with('success','Soal dihapus');
    }
}
