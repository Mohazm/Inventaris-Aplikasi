<?php

// use Carbon\Carbon;
namespace App\Http\Controllers;
// use App\Notifications\OverdueLoanNotification;

use App\Models\User;
use App\Models\Student; // Pastikan mengimpor model Student
use App\Models\Teacher; // Pastikan mengimpor model Teacher

use App\Mail\OverdueLoanNotification;
use App\Models\Loans_item;
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueLoanMail; // Pastikan sudah membuat Mail class

class staffLoansItemController extends Controller
{
    // Menampilkan semua loans_items
    public function index(Request $request)
    {
        $query = Loans_item::with(['item.category', 'borrower.student', 'borrower.teacher']); // Memuat relasi student dan teacher pada borrower

        // Ambil daftar tanggal unik untuk dropdown
        $tanggal_pinjam_options = Loans_item::select('tanggal_pinjam')->distinct()->pluck('tanggal_pinjam');
        $tanggal_kembali_options = Loans_item::select('tanggal_kembali')->distinct()->pluck('tanggal_kembali');

        // Filter berdasarkan pilihan dropdown
        if (
            $request->has(['tanggal_pinjam', 'tanggal_kembali']) &&
            $request->tanggal_pinjam != null &&
            $request->tanggal_kembali != null
        ) {
            // Filter data berdasarkan pilihan
            $query->where('tanggal_pinjam', $request->tanggal_pinjam)
                ->where('tanggal_kembali', $request->tanggal_kembali)
                ->whereDate('tanggal_kembali', '<', now()); // Cek tanggal kembali sudah lewat
        }

        $loans_items = $query->get();

        return view('staff.Loans_item.index', compact(
            'loans_items',
            'tanggal_pinjam_options',
            'tanggal_kembali_options'
        ));
    }



    // Form untuk membuat loans_items baru
    public function create()
    {
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');

        // Filter berdasarkan kategori, status_pinjaman, dan kondisi_barang
        $items = Item::whereIn('categories_id', $categoryIds)
            ->where('status_pinjaman', 'bisa di pinjam')
            ->where('Kondisi_barang', 'normal')
            ->get();

        $borrowers = Borrower::all();

        return view('staff.Loans_item.create', compact('items', 'borrowers'));
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
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->status_pinjaman != 'bisa di pinjam' || $item->Kondisi_barang != 'normal') {
            return redirect()->back()->withErrors(['error' => 'Barang tidak memenuhi syarat untuk dipinjam.']);
        }

        // Validasi tambahan
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        if (!in_array($item->category->name, $allowedCategories)) {
            return redirect()->back()->withErrors(['error' => 'Barang tidak termasuk dalam kategori yang diizinkan.']);
        }

