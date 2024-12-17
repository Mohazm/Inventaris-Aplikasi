<?php
// app/Http/Controllers/StudentController.php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Borrower;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Menyimpan data siswa baru.
     */
    public function create() {
        return view('Crud_admin.borrowers.student.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string',
            'class' => 'required|string'
        ]);
    
        // Menambahkan siswa baru
        $student = Student::create($request->all());
    
        // Menambahkan siswa ke tabel borrowers
        $borrower = Borrower::create([
            'borrower_type' => 'student',  // Menandakan tipe peminjam adalah siswa
            'borrower_id' => $student->id, // Menyimpan ID siswa ke borrower_id
            'name' => $student->name,      // Menyimpan nama siswa
        ]);
    
        // Setelah borrower dibuat, perbarui siswa dengan ID borrower
        $student->borrower_id = $borrower->id;
        $student->save();
    
        // Redirect ke halaman daftar peminjam (borrowers) dengan pesan sukses
        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'redirect' => route('borrowers.index'),
            'message' => 'berhasil',
        ], 201);
    }
    
    
}
