<?php

namespace App\Http\Controllers;

use App\Models\Detail_Item;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrower;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueLoanNotification;

class LoansItemController extends Controller
{
    // Menampilkan semua loans_items
    public function index(Request $request)
    {
        $query = Loans_item::with(['items.category', 'borrowers.student', 'borrowers.teacher']);

        $tanggal_pinjam_options = Loans_item::distinct()->pluck('tanggal_pinjam');
        $tanggal_kembali_options = Loans_item::distinct()->pluck('tanggal_kembali');

        if ($request->has(['tanggal_pinjam', 'tanggal_kembali']) && $request->tanggal_pinjam && $request->tanggal_kembali) {
            $query->where('tanggal_pinjam', $request->tanggal_pinjam)
                ->where('tanggal_kembali', $request->tanggal_kembali)
                ->whereDate('tanggal_kembali', '<', now());
        }

        $loans_items = Loans_item::with('items')->get();

        return view('Crud_admin.loans_item.index', compact(
            'loans_items',
            'tanggal_pinjam_options',
            'tanggal_kembali_options'
        ));
    }

    public function create()
    {
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');

        $items = Item::whereIn('categories_id', $categoryIds)
            ->where('status_pinjaman', 'bisa di pinjam')
            ->get()
            ->filter(function ($item) {
                return $item->details()->where('kondisi_barang', 'normal')->count() > 0;
            });

        $borrowers = Borrower::all();

        return view('Crud_admin.loans_item.create', compact('items', 'borrowers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'detail_item_ids' => 'required|array',
            'detail_item_ids.*' => 'exists:detail_items,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'tujuan_peminjaman' => 'required|string|max:255',
            'jumlah_pinjam' => 'required|integer|min:1',
        ]);

        // Buat Loans_item baru
        $loan = Loans_item::create([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'status' => 'menunggu',
        ]);

        // Sinkronisasi data pivot table
        $loan->detailItems()->sync($request->detail_item_ids);

        // Update stok item
        $item = Item::findOrFail($request->item_id);
        $item->decrement('stock', $request->jumlah_pinjam);

        // Update status pada detail items yang dipilih
        foreach ($request->detail_item_ids as $detailItemId) {
            Detail_Item::where('id', $detailItemId)->update(['status_pinjaman' => 'sedang dipinjam']);
        }

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }



    public function accept($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status != 'menunggu') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah diproses sebelumnya.']);
        }

        $loans_items->update(['status' => 'dipakai']);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman diterima.');
    }

    public function cancel($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        if ($loans_items->status === 'ditolak') {
            return redirect()->back()->withErrors(['error' => 'Peminjaman sudah dibatalkan.']);
        }

        $item = Item::findOrFail($loans_items->item_id);
        $item->increment('stock', $loans_items->jumlah_pinjam);

        $loans_items->update(['status' => 'ditolak']);

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function checkOverdueLoans()
    {
        $overdueLoans = Loans_item::where('status', 'dipakai')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->get();

        foreach ($overdueLoans as $loan) {
            $loan->update(['status' => 'terlambat']);

            $borrower = $loan->borrower;

            if ($borrower && $borrower->student && $borrower->student->email) {
                Mail::to($borrower->student->email)->send(new OverdueLoanNotification($loan));
            }

            if ($borrower && $borrower->teacher && $borrower->teacher->email) {
                Mail::to($borrower->teacher->email)->send(new OverdueLoanNotification($loan));
            }
        }

        return redirect()->route('loans_item.index')->with('success', 'Peminjaman terlambat berhasil diperbarui.');
    }


    public function destroy($id)
    {
        // Cari data loans_item berdasarkan ID
        $loanItem = Loans_item::with('items')->find($id);

        // Jika data tidak ditemukan, kembalikan pesan error
        if (!$loanItem) {
            return redirect()->route('loans_item.index')
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Mengembalikan stok barang
        if ($loanItem->items) {
            $loanItem->items->stock += $loanItem->jumlah_pinjam; // Tambahkan stok
            $loanItem->items->save(); // Simpan perubahan stok
        }

        // Hapus data peminjaman
        $loanItem->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('loans_item.index')
            ->with('success', 'Data peminjaman berhasil dihapus, stok barang dikembalikan.');
    }
    public function show($id)
    {
        $loanItem = Loans_item::with(['items', 'borrowers.student', 'borrowers.teacher', 'detailItems'])->findOrFail($id);
        // dd($loanItem->detailItems); // Cek apakah data sudah dimuat
        // dd($loanItem->detailItems->toArray()); // Lihat data terkait detailItems
        return view('Crud_admin.loans_item.detail', compact('loanItem'));
    }


}
