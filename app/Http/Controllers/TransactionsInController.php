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
        $transactions_outs = Transactions_out::with('item')->simplePaginate(10);
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
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'tujuan_keluar.required' => 'Tujuan barang keluar wajib diisi.',
            'tujuan_keluar.max' => 'Tujuan barang keluar maksimal 255 karakter.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal adalah 0.01.',
        ]);

        $items = Item::find($request->item_id);

        if ($request->jumlah > $items->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.']);
        }

        try {
            Transactions_out::create([
                'item_id' => $request->item_id,
                'tujuan_keluar' => $request->tujuan_keluar,
                'jumlah' => $request->jumlah,
                'tanggal_keluar' => now(), // Tanggal otomatis hari ini
            ]);

            $items->decrement('stock', $request->jumlah);

            return redirect()->route('Transactions_out.index')
                ->with('success', 'Transaksi barang keluar berhasil ditambahkan dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyimpan transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $transaction_out = Transactions_out::find($id);
        $items = Item::all();

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        return view('Crud_admin.transactions_out.edit', compact('transaction_out', 'items'));
    }

    public function update(Request $request, $id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'tujuan_keluar.required' => 'Tujuan barang keluar wajib diisi.',
            'tujuan_keluar.max' => 'Tujuan barang keluar maksimal 255 karakter.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal adalah 0.01.',
        ]);

        $item = Item::find($transaction_out->item_id);
        $stockChange = $transaction_out->jumlah - $request->jumlah;

        if ($request->jumlah > $item->stock + $transaction_out->jumlah) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.']);
        }

        try {
            $transaction_out->update([
                'item_id' => $request->item_id,
                'tujuan_keluar' => $request->tujuan_keluar,
                'jumlah' => $request->jumlah,
                'tanggal_keluar' => now(), // Tanggal otomatis hari ini
            ]);

            $item->increment('stock', $stockChange);

            return redirect()->route('Transactions_out.index')
                ->with('success', 'Transaksi barang keluar berhasil diperbarui dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memperbarui transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('Transactions_out.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            $item = Item::find($transaction_out->item_id);
            $item->increment('stock', $transaction_out->jumlah);

            $transaction_out->delete();

            return redirect()->route('Transactions_out.index')
                ->with('success', 'Transaksi barang keluar berhasil dihapus dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi. Silakan coba lagi.']);
        }
    }
}
