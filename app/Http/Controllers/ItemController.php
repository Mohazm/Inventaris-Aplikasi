<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get(); // Load category relation
        return view('Crud_admin.Items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('Crud_admin.Items.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:1|max:99999',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'photo_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo_barang')) {
            $data['photo_barang'] = $request->file('photo_barang')->store('uploads/items', 'public');
        }

        Item::create($data);

        return redirect()->route('Items.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('Crud_admin.Items.edit', compact('item','categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:1|max:99999',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'photo_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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
    }

    public function destroy(Item $item)
    {
        if ($item->photo_barang) {
            Storage::disk('public')->delete($item->photo_barang);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Produk berhasil dihapus.');
    }
}
