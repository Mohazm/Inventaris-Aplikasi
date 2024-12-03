<?php

namespace App\Http\Controllers;

use App\Models\Tendik;
use Illuminate\Http\Request;

class TendikController extends Controller
{
    // Menampilkan semua data tendik
    public function index()
    {
        $tendiks = Tendik::all();
        return view('Crud_admin.Tendiks.index', compact('tendiks'));
    }

    // Menampilkan form tambah data tendik
    public function create()
    {
        return view('Crud_admin.Tendiks.create');
    }

    // Menyimpan data tendik baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        Tendik::create($validated);
        return redirect()->route('tendiks.index')->with('success', 'Data tendik berhasil ditambahkan.');
    }

    // Menampilkan form edit data tendik
    public function edit($id)
    {
        $tendik = Tendik::findOrFail($id);
        return view('Crud_admin.Tendiks.edit', compact('tendik'));
    }

    // Memperbarui data tendik
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        $tendik = Tendik::findOrFail($id);
        $tendik->update($validated);

        return redirect()->route('tendiks.index')->with('success', 'Data tendik berhasil diperbarui.');
    }

    // Menghapus data tendik
    public function destroy($id)
    {
        $tendik = Tendik::findOrFail($id);
        $tendik->delete();

        return redirect()->route('tendiks.index')->with('success', 'Data tendik berhasil dihapus.');
    }
}
