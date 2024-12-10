<?php

namespace App\Http\Controllers;

use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoansItemController extends Controller
{
    // Menampilkan semua loans_items
    public function index()
    {
        $loans_items = Loans_item::with(['item.category', 'borrower'])->get();
        return view('Crud_admin.loans_item.index', compact('loans_items'));
    }

    // Form untuk membuat loans_items baru
    public function create()
    {
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');
        $items = Item::whereIn('categories_id', $categoryIds)->get();
        $borrowers = Borrower::all();

        return view('Crud_admin.loans_item.create', compact('items', 'borrowers'));
    }

    // Menyimpan data loans_items baru
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tujuan_peminjaman' => 'required|string|max:255',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'borrower_id.required' => 'Peminjam harus dipilih.',
            'borrower_id.exists' => 'Peminjam yang dipilih tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid.',
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tanggal_kembali.date' => 'Format tanggal kembali tidak valid.',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'jumlah_pinjam.required' => 'Jumlah barang yang dipinjam wajib diisi.',
            'jumlah_pinjam.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah_pinjam.min' => 'Jumlah barang minimal adalah 1.',
            'tujuan_peminjaman.required' => 'Tujuan peminjaman wajib diisi.',
            'tujuan_peminjaman.max' => 'Tujuan peminjaman maksimal 255 karakter.',
        ]);

        $item = Item::with('category')->findOrFail($request->item_id);
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];

        if (!in_array($item->category->name, $allowedCategories)) {
            return redirect()->back()->withErrors(['error' => 'Barang hanya bisa dipinjam jika termasuk kategori Kebersihan, Olah Raga, atau Elektronik.']);
        }

        if ($item->stock < $request->jumlah_pinjam) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }

        $item->decrement('stock', $request->jumlah_pinjam);

        Loans_item::create([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'status' => 'loading',
        ]);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibuat dengan status loading.');
    }

    // Form untuk mengedit data loans_items
    public function edit($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');
        $items = Item::whereIn('categories_id', $categoryIds)->get();
        $borrowers = Borrower::all();

        return view('Crud_admin.loans_item.edit', compact('loans_items', 'items', 'borrowers'));
    }

    // Memperbarui data loans_items
    public function update(Request $request, $id)
    {
        $loans_items = Loans_item::findOrFail($id);

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tujuan_peminjaman' => 'required|string|max:255',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'borrower_id.required' => 'Peminjam harus dipilih.',
            'borrower_id.exists' => 'Peminjam yang dipilih tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid.',
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tanggal_kembali.date' => 'Format tanggal kembali tidak valid.',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'jumlah_pinjam.required' => 'Jumlah barang yang dipinjam wajib diisi.',
            'jumlah_pinjam.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah_pinjam.min' => 'Jumlah barang minimal adalah 1.',
            'tujuan_peminjaman.required' => 'Tujuan peminjaman wajib diisi.',
            'tujuan_peminjaman.max' => 'Tujuan peminjaman maksimal 255 karakter.',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($loans_items->jumlah_pinjam !== $request->jumlah_pinjam) {
            $item->stock += ($loans_items->jumlah_pinjam - $request->jumlah_pinjam);
        }

        $item->save();

        $loans_items->update($request->all());

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // Menghapus data loans_items
    public function destroy($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        $item = Item::findOrFail($loans_items->item_id);
        $item->increment('stock', $loans_items->jumlah_pinjam);

        $loans_items->delete();

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    // Menerima loans_items
    public function accept($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status !== 'loading') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah diproses sebelumnya.']);
        }

        $loans_items->update(['status' => 'dipakai']);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman diterima dan status diubah menjadi "dipakai".');
    }

    // Membatalkan loans_items
    public function cancel($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status === 'ditolak') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah dibatalkan sebelumnya.']);
        }

        $item = Item::findOrFail($loans_items->item_id);
        $item->increment('stock', $loans_items->jumlah_pinjam);

        $loans_items->update(['status' => 'ditolak']);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    // Mengecek peminjaman yang melebihi batas waktu
    public function checkOverdueLoans()
    {
        $overdueLoans = Loans_item::where('status', 'dipakai')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->get();

        foreach ($overdueLoans as $loan) {
            $loan->update(['status' => 'selesai']);
        }

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman yang melebihi batas waktu berhasil diperbarui.');
    }
}
