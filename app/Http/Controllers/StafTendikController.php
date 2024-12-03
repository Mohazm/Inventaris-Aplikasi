<?php

namespace App\Http\Controllers;
use App\Models\Tendik;
use Illuminate\Http\Request;

class StafTendikController extends Controller
{
    // Menampilkan semua data tendik
    public function index()
    {
        $tendiks = Tendik::all();
        return view('staff.tendiks.index', compact('tendiks'));
    }

    
}
