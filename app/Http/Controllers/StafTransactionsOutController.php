<?php

namespace App\Http\Controllers;

use App\Models\Transactions_out;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StafTransactionsOutController extends Controller
{
    public function index()
    {
        $transactions_outs = Transactions_out::with('item')->simplePaginate(5);
        return view('staff.transactions_out.index', compact('transactions_outs'));
    }

    public function create()
    {
        $items = Item::all();
        return view('staff.transactions_out.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value !== now()->toDateString()) {
                        $fail('Tanggal keluar hanya bisa diisi dengan tanggal hari ini.');
                    }
                },
            ],
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal keluar tidak valid.',
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'tujuan_keluar.required' => 'Tujuan keluar wajib diisi.',
            'tujuan_keluar.string' => 'Tujuan keluar harus berupa teks.',
            'tujuan_keluar.max' => 'Tujuan keluar tidak boleh lebih dari 255 karakter.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang keluar harus berupa angka.',
            'jumlah.min' => 'Jumlah barang keluar minimal adalah 0.01.',
        ]);

        $requestData = $request->all();
        $requestData['tanggal_keluar'] = now()->toDateString();

        $items = Item::find($request->item_id);

        if ($request->jumlah > $items->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.'])->withInput();
        }

        try {
            Transactions_out::create($requestData);
            $items->decrement('stock', $request->jumlah);

            return redirect()->route('StafTransactions_out.index')->with('success', 'Transaksi barang keluar berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi.'])->withInput();
        }
    }

    public function edit($id)
    {
        $transaction_out = Transactions_out::find($id);
        $items = Item::all();

        if (!$transaction_out) {
            return redirect()->route('StafTransactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        return view('staff.transactions_out.edit', compact('transaction_out', 'items'));
    }

    public function update(Request $request, $id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('StafTransactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $request->validate([
            'tanggal_keluar' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value !== now()->toDateString()) {
                        $fail('Tanggal keluar hanya bisa diisi dengan tanggal hari ini.');
                    }
                },
            ],
            'item_id' => 'required|exists:items,id',
            'tujuan_keluar' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal keluar tidak valid.',
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'tujuan_keluar.required' => 'Tujuan keluar wajib diisi.',
            'tujuan_keluar.string' => 'Tujuan keluar harus berupa teks.',
            'tujuan_keluar.max' => 'Tujuan keluar tidak boleh lebih dari 255 karakter.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang keluar harus berupa angka.',
            'jumlah.min' => 'Jumlah barang keluar minimal adalah 0.01.',
        ]);

        $items = Item::find($transaction_out->item_id);

        $stockChange = $request->jumlah - $transaction_out->jumlah;

        if ($stockChange > $items->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.'])->withInput();
        }

        try {
            $transaction_out->update($request->all());
            $items->decrement('stock', $stockChange);

            return redirect()->route('StafTransactions_out.index')->with('success', 'Transaksi barang keluar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaction_out = Transactions_out::find($id);

        if (!$transaction_out) {
            return redirect()->route('StafTransactions_out.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            $items = Item::find($transaction_out->item_id);
            $items->increment('stock', $transaction_out->jumlah);

            $transaction_out->delete();

            return redirect()->route('StafTransactions_out.index')->with('success', 'Transaksi barang keluar berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi.']);
        }
    }
}
