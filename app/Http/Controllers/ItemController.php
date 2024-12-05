<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dan search dari request
        $filterCategory = $request->input('category_id');
        $searchQuery = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 items per page

        // Query item dengan relasi kategori
        $itemsQuery = Item::with('category');

        // Filter berdasarkan kategori
        if ($filterCategory) {
            $itemsQuery->where('categories_id', $filterCategory);
        }

        // Pencarian berdasarkan nama barang
        if ($searchQuery) {
            $itemsQuery->where('nama_barang', 'like', '%' . $searchQuery . '%');
        }

        // Pagination
        $items = $itemsQuery->paginate($perPage);

        // Ambil semua kategori untuk dropdown filter
        $categories = Category::all();

        return view('Crud_admin.Items.index', compact('items', 'categories', 'filterCategory', 'searchQuery', 'perPage'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('Crud_admin.Items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer', // Ubah min:1 menjadi min:0
            // 'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'photo_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.string' => 'Nama barang harus berupa teks.',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter.',
            'categories_id.exists' => 'Kategori yang dipilih tidak valid.',
            'stock.required' => 'Stok barang wajib diisi.',
            'stock.integer' => 'Stok barang harus berupa angka.',
            'stock.min' => 'Stok barang minimal adalah 0.', // Pesan untuk stok minimal 0
            'stock.max' => 'Stok barang maksimal adalah 99999.',
            // 'kondisi_barang.required' => 'Kondisi barang wajib dipilih.',
            // 'kondisi_barang.in' => 'Kondisi barang tidak valid.',
            'photo_barang.image' => 'File foto harus berupa gambar.',
            'photo_barang.mimes' => 'File foto harus berformat jpeg, png, atau jpg.',
            'photo_barang.max' => 'Ukuran file foto maksimal 2MB.',
        ]);
        

        try {
            $data = $request->all();

            // Upload photo
            if ($request->hasFile('photo_barang')) {
                $data['photo_barang'] = $request->file('photo_barang')->store('uploads/items', 'public');
            }

            Item::create($data);

            return redirect()->route('Items.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyimpan produk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('Items.index')->withErrors(['error' => 'Produk tidak ditemukan.']);
        }

        $categories = Category::all();
        return view('Crud_admin.Items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('Items.index')->withErrors(['error' => 'Produk tidak ditemukan.']);
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer', // Ubah min:1 menjadi min:0
            // 'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'photo_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.string' => 'Nama barang harus berupa teks.',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter.',
            'categories_id.exists' => 'Kategori yang dipilih tidak valid.',
            'stock.required' => 'Stok barang wajib diisi.',
            'stock.integer' => 'Stok barang harus berupa angka.',
            'stock.min' => 'Stok barang minimal adalah 0.', // Pesan untuk stok minimal 0
            'stock.max' => 'Stok barang maksimal adalah 99999.',
            // 'kondisi_barang.required' => 'Kondisi barang wajib dipilih.',
            // 'kondisi_barang.in' => 'Kondisi barang tidak valid.',
            'photo_barang.image' => 'File foto harus berupa gambar.',
            'photo_barang.mimes' => 'File foto harus berformat jpeg, png, atau jpg.',
            'photo_barang.max' => 'Ukuran file foto maksimal 2MB.',
        ]);
        
        try {
            $data = $request->all();

            // Handle photo update
            if ($request->hasFile('photo_barang')) {
                if ($item->photo_barang) {
                    Storage::disk('public')->delete($item->photo_barang);
                }
                $data['photo_barang'] = $request->file('photo_barang')->store('uploads/items', 'public');
            }

            $item->update($data);

            return redirect()->route('Items.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memperbarui produk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('Items.index')->withErrors(['error' => 'Produk tidak ditemukan.']);
        }

        try {
            if ($item->photo_barang) {
                Storage::disk('public')->delete($item->photo_barang);
            }

            $item->delete();

            return redirect()->route('Items.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menghapus produk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
        }
    }
}
