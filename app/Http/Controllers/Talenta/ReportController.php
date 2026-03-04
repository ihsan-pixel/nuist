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
        $this->authorize('viewAny', SchoolScore::class);
        $scores = SchoolScore::with('school')->paginate(30);
        return view('talenta.results.index', compact('scores'));
    }

    // Pemateri: rekap
    public function rekap()
    {
        $this->middleware('role:pemateri');
        $scores = SchoolScore::with('school')->paginate(30);
        return view('talenta.rekap.index', compact('scores'));
    }

    public function detail(SchoolScore $score)
    {
        return view('talenta.rekap.detail', compact('score'));
    }
}
