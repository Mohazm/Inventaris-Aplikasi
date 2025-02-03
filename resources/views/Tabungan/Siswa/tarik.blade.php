@extends('layout.AppTabungan')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Tarik Tabungan - {{ $tabunganSiswa->Nama_Siswa }}</h3>
    <p class="text-gray-600 mb-4">Saldo Saat Ini: <strong class="text-green-600">Rp{{ number_format($tabunganSiswa->jumlah_tabungan, 2) }}</strong></p>

    @if(session('error'))
        <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('tabungan.tarik', $tabunganSiswa->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="jumlah_tabungan" class="block text-gray-700 font-medium">Jumlah Penarikan</label>
            <input type="number" id="jumlah_tabungan" name="jumlah_tabungan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" min="1" required>
        </div>
        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition duration-300">
            Tarik
        </button>
    </form>
</div>
@endsection 
