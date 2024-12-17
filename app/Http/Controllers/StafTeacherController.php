<?php
// app/Http/Controllers/TeacherController.php
namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Borrower;
use Illuminate\Http\Request;

class StafTeacherController extends Controller
{
    /**
     * Menampilkan daftar semua guru.
     */

    /**
     * Menampilkan form untuk menambahkan guru baru.
     */
    public function create()
    {
        return view('staff.borrowers.teacher.create');
    }

    /**
     * Menyimpan data guru baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string',
        ]);
    
        // Menambahkan guru baru
        $teacher = Teacher::create($request->all());
    
        // Menambahkan guru ke tabel borrowers
        $borrower = Borrower::create([
            'borrower_type' => 'teacher',  // Menandakan tipe peminjam adalah siswa
            'borrower_id' => $teacher->id, // Menyimpan ID siswa ke borrower_id
            'name' => $teacher->name,      // Menyimpan nama siswa
        ]);

        $teacher->borrower_id = $borrower->id;
        $teacher->save();
    
        // Redirect ke halaman daftar guru dengan pesan sukses
        return redirect()->route('staff.borrower.index')->with('success', 'Guru berhasil ditambahkan dan menjadi peminjam');
    }
    

    /**
     * Menampilkan form untuk mengedit data guru.
     */
    public function edit($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('stafteachers.idex')->with('error', 'Guru tidak ditemukan');
        }

        return view('staff.borrowers.teacher.edit', compact('teacher'));
    }

    /**
     * Mengupdate data guru berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('stafteachers.idex')->with('error', 'Guru tidak ditemukan');
        }

        $teacher->update($request->all());

        return redirect()->route('stafteachers.idex')->with('success', 'Data guru berhasil diupdate');
    }

    /**
     * Menghapus guru berdasarkan ID.
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('stafteachers.idex')->with('error', 'Guru tidak ditemukan');
        }

        $teacher->delete();

        return redirect()->route('stafteachers.idex')->with('success', 'Guru berhasil dihapus');
    }
}
