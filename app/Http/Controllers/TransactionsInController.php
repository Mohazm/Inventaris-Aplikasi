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
 use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Str;
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
            ->orderBy('created_at', 'desc') // Tambahkan pengurutan berdasarkan transaksi terbaru
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
            'tanggal_masuk' => 'required|array',
            'tanggal_masuk.*' => 'required|date',
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:items,id',
            'supplier_id' => 'required|array',
            'supplier_id.*' => 'required|exists:suppliers,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ], [
            'tanggal_masuk.*.required' => 'Tanggal masuk wajib diisi.',
            'item_id.*.required' => 'Item wajib dipilih.',
            'supplier_id.*.required' => 'Supplier wajib dipilih.',
            'jumlah.*.required' => 'Jumlah wajib diisi.',
            'jumlah.*.integer' => 'Jumlah harus berupa angka.',
        ]);
        
        // Mulai transaksi database
        DB::beginTransaction();
    
        try {
            // Loop melalui data array untuk menyimpan setiap record
            foreach ($request->tanggal_masuk as $index => $tanggal_masuk) {
                $itemId = $request->item_id[$index];
                $supplierId = $request->supplier_id[$index];
                $jumlah = $request->jumlah[$index];
    
                // Ambil data item
                $item = Item::find($itemId);
    
                // Mengecek apakah item ditemukan
                if (!$item) {
                    return back()->withErrors(['item' => 'Barang tidak ditemukan.']);
                }
    
                // Mengecek apakah stok cukup untuk menambah barang
                if ($item->stock + $jumlah < 0) {
                    return back()->withErrors(['stock' => 'Stok barang tidak cukup.']);
                }
    
                // Generate kode_barang secara otomatis
                $kodeBarang = strtoupper(substr($item->nama_barang, 0, 3)) . '-' . Str::random(5);
    
                // Simpan transaksi barang masuk
                $transaction = Transactions_in::create([
                    'tanggal_masuk' => $tanggal_masuk,
                    'item_id' => $itemId,
                    'supplier_id' => $supplierId,
                    'jumlah' => $jumlah,
                    'kode_barang' => $kodeBarang, // Gunakan kode_barang yang di-generate
                ]);
    
                // Perbarui stok barang
                $item->increment('stock', $jumlah);
    
                // Tambahkan detail barang
                for ($i = 1; $i <= $jumlah; $i++) {
                    Detail_item::create([
                        'item_id' => $itemId,
                        'kode_barang' => $kodeBarang, // Gunakan kode_barang yang sama untuk detail
                        'kondisi_barang' => 'Normal',
                    ]);
                }
            }
    
            // Commit transaksi jika semuanya berhasil
            DB::commit();
    
            return redirect()->route('Transactions_in.index')->with('success', 'Transaksi barang masuk berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
    
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

        $request->validate(
            [
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
            ]
        );

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
