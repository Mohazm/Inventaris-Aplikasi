<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StafItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return view('staff.Items.index', compact('items', 'categories', 'filterCategory', 'searchQuery', 'perPage'));
    }
 
}
