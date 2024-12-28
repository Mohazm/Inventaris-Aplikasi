<?php
// app/Http/Controllers/TeacherController.php
namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:teachers,email',
                'phone' => 'nullable|string',
            ]);
    
            // Menyimpan data guru
            $teacher = Teacher::create($request->all());
    
            // Menambahkan data guru ke tabel borrowers
            $borrower = Borrower::create([
                'borrower_type' => 'teacher',
                'borrower_id' => $teacher->id,
                'name' => $teacher->name,
            ]);
    
            // Menyimpan borrower_id di tabel teachers
            $teacher->borrower_id = $borrower->id;
            $teacher->save();
    
            return response()->json([
                'id' => $teacher->id,
                'name' => $teacher->name,
                'redirect' => route('staff.borrower.index'), // Sesuaikan dengan route index stafteacher
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage()); // Log error jika terjadi masalah
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
            ], 500);
        }
    }
    
    

    /**
     * Menampilkan form untuk mengedit data guru.
     */
    public function edit($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Guru tidak ditemukan');
        }

        return view('Crud_admin.borrowers.teacher.edit', compact('teacher'));
    }

    /**
     * Mengupdate data guru berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Guru tidak ditemukan');
        }

        $teacher->update($request->all());

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diupdate');
    }

    /**
     * Menghapus guru berdasarkan ID.
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Guru tidak ditemukan');
        }

        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus');
    }
}
