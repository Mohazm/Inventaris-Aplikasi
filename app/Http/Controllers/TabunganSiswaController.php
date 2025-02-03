<?php
namespace App\Http\Controllers;

use App\Models\TabunganSiswa;
use Illuminate\Http\Request;

class TabunganSiswaController extends Controller
{
    public function index()
    {
        $tabungans = TabunganSiswa::latest()->paginate(10);
        return view('tabungan.siswa.index', compact('tabungans'));
    }

    public function create()
    {
        return view('tabungan.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Siswa' => 'required|string|max:255',
            'Kelas' => 'required|string|max:100',
            'Alamat' => 'nullable|string|max:255',
            'jumlah_Yang_di_Tabung' => 'required|numeric|min:0',
        ]);

        $tabunganSiswa = new TabunganSiswa();
        $tabunganSiswa->Nama_Siswa = $request->Nama_Siswa;
        $tabunganSiswa->Kelas = $request->Kelas;
        $tabunganSiswa->Alamat = $request->Alamat;
        $tabunganSiswa->jumlah_Yang_di_Tabung = $request->jumlah_Yang_di_Tabung;

        // Uang Masuk diisi dengan jumlah yang ditabung
        $tabunganSiswa->Uang_Masuk = $request->jumlah_Yang_di_Tabung;
        $tabunganSiswa->Uang_Keluar = 0; // Uang Keluar masih 0 karena belum ada penarikan

        // Jumlah tabungan sama dengan jumlah yang ditabung
        $tabunganSiswa->jumlah_Tabungan = $request->jumlah_Yang_di_Tabung;

        $tabunganSiswa->save();

        return redirect()->route('tabungan.siswa.index')->with('success', 'Tabungan berhasil ditambahkan!');
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

        return redirect()->route('tabungan.siswa.index')->with('success', 'Tabungan berhasil diperbarui!');
    }

    public function show(TabunganSiswa $siswa)
    {
        return view('tabungan.siswa.show', compact('siswa'));
    }

    public function edit(TabunganSiswa $siswa)
    {
        return view('tabungan.siswa.edit', compact('siswa'));
    }

    public function destroy(TabunganSiswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('tabungan.siswa.index')->with('success', 'Tabungan siswa berhasil dihapus.');
    }




    public function formTarik($id)
    {
        $tabunganSiswa = TabunganSiswa::findOrFail($id);
        return view('tabungan.siswa.tarik', compact('tabunganSiswa'));
    }

    public function tarik(Request $request, $id)
    {
        $request->validate([
            'jumlah_tabungan' => 'required|numeric|min:1',
        ]);

        $tabunganSiswa = TabunganSiswa::findOrFail($id);

        // Pastikan saldo cukup untuk penarikan
        if ($tabunganSiswa->jumlah_tabungan < $request->jumlah_tabungan) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi!');
        }

        // Kurangi saldo tabungan
        $tabunganSiswa->jumlah_tabungan -= $request->jumlah_tabungan;
        $tabunganSiswa->Uang_Keluar += $request->jumlah_tabungan; // Tambahkan ke Uang Keluar
        $tabunganSiswa->save();

        return redirect()->route('tabungan.siswa.index')->with('success', 'Penarikan berhasil!');
    }

}
