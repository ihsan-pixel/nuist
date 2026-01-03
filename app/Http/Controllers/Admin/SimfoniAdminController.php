<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simfoni;
use Illuminate\Http\Request;

class SimfoniAdminController extends Controller
{
    public function index()
    {
        // $simfonis = Simfoni::with('user.madrasah')->paginate(20);

        return view('admin.simfoni.index', compact('simfonis'));
    }
}
