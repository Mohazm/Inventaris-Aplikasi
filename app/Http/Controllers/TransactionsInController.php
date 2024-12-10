<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transactions_in;
use App\Models\Detail_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Category;

class TransactionsInController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->input('category');
        $supplierId = $request->input('supplier');

        $transaction_ins = Transactions_in::with('item', 'supplier')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('item', function ($q) use ($categoryId) {
                    $q->where('categories_id', $categoryId);
                });
            })
            ->when($supplierId, function ($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->paginate(10);

        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('Crud_admin.Transactions_in.index', compact('transaction_ins', 'categories', 'suppliers'));
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
            'jumlah' => 'required|integer|min:1',
        ],
        [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'item_id.required' => 'Item wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
        ]);

        try {
            // Buat transaksi barang masuk
            $transactions_ins = Transactions_in::create($request->all());

            // Perbarui stok barang
            $item = Item::find($request->item_id);
            $item->increment('stock', $request->jumlah);

            // Tambahkan detail barang
            for ($i = 1; $i <= $request->jumlah; $i++) {
                Detail_item::create([
                    'item_id' => $request->item_id,
                    'kode_barang' => strtoupper(substr($item->nama_barang, 0, 3)) . '-' . Str::random(5),
                    'kondisi_barang' => 'Normal', // Default kondisi barang adalah Normal
                ]);
            }

            return redirect()->route('Transactions_in.index')->with('success', 'Transaksi barang masuk berhasil disimpan. Stok dan detail barang diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyimpan transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $transactions_ins = Transactions_in::find($id);

        if (!$transactions_ins) {
            return redirect()->route('Transactions_in.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
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
            'jumlah' => 'required|integer|min:1',
        ],
        [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berformat tanggal.',
            'item_id.required' => 'Barang wajib diisi.',
            'item_id.exists' => 'Barang yang dipilih tidak ditemukan.',
            'supplier_id.required' => 'Supplier wajib diisi.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak ditemukan.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal adalah 1.',
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
            return redirect()->route('Transactions_in.index')->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        try {
            // Kurangi stok barang sesuai jumlah pada transaksi sebelum menghapus
            $item = Item::find($transactions_ins->item_id);
            $item->decrement('stock', $transactions_ins->jumlah);

            // Hapus detail barang terkait
            Detail_item::where('item_id', $transactions_ins->item_id)
                ->limit($transactions_ins->jumlah)
                ->delete();

            $transactions_ins->delete();

            return redirect()->route('Transactions_in.index')->with('success', 'Transaksi barang masuk berhasil dihapus dan stok diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus transaksi barang masuk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi. Silakan coba lagi.']);
        }
    }
}
