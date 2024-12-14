<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\models\Transactions_in;
use App\models\Transactions_out;
// use Illuminate\Http\Request;
use Carbon\Carbon;
class StafItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m')); 
        // Ambil filter dan search dari request
        $transactionsins = Transactions_in::whereYear('tanggal_masuk', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_masuk', substr($currentMonth, 5, 2))
            ->with('item')
            ->simplePaginate(1);

        $transactionsouts = Transactions_out::whereYear('tanggal_keluar', substr($currentMonth, 0, 4))
            ->whereMonth('tanggal_keluar', substr($currentMonth, 5, 2))
            ->with('item')
            ->simplePaginate(1);

        return view('staff.index', compact('transactionsins', 'transactionsouts', 
        'currentMonth'));
    }
    
    public function list(Request $request) {
        $filterCategory = $request->input('category_id');
        $searchQuery = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 items per page
        // Query item dengan relasi kategori
        $itemsQuery = Item::with('category');

        $filterkondisi = $request->input('Kondisi_barang');
        if ($filterkondisi) {
            $itemsQuery->where('Kondisi_barang', $filterkondisi);
        }

        $filterKondisi = $request->input('Kondisi_barang');

        // Query Item
        $query = Item::query();
        if ($filterKondisi) {
            $query->where('Kondisi_barang', $filterKondisi);
        }

        // Hitung total barang
        $countNormal = Item::where('Kondisi_barang', 'normal')->count();
        $countRusak = Item::where('Kondisi_barang', 'barang rusak')->count();

        // Ambil hasil berdasarkan filter
        $item = $query->paginate(10); // Count the items where Kondisi_barang is 'normal'  

        // Filter berdasarkan kategori
        if ($filterCategory) {
            $itemsQuery->where('categories_id', $filterCategory);
        }

        // Pencarian berdasarkan nama barang
        if ($searchQuery) {
            $itemsQuery->where('nama_barang', 'like', '%' . $searchQuery . '%');
        }

        // Urutkan berdasarkan item yang terakhir kali dibuat (created_at descending)
        $itemsQuery->orderBy('created_at', 'desc');

        // Pagination
        $items = $itemsQuery->paginate($perPage);

        // Ambil semua kategori untuk dropdown filter
        $categories = Category::all();

        return view('staff.Items.index', compact('items','countNormal','countRusak','item', 'categories', 'filterCategory', 'searchQuery', 'perPage'));
    }
}
