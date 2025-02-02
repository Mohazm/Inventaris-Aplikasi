@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Tambah Tabungan Siswa</h2>
        <form action="{{ route('Tabungan.Siswa.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nama Siswa</label>
                <input type="text" name="Nama_Siswa" value="{{ old('Nama_Siswa') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('Nama_Siswa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Kelas</label>
                <input type="text" name="Kelas" value="{{ old('Kelas') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('Kelas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Alamat</label>
                <input type="text" name="Alamat" value="{{ old('Alamat') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('Alamat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Yang Ditabung</label>
                <input type="number" name="jumlah_Yang_di_Tabung" value="{{ old('jumlah_Yang_di_Tabung') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('jumlah_Yang_di_Tabung') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Yang Ditabung</label>
                <input type="number" name="Uang_Masuk" value="{{ old('Uang_Masuk') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('Uang_Masuk') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Yang Ditabung</label>
                <input type="number" name="Uang_Keluar" value="{{ old('Uang_Keluar') }}" class="w-full p-3 border rounded-lg focus:ring focus:ring-purple-400">
                @error('Uang_Keluar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-800 transition">Simpan</button>
        </form>
    </div>
</div>
@endsection