        if ($item->stock < $request->jumlah_pinjam) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
        }

        $borrower = Borrower::with(['student', 'teacher'])->findOrFail($request->borrower_id);

        // Validasi tambahan jika diperlukan untuk borrower
        if (!$borrower->student && !$borrower->teacher) {
            return redirect()->back()->withErrors(['error' => 'Peminjam tidak valid.']);
        }

        $item->decrement('stock', $request->jumlah_pinjam);

        Loans_item::create([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'status' => 'menunggu',
        ]);

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dibuat dengan status menunggu.');
    }



    // Form untuk mengedit data loans_items
    public function edit($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        // Hanya menampilkan kategori yang diperbolehkan
        $allowedCategories = ['Kebersihan', 'Olah Raga', 'Elektronik'];
        $categoryIds = Category::whereIn('name', $allowedCategories)->pluck('id');

        // Barang harus memiliki status pinjaman 'bisa di pinjam' dan kondisi 'normal'
        $items = Item::whereIn('categories_id', $categoryIds)
            ->where('status_pinjaman', 'bisa di pinjam')
            ->where('Kondisi_barang', 'normal')
            ->get();

        $borrowers = Borrower::all();

        return view('loans.edit', compact('loans_items', 'items', 'borrowers'));
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

        if ($item->status_pinjaman != 'bisa di pinjam' || $item->Kondisi_barang != 'normal') {
            return redirect()->back()->withErrors(['error' => 'Barang tidak memenuhi syarat untuk dipinjam.']);
        }

        $borrower = Borrower::with(['student', 'teacher'])->findOrFail($request->borrower_id);

        // Validasi tambahan jika diperlukan untuk borrower
        if (!$borrower->student && !$borrower->teacher) {
            return redirect()->back()->withErrors(['error' => 'Peminjam tidak valid.']);
        }
        // Update stok berdasarkan perbedaan jumlah pinjam
        if ($loans_items->item_id == $request->item_id) {
            // Jika item yang sama, hanya update stok
            $item->stock += ($loans_items->jumlah_pinjam - $request->jumlah_pinjam);
        } else {
            // Jika item berbeda, kembalikan stok barang lama dan kurangi stok barang baru
            $oldItem = Item::findOrFail($loans_items->item_id);
            $oldItem->increment('stock', $loans_items->jumlah_pinjam);

            if ($request->jumlah_pinjam > $item->stock) {
                return redirect()->back()->withErrors(['error' => 'Jumlah pinjaman melebihi stok yang tersedia.']);
            }

            $item->decrement('stock', $request->jumlah_pinjam);
        }

        $item->save();

        // Update data loans_items
        $loans_items->update([
            'item_id' => $request->item_id,
            'borrower_id' => $request->borrower_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
        ]);

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

public function show($id)
{
    // Ambil data Loans_items dengan relasi
    $loanItem = Loans_Item::with([
        'item',           // Relasi ke Item
        'borrower.student', // Relasi Borrowers ke Students
        'borrower.teacher'  // Relasi Borrowers ke Teachers
    ])->findOrFail($id);

    // Kirim ke view
    return view('loans.detail', compact('loanItem'));
}

    // Menghapus data loans_items
    public function destroy($id)
    {
        $loans_items = Loans_item::findOrFail($id);

        $item = Item::findOrFail(id: $loans_items->item_id);
        $item->increment('stock', $loans_items->jumlah_pinjam);

        $loans_items->delete();

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    // Menerima loans_items
    // public function accept($id)
    // {
    //     $loans_items = Loans_item::findOrFail($id);

    //     if ($loans_items->status !== 'menunggu') {
    //         return redirect()->back()->withErrors(['error' => 'Peminjaman sudah diproses sebelumnya.']);
    //     }

    //     $loans_items->update(['status' => 'dipakai']);

    //     return redirect()->route('loans_item.index')->with('success', 'Peminjaman diterima dan status diubah menjadi "dipakai".');
    // }

    // Membatalkan loans_items
    // public function cancel($id)
    // {
    //     $loans_items = Loans_item::findOrFail($id);

    //     if ($loans_items->status === 'ditolak') {
    //         return redirect()->back()->withErrors(['error' => 'Peminjaman sudah dibatalkan sebelumnya.']);
    //     }

    //     $item = Item::findOrFail($loans_items->item_id);
    //     $item->increment('stock', $loans_items->jumlah_pinjam);

    //     $loans_items->update(['status' => 'ditolak']);

    //     return redirect()->route('loans_item.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    // }


    public function checkOverdueLoans()
{
    // Mengambil peminjaman yang belum selesai dan melebihi batas waktu
    $overdueLoans = Loans_item::where('status', 'dipakai')
        ->where('tanggal_kembali', '<', Carbon::now())
        ->get();

    // Update status peminjaman yang telat dan kirim email
    foreach ($overdueLoans as $loan) {
        // Cek apakah sudah telat
        if (Carbon::now()->gt(Carbon::parse($loan->tanggal_kembali))) {
            // Update status peminjaman menjadi terlambat
            $loan->update(['status' => 'terlambat']);

            // Mengakses borrower yang memiliki student dan teacher
            $borrower = $loan->borrower;

            // Kirim email kepada student jika ada
            if ($borrower && $borrower->student && $borrower->student->email) {
                Mail::to($borrower->student->email)->send(new OverdueLoanNotification($loan));
            }

            // Kirim email kepada teacher jika ada
            if ($borrower && $borrower->teacher && $borrower->teacher->email) {
                Mail::to($borrower->teacher->email)->send(new OverdueLoanNotification($loan));
            }
        }
    }

    // Redirect setelah proses update
    return redirect()->route('loans.index')->with('success', 'Peminjaman yang melebihi batas waktu berhasil diperbarui dan pemberitahuan telah dikirim.');
}

}
