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
            // Ambil semua detail barang berdasarkan item_id
            $detail_items = Detail_item::where('item_id', $itemId)->paginate(10);

            if ($detail_items->isEmpty()) {
                return redirect()->route('items.index')->withErrors(['error' => 'Tidak ada detail barang ditemukan untuk produk ini.']);
            }

            return view('Crud_admin.Detail_item.index', compact('detail_items'));
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengambil data detail barang: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengambil data.']);
        }
    }

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
