<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Pencarian kategori berdasarkan nama
        $search = $request->input('search');
        $catego = Category::where('name', 'like', "%$search%")
                              ->paginate(10);  // Pagination, menampilkan 10 per halaman

        // Menampilkan kategori dengan pencarian dan pagination
        return view('Crud_admin.categories.index', compact('catego', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Crud_admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input nama kategori
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Menyimpan kategori baru ke database
        Category::create([
            'name' => $request->input('name'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('Crud_admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input nama kategori
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Update kategori dengan nama baru
        $category->update([
            'name' => $request->input('name'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Hapus kategori
        $category->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
