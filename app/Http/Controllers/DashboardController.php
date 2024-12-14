<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Transactions_in;
use App\models\Transactions_out;
// use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m')); // Default ke bulan saat ini

        $transactionsins = Transactions_in::whereYear('tanggal_masuk', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_masuk', substr($currentMonth, 5, 2))
            ->with('item')
            ->simplePaginate(1);

        $transactionsouts = Transactions_out::whereYear('tanggal_keluar', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_keluar', substr($currentMonth, 5, 2))
            ->with('item')
            ->simplePaginate(1);

        if (Auth::user()->role == 'admin') {
            return view('admin.index', compact('transactionsins', 'transactionsouts', 'currentMonth'));
        }

        if (Auth::user()->role == 'staff') {
            return view('staff.index', compact('transactionsins', 'transactionsouts', 'currentMonth'));
        }

        return redirect('/');
    }
}
