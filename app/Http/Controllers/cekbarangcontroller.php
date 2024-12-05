<?php
namespace App\Http\Controllers;

use App\Models\CekBarang;
use App\Models\Item;
use Illuminate\Http\Request;

class cekbarangcontroller extends Controller
{
    public function index()
    {
        // Eager load items to avoid N+1 query problem
        $cekBarang = CekBarang::with('item')->get(); // Using 'item' relationship
        return view('crud_admin.cek_barang.index', compact('cekBarang'));
    }

    public function create()
    {
        $items = Item::all(); // Retrieve all items
        return view('crud_admin.cek_barang.create', compact('items')); // Pass items to the create view
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'item_id' => 'required|exists:items,id', // Validation for item_id
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'descripsi' => 'required|string|max:255',
        ]);

        // Store the new CekBarang record
        CekBarang::create([
            'item_id' => $request->item_id,
            'kondisi_barang' => $request->kondisi_barang,
            'descripsi' => $request->descripsi,
        ]);

        // Redirect with success message
        return redirect()->route('cekbarang.index')->with('success', 'Data has been added successfully.');
    }

    public function show(CekBarang $cekBarang)
    {
        return view('crud_admin.cek_barang.show', compact('cekBarang'));
    }

        public function edit(CekBarang $id)
        {
            $items = Item::all(); // Retrieve all items for dropdown
            return view('crud_admin.cek_barang.edit', compact('id', 'items'));
        }

    public function update(Request $request, $id)
    {
        $cekbarang = CekBarang::findOrFail($id);
    
        $request->validate([
            'items_id' => 'required|exists:items,id',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'descripsi' => 'required|string|max:255',
        ]);
    
        $cekbarang->update([
            'items_id' => $request->items_id,
            'kondisi_barang' => $request->kondisi_barang,
            'descripsi' => $request->descripsi,
        ]);
    
        return redirect()->route('cekbarang.index')->with('success', 'Data updated successfully!');
    }
    

    public function destroy(CekBarang $cekBarang)
    {
        $cekBarang->delete();
        return redirect()->route('cekbarang.index')->with('success', 'Data has been deleted successfully.');
    }
}
