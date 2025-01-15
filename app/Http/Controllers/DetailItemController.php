<?php

namespace App\Http\Controllers;

use App\Models\Detail_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetailItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($itemId)
    {
        try {
            // Ambil semua detail barang berdasarkan item_id dengan paginasi
            $detail_items = Detail_item::where('item_id', $itemId)->simplePaginate(10);

            // Cek apakah data kosong
            if ($detail_items->isEmpty()) {
                return redirect()->route('items.index')
                    ->with('warning', 'Tidak ada detail barang ditemukan untuk produk ini.');
            }

            return view('Crud_admin.Detail_item.index', compact('detail_items'));
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            Log::error('Kesalahan saat mengambil data detail barang: ' . $e->getMessage());

            // Redirect dengan pesan kesalahan
            return redirect()->route('Items.index')
                ->withErrors(['error' => 'Terjadi kesalahan saat mengambil data.']);
        }
    }
    // public function updateStatusPinjam(Request $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $detailItem = Detail_item::where('kode_barang', $request->kode_barang)->first();

    //     if (!$detailItem) {
    //         Log::error('Detail item tidak ditemukan.');
    //         return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan.'], 404);
    //     }

    //     Log::info('Status Sebelum Update:', ['status_pinjaman' => $detailItem->status_pinjaman]);

    //     if (trim(strtolower($detailItem->status_pinjaman)) === 'belum di pinjam') {
    //         $detailItem->status_pinjaman = 'Sedang dipinjam';
    //         $detailItem->save();

    //         Log::info('Status Setelah Update:', ['status_pinjaman' => $detailItem->status_pinjaman]);

    //         return response()->json(['success' => true, 'message' => 'Status pinjaman berhasil diperbarui.']);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Barang sudah dipinjam'], 400);
    // }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_barang)
    {
        try {
            // Cari detail barang berdasarkan kode_barang
            $detail_items = Detail_item::where('kode_barang', $kode_barang)->first();

            if (!$detail_items) {
                return redirect()->route('details.index')->withErrors(['error' => 'Detail barang tidak ditemukan.']);
            }

            return view('Crud_admin.Detail_item.edit', compact('detail_items'));
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengambil data detail barang untuk edit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengambil data untuk edit.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_barang)
    {
        $request->validate([
            'kondisi_barang' => 'required|in:Normal,Rusak',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            // Cari detail barang berdasarkan kode_barang
            $detail_items = Detail_item::where('kode_barang', $kode_barang)->first();

            if (!$detail_items) {
                return redirect()->route('details.index')->withErrors(['error' => 'Detail barang tidak ditemukan.']);
            }

            // Update data detail barang
            $detail_items->update($request->only('kondisi_barang', 'deskripsi'));

            return redirect()->route('details.index', ['itemId' => $detail_items->item_id])
                ->with('success', 'Detail barang berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memperbarui detail barang: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_barang)
    {
        try {
            // Cari detail barang berdasarkan kode_barang
            $detail_items = Detail_item::where('kode_barang', $kode_barang)->first();

            if (!$detail_items) {
                return redirect()->route('details.index')->withErrors(['error' => 'Detail barang tidak ditemukan.']);
            }

            // Hapus data detail barang
            $detail_items->delete();

            return redirect()->route('details.index', ['itemId' => $detail_items->item_id])
                ->with('success', 'Detail barang berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus detail barang: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data.']);
        }
    }
}
