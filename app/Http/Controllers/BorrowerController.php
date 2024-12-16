<?php
// app/Http/Controllers/BorrowerController.php
namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    /**
     * Menampilkan semua peminjam.
     */
  // app/Http/Controllers/BorrowerController.php
  public function index(Request $request)
  {
      $filter = $request->get('filter', ''); // Ambil filter dari request, default 'Semua'
  
      if ($filter == 'student') {
          $borrowers = Borrower::with('student') // Memuat relasi student
                              ->where('borrower_type', 'student')
                              ->get();
      } elseif ($filter == 'teacher') {
          $borrowers = Borrower::with('teacher') // Memuat relasi teacher
                              ->where('borrower_type', 'teacher')
                              ->get();
      } else {
          $borrowers = Borrower::with(['student', 'teacher']) // Memuat kedua relasi
                              ->get();
      }
  
      // Mengambil data teacher dan student jika dibutuhkan untuk filter
      $teachers = Teacher::all(); 
      $students = Student::all(); 
  
    //   dd($borrowers);
      return view('Crud_admin.borrowers.index', compact('borrowers', 'teachers', 'students'));

  }
  
  


    /**
     * Menampilkan form untuk menambahkan peminjam baru.
     */
    public function create()
    {
        return view('Crud_admin.borrowers.create');
    }

    /**
     * Menyimpan data peminjam baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_type' => 'required|in:teacher,student',
            'borrower_id' => 'required|exists:teachers,id|exists:students,id',
            'name' => 'required|string',
        ]);

        // Tentukan peminjam berdasarkan tipe (teacher atau student)
        if ($request->borrower_type === 'teacher') {
            $borrowerable = Teacher::find($request->borrower_id);
        } else {
            $borrowerable = Student::find($request->borrower_id);
        }

        // Simpan peminjam ke dalam tabel borrowers dengan relasi polymorphic
        $borrower = Borrower::create([
            'borrower_type' => $request->borrower_type,
            'borrower_id' => $request->borrower_id,
            'name' => $request->name, // Nama peminjam
        ]);

        // Jika tipe peminjam adalah teacher atau student, buat relasi polymorphic
        $borrowerable->borrower()->save($borrower);

        // Redirect ke halaman daftar peminjam setelah berhasil
        return redirect()->route('borrowers.index')->with('success', 'Peminjam berhasil ditambahkan');
    }

    /**
     * Menampilkan detail peminjam berdasarkan tipe dan ID peminjam.
     */
    public function show($borrowerType, $borrowerId)
    {
        $borrower = Borrower::where('borrower_type', $borrowerType)
                            ->where('borrower_id', $borrowerId)
                            ->first();

        if (!$borrower) {
            return redirect()->route('borrowers.index')->with('error', 'Peminjam tidak ditemukan');
        }

        return view('borrowers.show', compact('borrower'));
    }

    /**
     * Menghapus peminjam berdasarkan tipe dan ID peminjam.
     */
    public function destroy($borrowerType, $borrowerId)
    {
        // Cari borrower berdasarkan tipe dan ID
        $borrower = Borrower::where('borrower_type', $borrowerType)
                            ->where('borrower_id', $borrowerId)
                            ->first();
    
        if (!$borrower) {
            return redirect()->route('borrowers.index')->with('error', 'Peminjam tidak ditemukan');
        }
    
        // Hapus borrower
        $borrower->delete();
    
        return redirect()->route('borrowers.index')->with('success', 'Peminjam berhasil dihapus');
    }
    
}
