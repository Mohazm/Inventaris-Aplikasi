<?php

namespace App\Http\Controllers;

use App\Models\Transactions_out;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionsOutController extends Controller
{
    public function index()
    {
        $transactions_outs = Transactions_out::with('item')->get();
        return view('Crud_admin.transactions_out.index', compact('transactions_outs'));
    }

    public function create()
    {
        $items = Item::all();
        return view('Crud_admin.transactions_out.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        $items = Item::find($request->item_id);

        if ($request->jumlah > $items->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.']);
        }

        try {
            Transactions_out::create($request->all());
            $items->decrement('stock', $request->jumlah);

            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi.']);
        }
    }

    public function edit($id)
    {
        $transaction_out = Transactions_out::find($id);
        $items = Item::all();

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        return view('Crud_admin.transactions_out.edit', compact('transaction_out', 'items'));
    }

    public function update(Request $request, $id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $request->validate([
            'tanggal_keluar' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        $items = Item::find($transaction_out->item_id);

        $stockChange = $request->jumlah - $transaction_out->jumlah;

        if ($stockChange > $items->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.']);
        }

        try {
            $transaction_out->update($request->all());
            $items->decrement('stock', $stockChange);

            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi.']);
        }
    }

    public function destroy($id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            $items = Item::find($transaction_out->item_id);
            $items->increment('stock', $transaction_out->jumlah);

            $transaction_out->delete();

            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi.']);
        }
    }
}
