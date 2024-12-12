<?php

namespace App\Http\Controllers;

use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrower;
use Illuminate\Http\Request;

class StafLoansItemController extends Controller
{
    // Menampilkan daftar peminjaman yang diajukan staff
    public function index()
{
    $loans_items = Loans_item::with(['item.category', 'borrower'])
        ->where('status', '!=', 'ditolak') // Hanya tampilkan yang belum ditolak
        ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pembuatan terbaru
        ->get();

    return view('staff.loans_item.index', compact('loans_items'));
}


    // Form untuk membuat permintaan peminjaman
    public function create()
    {
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');
        $items = Item::whereIn('categories_id', $categoryIds)->get();
        $borrowers = Borrower::all(); // Mendapatkan daftar peminjam dari tabel Borrower

        return view('staff.loans_item.create', compact('items', 'borrowers'));
    }

    // Menyimpan permintaan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_id' => 'required|exists:borrowers,id', // Borrower harus valid
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tujuan_peminjaman' => 'required|string|max:255',
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'borrower_id.required' => 'Peminjam harus dipilih.',
            'borrower_id.exists' => 'Peminjam yang dipilih tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi.',
            'jumlah_pinjam.required' => 'Jumlah barang yang dipinjam wajib diisi.',
            'tujuan_peminjaman.required' => 'Tujuan peminjaman wajib diisi.',
        ]);

        $item = Item::with('category')->findOrFail($request->item_id);
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];

        if (!in_array($item->category->name, $allowedCategories)) {
            return redirect()->back()->withErrors(['error' => 'Barang hanya bisa dipinjam jika termasuk kategori Kebersihan, Olah Raga, atau Elektronik.']);
        }

        if ($item->stock < $request->jumlah_pinjam) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }

        Loans_item::create([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id, // Peminjam yang dipilih
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'status' => 'pending', // Status awal "pending"
        ]);

        return redirect()->route('staff_loans_item.index')->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    // Membatalkan permintaan peminjaman oleh staff
    public function cancel($id)
    {
        $loans_items = Loans_item::where('status', 'pending')
            ->findOrFail($id); // Hanya status pending yang bisa dibatalkan

        $loans_items->delete();

        return redirect()->route('staff_loans_item.index')->with('success', 'Permintaan peminjaman berhasil dibatalkan.');
    }
}
