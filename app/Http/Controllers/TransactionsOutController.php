<?php

namespace App\Http\Controllers;

use App\Models\Transactions_out;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\support\Str;
use App\Models\Detail_item;
use Illuminate\Support\Facades\DB;
class TransactionsOutController extends Controller
{
    public function index()
{
    $transactions_outs = Transactions_out::with('item')
        ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal transaksi terbaru
        ->simplePaginate(5); // Pagination 5 items per halaman

    return view('Crud_admin.transactions_out.index', compact('transactions_outs'));
}

    public function create()
    {
        // Filter items dengan stok >= 1
        $items = Item::where('stock', '>=', 1)->get();

        return view('Crud_admin.transactions_out.create', compact('items'));
    }
    public function store(Request $request)
    {
        // Validasi input
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
            'jumlah' => 'required|numeric|min:1',
        ], [
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal keluar tidak valid.',
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'tujuan_keluar.required' => 'Tujuan keluar wajib diisi.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.numeric' => 'Jumlah barang keluar harus berupa angka.',
            'jumlah.min' => 'Jumlah barang keluar minimal adalah 1.',
        ]);
    
        // Temukan item berdasarkan ID
        $item = Item::findOrFail($request->item_id);
    
        // Validasi jika jumlah barang keluar lebih banyak dari stok yang ada
        if ($request->jumlah > $item->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.'])->withInput();
        }
    
        DB::beginTransaction();
        try {
            // Generate kode_barang otomatis
            $kodeBarang = strtoupper(substr($item->nama_barang, 0, 3)) . '-' . Str::random(5);
    
            // Simpan transaksi barang keluar
            $transactionOut = Transactions_out::create([
                'tanggal_keluar' => $request->tanggal_keluar,
                'item_id' => $request->item_id,
                'tujuan_keluar' => $request->tujuan_keluar,
                'jumlah' => $request->jumlah,
                'kode_barang' => $kodeBarang, // Simpan kode barang yang telah digenerate
            ]);
    
            // Ambil dan hapus data dari tabel Detail_item sesuai jumlah barang keluar
            $detailItems = Detail_item::where('item_id', $request->item_id)
                ->where('kondisi_barang', 'Normal')
                ->take($request->jumlah)
                ->get();
    
            if ($detailItems->count() < $request->jumlah) {
                return back()->withErrors(['error' => 'Jumlah kode barang di detail_item tidak mencukupi.'])->withInput();
            }
    
            // Loop dan hapus detail_item sesuai jumlah barang keluar
            foreach ($detailItems as $detail) {
                $detail->delete(); // Hapus kode_barang di Detail_item
            }
    
            // Kurangi stok barang setelah transaksi keluar
            $item->decrement('stock', $request->jumlah);
    
            DB::commit();
            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi.'])->withInput();
        }
    }
    


    public function edit($id)
    {
        $transaction_out = Transactions_out::findOrFail($id);

        // Filter items dengan stok >= 1
        $items = Item::where('stock', '>=', 1)->get();

        return view('Crud_admin.transactions_out.edit', compact('transaction_out', 'items'));
    }

    public function update(Request $request, $id)
    {
        $transaction_out = Transactions_out::findOrFail($id);

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

        $item = Item::findOrFail($transaction_out->item_id);

        // Perhitungan perubahan stok
        $stockChange = $request->jumlah - $transaction_out->jumlah;

        if ($stockChange > $item->stock) {
            return back()->withErrors(['error' => 'Jumlah barang keluar melebihi stok yang tersedia.'])->withInput();
        }

        try {
            $transaction_out->update($request->all());
            $item->decrement('stock', $stockChange);

            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaction_out = Transactions_out::findOrFail($id);

        try {
            $item = Item::findOrFail($transaction_out->item_id);
            $item->increment('stock', $transaction_out->jumlah);

            $transaction_out->delete();

            return redirect()->route('Transactions_out.index')->with('success', 'Transaksi barang keluar berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus transaksi barang keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi.']);
        }
    }
}
