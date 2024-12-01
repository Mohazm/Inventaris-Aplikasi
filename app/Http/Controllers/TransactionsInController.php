<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transactions_in;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionsInController extends Controller
{
    public function index(Request $request)
    {
        $transaction_ins = Transactions_in::with('item', 'supplier')->paginate(10);

        return view('Crud_admin.Transactions_in.index', compact('transaction_ins'));
    }

    public function create()
    {
        $items = Item::all();
        $suppliers = Supplier::all();

        return view('Crud_admin.Transactions_in.create', compact('items', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal tidak valid.',
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier harus dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah barang masuk wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal adalah 0.01.',
        ]);

        try {
            // Buat transaksi barang masuk
            $transactions_ins = Transactions_in::create($request->all());

            // Perbarui stok barang
            $item = Item::find($request->item_id);
            $item->increment('stock', $request->jumlah);

            return redirect()->route('Transactions_in.index')->with('success', 'Transaksi barang masuk berhasil disimpan dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyimpan transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('Crud_admin.Transactions_in.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $items = Item::all();
        $suppliers = Supplier::all();

        return view('Crud_admin.Transactions_in.edit', compact('transactions_ins', 'items', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('Transactions_in.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $request->validate([
            'tanggal_masuk' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        try {
            // Hitung perubahan stok
            $oldJumlah = $transactions_ins->jumlah;
            $newJumlah = $request->jumlah;
            $stokChange = $newJumlah - $oldJumlah;

            // Update transaksi barang masuk
            $transactions_ins->update($request->all());

            // Perbarui stok barang
            $item = Item::find($transactions_ins->item_id);
            $item->increment('stock', $stokChange);

            return redirect()->route('Transactions_in.index')->with('success', 'Transaksi barang masuk berhasil diperbarui dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memperbarui transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('Crud_admin.Transactions_in.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            // Kurangi stok barang sesuai jumlah pada transaksi sebelum menghapus
            $item = Item::find($transactions_ins->item_id);
            $item->decrement('stock', $transactions_ins->jumlah);

            $transactions_ins->delete();

            return redirect()->route('Crud_admin.index')->with('success', 'Transaksi barang masuk berhasil dihapus dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi. Silakan coba lagi.']);
        }
    }
}
