<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions_in;
use App\Models\Transactions_out;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function getRevenueData(Request $request)
    {
        // Ambil bulan yang dipilih
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Ambil total pendapatan untuk transactions_in
        $revenueIn = Transactions_in::whereYear('tanggal_masuk', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_masuk', substr($currentMonth, 5, 2))
            ->sum('total_price');  // Ganti dengan kolom yang sesuai untuk total

        // Ambil total pendapatan untuk transactions_out
        $revenueOut = Transactions_out::whereYear('tanggal_keluar', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_keluar', substr($currentMonth, 5, 2))
            ->sum('total_price');  // Ganti dengan kolom yang sesuai untuk total

        return response()->json([
            'revenue_in' => $revenueIn,
            'revenue_out' => $revenueOut,
        ]);
    }
}
