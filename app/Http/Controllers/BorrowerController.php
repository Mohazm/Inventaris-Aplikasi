<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $borrowers = Borrower::orderBy('created_at', 'desc')->get(); // Ambil semua data peminjam dengan urutan terbaru
    return view('Crud_admin.borrowers.index', compact('borrowers'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Crud_admin.borrowers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
        ]);

        // Coba untuk menyimpan data borrower
        try {
            $borrower = Borrower::create([
                'nama_peminjam' => $validated['nama_peminjam'],
                'no_telp' => $validated['no_telp'],
            ]);

            // Mengembalikan respons JSON jika berhasil
            return response()->json($borrower, 201);
        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan respons error dalam format JSON
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrower $borrower)
    {
        return view('Crud_admin.borrowers.show', compact('borrower'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrower $borrower)
    {
        return view('Crud_admin.borrowers.edit', compact('borrower'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrower $borrower)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'no_telp' => [
                'required',
                'digits:12', // Menyesuaikan format nomor telepon
                'regex:/^(?!-)\d{12}$/' // Validasi nomor telepon tidak boleh diawali dengan 08
            ],
        ], [
            'nama_peminjam.unique' => 'Nama sudah ada',
            'no_telp.regex' => 'Nomor telepon tidak boleh dimulai dengan 08',
            'no_telp.digits' => 'Nomor telepon harus terdiri dari 12 digit',
        ]);

        // Perbarui data peminjam
        $borrower->update($validated);

        // Redirect setelah berhasil
        return redirect()->route('borrowers.index')->with('success', 'Peminjam berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        return redirect()->route('borrowers.index')->with('success', 'Peminjam berhasil dihapus');
    }
}
