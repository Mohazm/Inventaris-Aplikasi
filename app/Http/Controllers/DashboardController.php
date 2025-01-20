<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Transactions_in;
use App\models\User;
use App\models\Transactions_out;
// use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $users = User::count();

        $currentMonth = $request->input('month', Carbon::now()->format('Y-m')); // Default ke bulan saat ini

        $transactionsins = Transactions_in::whereYear('tanggal_masuk', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_masuk', substr($currentMonth, 5, 2))
            ->with('item')
            ->simplePaginate(1);

        $transactionsouts = Transactions_out::whereYear('tanggal_keluar', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_keluar', substr($currentMonth, 5, 2))
            ->with('item')

            ->simplePaginate(1);
        $revenueIn = Transactions_in::whereYear('tanggal_masuk', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_masuk', substr($currentMonth, 5, 2))
            ->sum('jumlah');  // Ganti dengan kolom yang sesuai untuk total

        // Ambil total pendapatan untuk transactions_out
        $revenueOut = Transactions_out::whereYear('tanggal_keluar', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_keluar', substr($currentMonth, 5, 2))
            ->sum('jumlah');  // Ganti dengan kolom yang sesuai untuk total

        if (Auth::user()->role == 'admin') {
            return view('admin.index', compact('transactionsins', 'revenueIn','revenueOut', 'transactionsouts', 'currentMonth', 'users'));
        }

        if (Auth::user()->role == 'staff') {
            return view('staff.index', compact('transactionsins', 'revenueIn','revenueOut','transactionsouts', 'currentMonth'));
        }

        return redirect('/');
    }
}
