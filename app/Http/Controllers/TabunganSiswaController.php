<?php
namespace App\Http\Controllers;

use App\Models\TabunganSiswa;
use Illuminate\Http\Request;

class TabunganSiswaController extends Controller
{
    public function index()
    {
        $tabungans = TabunganSiswa::latest()->paginate(10);
        return view('tabungan_siswa.index', compact('tabungans'));
    }

    public function create()
    {
        return view('tabungan_siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Siswa' => 'required|string|max:255',
            'Kelas' => 'required|string|max:100',
            'Alamat' => 'nullable|string|max:255',
            'jumlah_Yang_di_Tabung' => 'required|numeric|min:0',
            'Uang_Masuk' => 'nullable|numeric|min:0',
            'Uang_Keluar' => 'nullable|numeric|min:0',
        ]);
    
        // Membuat instance baru TabunganSiswa
        $tabunganSiswa = new TabunganSiswa();
        $tabunganSiswa->Nama_Siswa = $request->Nama_Siswa;
        $tabunganSiswa->Kelas = $request->Kelas;
        $tabunganSiswa->Alamat = $request->Alamat;
        $tabunganSiswa->jumlah_Yang_di_Tabung = $request->jumlah_Yang_di_Tabung;
        
        // Menghitung jumlah tabungan
        $tabunganSiswa->jumlah_Tabungan = $request->jumlah_Yang_di_Tabung + $request->Uang_Masuk - $request->Uang_Keluar;
        
        // Menyimpan uang masuk dan keluar
        $tabunganSiswa->Uang_Masuk = $request->Uang_Masuk ?? 0;
        $tabunganSiswa->Uang_Keluar = $request->Uang_Keluar ?? 0;
        
        $tabunganSiswa->save();
    
        return redirect()->route('Tabungan.Siswa.index')->with('success', 'Tabungan berhasil ditambahkan!');
    }
    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Siswa' => 'required|string|max:255',
            'Kelas' => 'required|string|max:100',
            'Alamat' => 'nullable|string|max:255',
            'jumlah_Yang_di_Tabung' => 'required|numeric|min:0',
            'Uang_Masuk' => 'nullable|numeric|min:0',
            'Uang_Keluar' => 'nullable|numeric|min:0',
        ]);
    
        $tabunganSiswa = TabunganSiswa::findOrFail($id);
        $tabunganSiswa->Nama_Siswa = $request->Nama_Siswa;
        $tabunganSiswa->Kelas = $request->Kelas;
        $tabunganSiswa->Alamat = $request->Alamat;
        $tabunganSiswa->jumlah_Yang_di_Tabung = $request->jumlah_Yang_di_Tabung;
    
        // Menghitung ulang jumlah tabungan
        $tabunganSiswa->jumlah_Tabungan = $tabunganSiswa->jumlah_Yang_di_Tabung + $request->Uang_Masuk - $request->Uang_Keluar;
        
        // Menyimpan uang masuk dan keluar
        $tabunganSiswa->Uang_Masuk = $request->Uang_Masuk ?? 0;
        $tabunganSiswa->Uang_Keluar = $request->Uang_Keluar ?? 0;
    
        $tabunganSiswa->save();
    
        return redirect()->route('Tabungan.Siswa.index')->with('success', 'Tabungan berhasil diperbarui!');
    }
    
    public function show(TabunganSiswa $siswa)
    {
        return view('tabungan_siswa.show', compact('siswa'));
    }

    public function edit(TabunganSiswa $siswa)
    {
        return view('tabungan_siswa.edit', compact('siswa'));
    }
    
    public function destroy(TabunganSiswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('Tabungan.Siswa.index')->with('success', 'Tabungan siswa berhasil dihapus.');
    }
}
