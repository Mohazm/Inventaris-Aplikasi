<?php

namespace App\Http\Controllers;

use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Tendik;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoansItemController extends Controller
{
    // Menampilkan semua loans_items
    public function index()
    {
        $loans_items = Loans_item::with(['item', 'tendik'])->get();
        return view('Crud_admin.loans_item.index', compact('loans_items'));
    }

    // Form untuk membuat loans_items baru
    public function create()
    {
        $items = Item::all(); // Ambil semua item
        $tendiks = Tendik::all(); // Ambil semua tendik
        return view('Crud_admin.loans_item.create', compact('items', 'tendiks'));
    }

    // Menyimpan data loans_items baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'tendik_id' => 'required|exists:tendiks,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tujuan_peminjaman' => 'required|string|max:255',
        ]);

        // Kurangi stok item berdasarkan jumlah pinjaman
        $item = Item::findOrFail($request->item_id);
        if ($item->stock < $request->jumlah_pinjam) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }
        $item->stock -= $request->jumlah_pinjam;
        $item->save();

        // Tambahkan peminjaman dengan status 'loading'
        $validated['status'] = 'loading';
        Loans_item::create($validated);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibuat dengan status loading.');
    }

    // Form untuk mengedit data loans_items
    public function edit($id)
    {
        $loans_items = Loans_item::findOrFail($id);
        $items = Item::all(); // Ambil semua item
        $tendiks = Tendik::all(); // Ambil semua tendik
        return view('Crud_admin.loans_item.edit', compact('loans_items', 'items', 'tendiks'));
    }

    // Memperbarui data loans_items
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_id' => 'exists:items,id',
            'tendik_id' => 'exists:tendiks,id',
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date|after:tanggal_pinjam',
            'jumlah_pinjam' => 'integer|min:1',
            'tujuan_peminjaman' => 'string|max:255',
        ]);

        $loans_items = Loans_item::findOrFail($id);

        // Update stok jika jumlah pinjaman berubah
        if ($request->has('jumlah_pinjam')) {
            $item = Item::findOrFail($request->item_id);
            $stok_dikembalikan = $loans_items->jumlah_pinjam - $request->jumlah_pinjam;
            $item->stock += $stok_dikembalikan;
            $item->save();
        }

        $loans_items->update($validated);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // Menghapus data loans_items
    public function destroy($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        // Kembalikan stok ke item
        $item = Item::findOrFail($loans_items->item_id);
        $item->stock += $loans_items->jumlah_pinjam;
        $item->save();

        $loans_items->delete();

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    // Menerima loans_items (ubah status menjadi 'di pinjam')
    public function accept($id)
    {
        $loans_items = Loans_item::findOrFail($id);
        $item = Item::findOrFail($loans_items->item_id); // Ambil data barang terkait
    
        // Periksa apakah status sudah diproses sebelumnya
        if ($loans_items->status !== 'loading') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah diproses.']);
        }
    
        // Validasi stok
        if ($item->stock < $loans_items->quantity) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }
    
        // Kurangi stok barang
        $item->stock -= $loans_items->quantity;
        $item->save();
    
        // Ubah status menjadi "dipakai"
        $loans_items->status = 'dipakai';
        $loans_items->save();
    
        return redirect()->route('loans_item.index')->with('success', 'Peminjaman diterima.');
    }
    


    // Membatalkan loans_items
    // Membatalkan loans_items
    // Membatalkan loans_items
    public function cancel($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status === 'ditolak') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah dibatalkan sebelumnya.']);
        }

        // Kembalikan stok ke item
        $item = Item::findOrFail($loans_items->item_id);
        $item->stock += $loans_items->jumlah_pinjam;
        $item->save();

        // Update status menjadi 'ditolak'
        $loans_items->status = 'ditolak';
        $loans_items->save();

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    // Mengecek peminjaman yang melebihi batas waktu dan mengubah status
    public function checkOverdueLoans()
    {
        $overdueLoans = Loans_item::where('status', 'dipakai')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->get();

        foreach ($overdueLoans as $loans_items) {
            // Kembalikan stok barang
            $item = Item::findOrFail($loans_items->item_id);
            $item->stock += $loans_items->jumlah_pinjam;
            $item->save();

            // Update status peminjaman menjadi 'selesai'
            $loans_items->status = 'selesai';
            $loans_items->save();
        }

        return redirect()->route('loans_item.index')->with('success', 'Status peminjaman yang melewati batas waktu berhasil diperbarui.');
    }
}
