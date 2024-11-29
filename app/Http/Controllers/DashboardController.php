<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengecek role pengguna
        if (Auth::user()->role == 'admin') {
            return view('admin.index');
        }

        if (Auth::user()->role == 'staff') {
            return view('staff.index');
        }

        // Redirect jika bukan admin atau staff
        return redirect('/');
    }
}
