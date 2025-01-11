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

        $loans_items = $query->get();

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
                return $item->details()->where('kondisi_barang', 'Normal')->count() > 0;
            });

        $borrowers = Borrower::all();

        return view('Crud_admin.loans_item.create', compact('items', 'borrowers'));
    }
    
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'item_id' => 'required',
        'item_id.*' => 'exists:items,id',
        'detail_item_ids' => 'required|array',
        'detail_item_ids.*' => 'exists:detail_items,id',
        'borrower_id' => 'required|exists:borrowers,id',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        'tujuan_peminjaman' => 'required|string|max:255',
        'jumlah_pinjam' => 'required|integer',
    ]);
    
    
    $detailItemIds = $request->detail_item_ids;
    
        $loan = Loans_item::create([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'status' => 'menunggu',
        ]);
    
        // Masukkan data ke pivot table loans_item_detail_items
        foreach ($detailItemIds as $detailItemId) {
            $loan->detailItems()->attach($detailItemId);
        }
        $itemIds->decrement('stock', $request->jumlah_pinjam);
    
   

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

    public function show($id)
    {
        $loan = Loans_item::with(['items', 'borrowers.student', 'borrowers.teacher'])->findOrFail($id);

        return view('loans_item.detail', compact('loan'));
    }
}
