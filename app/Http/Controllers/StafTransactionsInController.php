<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transactions_in;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class StafTransactionsInController extends Controller
{
    public function index(Request $request)
{
    $categoryId = $request->input('category');
    $supplierId = $request->input('supplier'); // Ambil ID supplier dari input

    $transaction_ins = Transactions_in::with('item', 'supplier')
        ->when($categoryId, function ($query) use ($categoryId) {
            $query->whereHas('item', function ($q) use ($categoryId) {
                $q->where('categories_id', $categoryId);
            });
        })
        ->when($supplierId, function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId); // Filter berdasarkan supplier_id
        })
        ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pembuatan terbaru
        ->paginate(10); // Tetap menggunakan paginasi
    
    $categories = Category::all();
    $suppliers = Supplier::all();

    return view('staff.Transactions_in.index', compact('transaction_ins', 'categories', 'suppliers'));
}

    public function create()
    {
        $items = Item::all();
        $suppliers = Supplier::all();

        return view('staff.Transactions_in.create', compact('items', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier harus dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah barang masuk wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal adalah 0.01.',
        ]);

        try {
            Transactions_in::create([
                'item_id' => $request->item_id,
                'supplier_id' => $request->supplier_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => now(), // Tanggal otomatis sesuai hari ini
            ]);

            $item = Item::find($request->item_id);
            $item->increment('stock', $request->jumlah);

            return redirect()->route('StafTransactions_in.index')
                ->with('success', 'Transaksi barang masuk berhasil disimpan dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyimpan transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('StafTransactions_in.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $items = Item::all();
        $suppliers = Supplier::all();

        return view('staff.Transactions_in.edit', compact('transactions_ins', 'items', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('StafTransactions_in.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier harus dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah barang masuk wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal adalah 0.01.',
        ]);

        try {
            $stokChange = $request->jumlah - $transactions_ins->jumlah;

            $transactions_ins->update([
                'item_id' => $request->item_id,
                'supplier_id' => $request->supplier_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => now(), // Tanggal otomatis sesuai hari ini
            ]);

            $item = Item::find($transactions_ins->item_id);
            $item->increment('stock', $stokChange);

            return redirect()->route('StafTransactions_in.index')
                ->with('success', 'Transaksi barang masuk berhasil diperbarui dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memperbarui transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('StafTransactions_in.index')
                ->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            $item = Item::find($transactions_ins->item_id);
            $item->decrement('stock', $transactions_ins->jumlah);

            $transactions_ins->delete();

            return redirect()->route('StafTransactions_in.index')
                ->with('success', 'Transaksi barang masuk berhasil dihapus dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi. Silakan coba lagi.']);
        }
    }
}
