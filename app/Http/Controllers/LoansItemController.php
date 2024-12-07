<?php

namespace App\Http\Controllers;

use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Category;
use App\Models\Tendik;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoansItemController extends Controller
{
    // Menampilkan semua loans_items
    public function index()
    {
        $loans_items = Loans_item::with(['item.category', 'tendik'])->get();
        return view('Crud_admin.loans_item.index', compact('loans_items'));
    }

    // Form untuk membuat loans_items baru
    public function create()
    {
        // Filter kategori yang diizinkan
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');

        // Ambil item yang sesuai kategori
        $items = Item::whereIn('categories_id', $categoryIds)->get();

        // Ambil semua tendik
        $tendiks = Tendik::all();

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

        // Validasi kategori barang
        $item = Item::with('category')->findOrFail($request->item_id);
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        if (!in_array($item->category->name, $allowedCategories)) {
            return redirect()->back()->withErrors(['error' => 'Barang hanya bisa dipinjam jika termasuk kategori Kebersihan, Olah Raga, atau Elektronik.']);
        }

        // Kurangi stok item berdasarkan jumlah pinjaman
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

        // Filter kategori yang diizinkan
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');

        // Ambil item yang sesuai kategori
        $items = Item::whereIn('categories_id', $categoryIds)->get();

        // Ambil semua tendik
        $tendiks = Tendik::all();

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
        $item = Item::with('category')->findOrFail($request->item_id);

        // Validasi kategori barang
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        if (!in_array($item->category->name, $allowedCategories)) {
            return redirect()->back()->withErrors(['error' => 'Barang hanya bisa dipinjam jika termasuk kategori Kebersihan, Olah Raga, atau Elektronik.']);
        }

        // Update stok jika jumlah pinjaman berubah
        if ($request->has('jumlah_pinjam')) {
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

    // Menerima loans_items (ubah status menjadi 'dipakai')
    public function accept($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status !== 'loading') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah diproses.']);
        }

        $item = Item::findOrFail($loans_items->item_id);

        if ($item->stock < $loans_items->jumlah_pinjam) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }

        $item->stock -= $loans_items->jumlah_pinjam;
        $item->save();

        $loans_items->status = 'dipakai';
        $loans_items->save();

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman diterima.');
    }

    // Membatalkan loans_items
    public function cancel($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status === 'ditolak') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah dibatalkan sebelumnya.']);
        }

        $item = Item::findOrFail($loans_items->item_id);
        $item->stock += $loans_items->jumlah_pinjam;
        $item->save();

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
            $item = Item::findOrFail($loans_items->item_id);
            $item->stock += $loans_items->jumlah_pinjam;
            $item->save();

            $loans_items->status = 'selesai';
            $loans_items->save();
        }

        return redirect()->route('loans_item.index')->with('success', 'Status peminjaman yang melewati batas waktu berhasil diperbarui.');
    }
}
