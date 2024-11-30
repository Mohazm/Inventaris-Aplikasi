<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input data dengan pesan kustom
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.string' => 'Nama supplier harus berupa teks.',
            'nama_supplier.max' => 'Nama supplier tidak boleh lebih dari 255 karakter.',
            'alamat.required' => 'Alamat supplier wajib diisi.',
            'alamat.string' => 'Alamat supplier harus berupa teks.',
            'kontak.string' => 'Kontak supplier harus berupa teks.',
            'kontak.max' => 'Kontak supplier tidak boleh lebih dari 255 karakter.',
            'catatan.string' => 'Catatan harus berupa teks.',
        ]);

        try {
            // Menyimpan data supplier baru
            Supplier::create($request->all());

            // Redirect dengan pesan sukses
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('suppliers.index')->with('error', 'Terjadi kesalahan saat menambahkan supplier.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validasi input data dengan pesan kustom
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.string' => 'Nama supplier harus berupa teks.',
            'nama_supplier.max' => 'Nama supplier tidak boleh lebih dari 255 karakter.',
            'alamat.required' => 'Alamat supplier wajib diisi.',
            'alamat.string' => 'Alamat supplier harus berupa teks.',
            'kontak.string' => 'Kontak supplier harus berupa teks.',
            'kontak.max' => 'Kontak supplier tidak boleh lebih dari 255 karakter.',
            'catatan.string' => 'Catatan harus berupa teks.',
        ]);

        try {
            // Update data supplier
            $supplier->update($request->all());

            // Redirect dengan pesan sukses
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('suppliers.index')->with('error', 'Terjadi kesalahan saat memperbarui supplier.');
        }
    }
}
