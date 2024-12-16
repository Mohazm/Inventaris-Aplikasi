<?php

namespace App\Http\Controllers;

use App\Models\Returns_item;
use Illuminate\Http\Request;
use App\Models\Loans_item;
use App\Models\Item;

class ReturnsItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Returns_item $returns_item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Returns_item $returns_item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Returns_item $returns_item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Returns_item $returns_item)
    {
        //
    }
    public function returnItem($id)
    {
        // Cari data loan berdasarkan ID
        $loan = Loans_item::findOrFail($id);

        // Pastikan status saat ini adalah 'dipakai'
        if ($loan->status === 'dipakai') {
            $tanggalKembali = \Carbon\Carbon::parse($loan->tanggal_kembali); // Tanggal jatuh tempo
            $hariIni = now(); // Tanggal saat ini

            // Validasi: hanya bisa dikembalikan jika hari ini >= 1 hari sebelum tanggal kembali
            if ($hariIni->greaterThanOrEqualTo($tanggalKembali->subDay())) {
                // Kembalikan stok barang
                $item = Item::findOrFail($loan->item_id);
                $item->stock += $loan->jumlah_pinjam; // Tambahkan stok sesuai jumlah pinjam
                $item->save();

                // Perbarui status loan
                $loan->status = 'di kembalikan';
                $loan->tanggal_kembali = $hariIni; // Atur tanggal kembali aktual
                $loan->save();

                return redirect()->back()->with('success', 'Barang berhasil dikembalikan dan stok diperbarui.');
            }

            // Jika terlalu dini untuk mengembalikan
            return redirect()->back()->with('error', 'Barang belum bisa dikembalikan. Tunggu mendekati tanggal jatuh tempo.');
        }

        // Jika status tidak valid
        return redirect()->back()->with('error', 'Status barang tidak valid untuk dikembalikan.');
    }
}
